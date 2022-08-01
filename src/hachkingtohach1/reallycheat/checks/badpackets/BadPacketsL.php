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
use hachkingtohach1\reallycheat\utils\BlockUtil;
use hachkingtohach1\reallycheat\components\block\BlockLegacyIds;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\PlayerAuthInputPacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;

class BadPacketsL extends Check{

    public function getName() :string{
        return "Speed";
    }

    public function getSubType() :string{
        return "A";
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
        return 8;
    }

    public function check(DataPacket $packet, RCPlayerAPI $playerAPI) :void{
        $nLocation = $playerAPI->getNLocation();
        $player = $playerAPI->getPlayer();
        if($playerAPI->getOnlineTime() > 10 && !empty($nLocation) && $player->isSurvival()){
            $recived = false;
            if($packet instanceof MovePlayerPacket){
                $recived = true;
            }
            if($packet instanceof PlayerAuthInputPacket){ 
                $limit = $player->getMovementSpeed() * 35;
                $distX = $nLocation["to"]->getX() - $nLocation["from"]->getX();
                $distZ = $nLocation["to"]->getZ() - $nLocation["from"]->getZ(); 
                $dist = ($distX * $distX) + ($distZ * $distZ);
                $lastDist = $dist;
                $shiftedLastDist = $lastDist * 0.91;
                $equalness = $dist - $shiftedLastDist;
                $scaledEqualness = $equalness * 138;  
                $idBlockDown = $player->getWorld()->getBlockAt((int)$player->getLocation()->getX(), (int)$player->getLocation()->getY() - 0.01, (int)$player->getLocation()->getZ())->getId();
                $isFalling = $playerAPI->getLastGroundY() > $player->getLocation()->getY();
                $limit += $playerAPI->getJumpTicks() < 40 ? ($limit / 3) : 0;
                $limit += $player->isSprinting() ? ($limit / 33) : 0;
                $effects = [];
                foreach($player->getEffects()->all() as $index => $effect){
                    $transtable = $effect->getType()->getName()->getText();
                    $effects[$transtable] = $effect->getEffectLevel() + 1;
                }
                $limit += isset($effects["potion.moveSpeed"]) ? (pow($effects["potion.moveSpeed"] * 2, 2) / 16) : 0;
                $limit -= $playerAPI->isInLiquid() ? ($limit / 2.6) : 0;
                $limit -= $playerAPI->isInWeb() ? ($limit / 1.1) : 0;
                $limit -= BlockUtil::isUnderBlock($nLocation["to"], [BlockLegacyIds::SOUL_SAND], 1) ? ($limit / 1.3) : 0;
                if($playerAPI->isOnGround() && !$playerAPI->isOnAdhesion() && !$playerAPI->isOnIce() && $playerAPI->getAttackTicks() > 100 && $player->isSurvival() && !$recived && !$isFalling && $idBlockDown !== 0){
                    if($scaledEqualness > $limit and $playerAPI->getPing() < self::getData(self::PING_LAGGING)){
                        $this->failed($playerAPI);
                    }
                }
            }
        }
    }

}