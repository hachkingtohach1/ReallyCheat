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
namespace hachkingtohach1\reallycheat\checks\aimassist;

use hachkingtohach1\reallycheat\checks\Check;
use hachkingtohach1\reallycheat\player\RCPlayerAPI;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\PlayerAuthInputPacket;

class AimAssistD extends Check{

    public function getName() :string{
        return "AimAssist";
    }

    public function getSubType() :string{
        return "D";
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
        return 3;
    }
    
    public function check(DataPacket $packet, RCPlayerAPI $player) :void{
        if($packet instanceof PlayerAuthInputPacket){    
            if(                        
                !$player->isSurvival() ||
                $player->getAttackTicks() > 100 ||
                $player->getTeleportTicks() < 100 ||
                $player->isFlying() ||
                $player->getAllowFlight()          
            ){
                return;
            }   
            $nLocation = $player->getNLocation();
            if(!empty($nLocation)){
                $abs = abs($nLocation["to"]->getYaw() - $nLocation["from"]->getYaw());
                $abs2 = abs($nLocation["to"]->getPitch() - $nLocation["from"]->getPitch());
                if($abs > 0.0 && $abs < 0.8 && $abs2 > 0.279 && $abs2 < 0.28090858){
                    $this->failed($player);
                }
            }
        }
    }

}