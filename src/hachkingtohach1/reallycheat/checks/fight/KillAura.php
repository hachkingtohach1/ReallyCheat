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
namespace hachkingtohach1\reallycheat\checks\fight;

use hachkingtohach1\reallycheat\checks\Check;
use hachkingtohach1\reallycheat\player\RCPlayerAPI;
use hachkingtohach1\reallycheat\utils\MathUtil;
use pocketmine\math\Vector3;
use pocketmine\event\Event;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\player\Player;

class KillAura extends Check{

    public function getName() :string{
        return "KillAura";
    }

    public function getSubType() :string{
        return "F";
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

    public function checkJustEvent(Event $event) :void{
        if($event instanceof EntityDamageByEntityEvent){
            $entity = $event->getEntity();
            $damager = $event->getDamager();
            $locDamager = $damager->getLocation();
            if($damager instanceof Player){
                $playerAPI = RCPlayerAPI::getRCPlayer($damager);
                $delta = MathUtil::getDeltaDirectionVector($damager, 3);
                $from = new Vector3($locDamager->getX(), $locDamager->getY() + $damager->getEyeHeight(), $locDamager->getZ());           
                $to = $damager->getLocation()->add($delta->getX(), $delta->getY() + $damager->getEyeHeight(), $delta->getZ());		
                $distance = MathUtil::distance($from, $to);
                $vector = $to->subtract($from->x, $from->y, $from->z)->normalize()->multiply(1);
                $entities = [];
                for($i = 0; $i <= $distance; $i += 1){
                    $from = $from->add($vector->x, $vector->y, $vector->z);
                    foreach($damager->getWorld()->getEntities() as $target){	
                        $distanceA = new Vector3($from->x, $from->y, $from->z);
                        if($target->getPosition()->distance($distanceA) <= 2.6){
                            $entities[$target->getId()] = $target;
                        }
                    }
                }
                if(!isset($entities[$entity->getId()])){
                    $this->failed($playerAPI);
                }
            }
        }
    }

}