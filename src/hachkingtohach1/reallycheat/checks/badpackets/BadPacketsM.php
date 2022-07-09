<?php
/**
 *  Copyright (c) 2022 hachkingtohach1
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 *  SOFTWARE.
 */
namespace hachkingtohach1\reallycheat\checks\badpackets;

use hachkingtohach1\reallycheat\checks\Check;
use hachkingtohach1\reallycheat\player\RCPlayerAPI;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\PlayerAuthInputPacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;

class BadPacketsM extends Check{

    public function getName() :string{
        return "Speed";
    }

    public function getSubType() :string{
        return "B";
    }

    public function enable() :bool{
        return true;
    }

    public function ban() :bool{
        return false;
    }

    public function transfer() :bool{
        return true;
    }

    public function flag() :bool{
        return false;
    }

    public function captcha() :bool{
        return false;
    }

    public function maxViolations() :int{
        return 4;
    }

    public function check(DataPacket $packet, RCPlayerAPI $player) :void{       
        $nLocation = $player->getNLocation();
        if($player->getOnlineTime() > 10 && !empty($nLocation) && $player->isSurvival()){  
            $recived = false;
            if($packet instanceof MovePlayerPacket){
                $recived = true;
            }
            if($packet instanceof PlayerAuthInputPacket){ 
                $limit = $player->getMovementSpeed() * 4.8; 
                $distX = $nLocation["to"]->getX() - $nLocation["from"]->getX();
                $distZ = $nLocation["to"]->getZ() - $nLocation["from"]->getZ(); 
                $dist = ($distX * $distX) + ($distZ * $distZ);
                $lastDist = $dist;
                $shiftedLastDist = $lastDist * 0.91;
                $equalness = $dist - $shiftedLastDist;
                $scaledEqualness = $equalness * 138;  
                $idBlockDown = $player->getWorld()->getBlockAt((int)$player->getLocation()->getX(), (int)$player->getLocation()->getY() - 0.01, (int)$player->getLocation()->getZ())->getId();            
                $isFalling = $player->getLastGroundY() > $player->getlocation()->getY() ? true : false;               
                $limit += $player->getJumpTicks() < 40 ? $limit : 0;             
                if($player->isOnAdhesion() && !$player->isOnIce() && $player->getAttackTicks() > 100 && $player->isSurvival() && !$recived && !$isFalling && $idBlockDown !== 0){    
                    if($scaledEqualness > $limit and $player->getPing() < self::getData(self::PING_LAGGING)){
                        $this->failed($player);
                    }
                }
            }
        }
    }

}