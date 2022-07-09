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
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;

class BadPacketsG extends Check{

    public function getName() :string{
        return "AutoClick";
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
        return 25;
    }

    public function check(DataPacket $packet, RCPlayerAPI $player) :void{
        $ticks = $player->getExternalData("ticksClick");
        $avgSpeed = $player->getExternalData("avgSpeed");
        $avgDeviation = $player->getExternalData("avgDeviation");
        if($packet instanceof LevelSoundEventPacket){
            if($packet->sound === LevelSoundEvent::ATTACK_NODAMAGE){
                if($ticks !== null && $avgSpeed !== null && $avgDeviation !== null){
                    $player->setExternalData("ticksClick", 0);
                    if($player->isDigging() || $ticks > 5){
                        $player->unsetExternalData("ticksClick");
                        $player->unsetExternalData("avgSpeed");
                        $player->unsetExternalData("avgDeviation"); 
                        return;
                    }else{
                        $player->getExternalData("ticksClick", $ticks + 1);
                    }
                    $speed = $ticks * 50;
                    $player->setExternalData("avgSpeed", (($avgSpeed * 14) + $speed) / 15);
                    $deviation = abs($speed - $player->getExternalData("avgSpeed"));
                    $player->setExternalData("avgDeviation", (($avgDeviation * 9) + $deviation) / 10);
                    if($player->getExternalData("avgDeviation") < 5){
                        $this->failed($player);
                    }
                }else{
                    $player->setExternalData("ticksClick", 0);
                    $player->setExternalData("avgSpeed", 0);
                    $player->setExternalData("avgDeviation", 0);   
                }
            }
        }
    }

}