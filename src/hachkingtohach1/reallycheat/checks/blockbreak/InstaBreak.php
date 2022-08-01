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
namespace hachkingtohach1\reallycheat\checks\blockbreak;

use hachkingtohach1\reallycheat\checks\Check;
use hachkingtohach1\reallycheat\player\RCPlayerAPI;
use pocketmine\event\Event;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\entity\effect\VanillaEffects;

class InstaBreak extends Check{

    public function getName() :string{
        return "InstaBreak";
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
        return false;
    }

    public function flag() :bool{
        return false;
    }

    public function captcha() :bool{
        return false;
    }

    public function maxViolations() :int{
        return 1;
    }

    public function checkEvent(Event $event, RCPlayerAPI $playerAPI) :void{
        $breakTimes = $playerAPI->getExternalData("breakTimes");
        if($event instanceof PlayerInteractEvent){
            if($event->getAction() === PlayerInteractEvent::LEFT_CLICK_BLOCK){
                $playerAPI->setExternalData("breakTimes", floor(microtime(true) * 20));
            }
        }
        if($event instanceof BlockBreakEvent){
            if(!$event->getInstaBreak()){               
                if($breakTimes === null){
                    $event->cancel();                   
                    return;
                }
                $target = $event->getBlock();
                $item = $event->getItem();
                $expectedTime = ceil($target->getBreakInfo()->getBreakTime($item) * 20);
                if(($haste = $playerAPI->getPlayer()->getEffects()->get(VanillaEffects::HASTE())) !== null){
                    $expectedTime *= 1 - (0.2 * $haste->getEffectLevel());
                }
                if(($miningFatigue = $playerAPI->getPlayer()->getEffects()->get(VanillaEffects::MINING_FATIGUE())) !== null){
                    $expectedTime *= 1 + (0.3 * $miningFatigue->getEffectLevel());
                }
                $expectedTime -= 1; 
                $actualTime = ceil(microtime(true) * 20) - $breakTimes;
                if($actualTime < $expectedTime){
                    $event->cancel();
                    return;
                }
                $playerAPI->unsetExternalData("breakTimes");
            }
        }
    }

}