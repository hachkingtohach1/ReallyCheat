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
use hachkingtohach1\reallycheat\utils\MathUtil;
use hachkingtohach1\reallycheat\components\block\BlockLegacyIds;
use pocketmine\math\Vector3;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemOnEntityTransactionData;

class BadPacketsE extends Check{

    private bool $interact = false;

    public function getName() :string{
        return "KillAura";
    }

    public function getSubType() :string{
        return "C";
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
        if($event instanceof PlayerInteractEvent){
            $this->interact = true;
        }
    }

    public function check(DataPacket $packet, RCPlayerAPI $playerAPI) :void{
        if($playerAPI->getAttackTicks() > 40 || $this->interact){
            return;
        }
        $player = $playerAPI->getPlayer();
        $locPlayer = $player->getLocation();
        $delta = MathUtil::getDeltaDirectionVector($playerAPI, 3);
        $from = new Vector3($locPlayer->getX(), $locPlayer->getY() + $player->getEyeHeight(), $locPlayer->getZ());
        $to = $player->getLocation()->add($delta->getX(), $delta->getY() + $player->getEyeHeight(), $delta->getZ());
        $distance = MathUtil::distance($from, $to);
        $vector = $to->subtract($from->x, $from->y, $from->z)->normalize()->multiply(1);
        $entities = [];
        for($i = 0; $i <= $distance; $i += 1){
            $from = $from->add($vector->x, $vector->y, $vector->z);
            foreach($player->getWorld()->getEntities() as $target){
                $distanceA = new Vector3($from->x, $from->y, $from->z);
                if($target->getPosition()->distance($distanceA) <= 2 && $target->getId() !== $player->getId()){
                    $entities[$target->getId()] = $target;
                }
            }
        }  
        if($packet instanceof InventoryTransactionPacket){
            if($packet->trData instanceof UseItemOnEntityTransactionData){
                if($locPlayer->getPitch() < 30){
                    if(count($entities) < 1 && $player->getTargetBlock(10)->getId() !== BlockLegacyIds::AIR){
                        $this->failed($playerAPI);
                    }
                }
            }
        }
    }

}