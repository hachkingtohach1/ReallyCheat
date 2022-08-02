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
use hachkingtohach1\reallycheat\utils\BlockUtil;
use hachkingtohach1\reallycheat\components\block\BlockLegacyIds;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerMoveEvent;

class Phase extends Check{

    public function getName() :string{
        return "Phase";
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
        return 1;
    }

    public function checkEvent(Event $event, RCPlayerAPI $playerAPI) :void{
        if($event instanceof PlayerMoveEvent){
            $player = $event->getPlayer();
            $world = $player->getWorld();
            $block = $world->getBlock($player->getLocation()->asVector3()->add(0, 0.75, 0));
            $skip = [BlockLegacyIds::SAND, BlockLegacyIds::GRAVEL, BlockLegacyIds::ANVIL, BlockLegacyIds::AIR];
            $skip2 = [BlockLegacyIds::TORCH, BlockLegacyIds::SIGN_POST, BlockLegacyIds::WALL_SIGN, BlockLegacyIds::REDSTONE_TORCH, BlockLegacyIds::REDSTONE_WIRE, BlockLegacyIds::SEA_PICKLE, BlockLegacyIds::REPEATER_BLOCK, BlockLegacyIds::LANTERN, BlockLegacyIds::COMPARATOR_BLOCK];
            if($player->isSurvival() && !$playerAPI->isOnCarpet() && !$playerAPI->isOnPlate() && !$playerAPI->isOnDoor() && !$playerAPI->isOnSnow() && !$playerAPI->isOnPlant() && !$playerAPI->isOnAdhesion() && !$playerAPI->isOnStairs() && !$playerAPI->isInLiquid() && !$playerAPI->isInWeb() && !in_array($block->getId(), $skip) && !BlockUtil::isUnderBlock($event->getTo(), $skip2, 0)){
                $event->cancel();
            }
        }
    }

}