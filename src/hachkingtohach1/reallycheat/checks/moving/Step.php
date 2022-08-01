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
namespace hachkingtohach1\reallycheat\checks\moving;

use hachkingtohach1\reallycheat\checks\Check;
use hachkingtohach1\reallycheat\player\RCPlayerAPI;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerMoveEvent;

class Step extends Check{

    public function getName() :string{
        return "Step";
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
        return 3;
    }

    public function checkEvent(Event $event, RCPlayerAPI $playerAPI) :void{
        if($event instanceof PlayerMoveEvent){
            $player = $event->getPlayer();
            if(
                $playerAPI->getPing() > self::getData(self::PING_LAGGING) ||
                !$playerAPI->isOnGround() ||
                !$player->isSurvival() ||
                $player->getAllowFlight() ||
                $player->isFlying() ||
                $playerAPI->isInLiquid() ||
                $playerAPI->isOnAdhesion() ||
                $playerAPI->getTeleportTicks() < 40 ||
                $playerAPI->getAttackTicks() < 40 ||
                $playerAPI->getDeathTicks() < 40 ||
                $playerAPI->getPlacingTicks() < 40 ||
                $event->isCancelled()
            ){
                return;
            }
            $lastY = $playerAPI->getExternalData("lastY");
            $locationPlayer = $player->getLocation();
            $limit = 0.25;
            if($lastY !== null){
                $diff = $locationPlayer->getY() - $lastY;
                $limit += $playerAPI->isOnStairs() ? 0.5 : 0;
                $limit += $playerAPI->getJumpTicks() < 40 ? 0.4 : 0;
                if($diff > $limit){
                    $this->failed($playerAPI);
                }
                $playerAPI->unsetExternalData("lastY");
            }else{
                $playerAPI->setExternalData("lastY", $locationPlayer->getY());
            }
        }
    }

}