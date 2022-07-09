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

class AimAssistB extends Check{

    public function getName() :string{
        return "AimAssist";
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
        return 2;
    }

    public function check(DataPacket $packet, RCPlayerAPI $player) :void{
        if($packet instanceof PlayerAuthInputPacket){    
            $nLocation = $player->getNLocation();
            if(!empty($nLocation)){
                $toYaw = $nLocation["to"]->getYaw();
                $fromYaw = $nLocation["from"]->getYaw();               
                $abs = abs($toYaw - $fromYaw);
                if($abs >= 1 && fmod($abs, 0.1) == 0){
                    if(fmod($abs, 1.0) == 0 || fmod($abs, 10.0) == 0 || fmod($abs, 30.0) == 0){
                        $this->failed($player);
                    }
                }   
                $toPitch = $nLocation["to"]->getPitch();
                $fromPitch = $nLocation["from"]->getPitch();            
                $abs2 = abs($toPitch - $fromPitch);
                if($abs2 >= 1 && fmod($abs2, 0.1) == 0){
                    if(fmod($abs2, 1.0) == 0 || fmod($abs2, 10.0) == 0 || fmod($abs2, 30.0) == 0){
                        $this->failed($player);
                    }
                }
            }
        }
    }

}