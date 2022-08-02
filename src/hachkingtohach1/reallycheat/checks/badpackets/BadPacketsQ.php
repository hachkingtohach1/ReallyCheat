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

class BadPacketsQ extends Check{

    public function getName() :string{
        return "Timer";
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
        if($packet instanceof PlayerAuthInputPacket){
            if($playerAPI->getOnlineTime() < 10 || $playerAPI->getDeathTicks() < 40){
                return;
            }
            $point = $playerAPI->getExternalData("pointQ");
            $lastTime = $playerAPI->getExternalData("lastTimeQ");
            if($lastTime === null && $point === null){
                $playerAPI->setExternalData("lastTimeQ", microtime(true));
                $playerAPI->setExternalData("pointQ", 1);
                return;
            }
            $timeDiff = microtime(true) - $lastTime;
            if($timeDiff > 1){
                if($point < 17){
                    $this->failed($playerAPI);
                }
                $playerAPI->unsetExternalData("pointQ");
                $playerAPI->unsetExternalData("lastTimeQ");
            }else{
                $playerAPI->setExternalData("pointQ", $point + 1);
            }           
        }
    }

}