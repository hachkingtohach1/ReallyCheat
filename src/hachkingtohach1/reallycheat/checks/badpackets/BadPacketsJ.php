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

class BadPacketsJ extends Check{

    public function getName() :string{
        return "AntiVoid";
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

    public function check(DataPacket $packet, RCPlayerAPI $playerAPI) :void{
        if(
            $playerAPI->isOnAdhesion() ||
            $playerAPI->isInLiquid() ||
            $playerAPI->isInWeb() ||
            $playerAPI->getDeathTicks() < 100 ||
            $playerAPI->getJumpTicks() < 60 ||
            $playerAPI->getTeleportTicks() < 100 ||
            $playerAPI->isOnGround()
        ){ 
            return;
        }
        $lastY = $playerAPI->getExternalData("lastYB");
        $playerAPI->setExternalData("lastYB", $playerAPI->getPlayer()->getLocation()->getY());
        if($lastY !== null && $playerAPI->isOnGround()){
            if($lastY < $playerAPI->getPlayer()->getLocation()->getY()){
                $this->failed($playerAPI);
            }
            $playerAPI->unsetExternalData("lastYB");
        }
    }

}