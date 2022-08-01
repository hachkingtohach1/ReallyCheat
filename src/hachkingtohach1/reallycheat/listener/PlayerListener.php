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
namespace hachkingtohach1\reallycheat\listener;

use hachkingtohach1\reallycheat\utils\Utils;
use hachkingtohach1\reallycheat\utils\BlockUtil;
use hachkingtohach1\reallycheat\player\RCPlayerAPI;
use hachkingtohach1\reallycheat\components\registry\RCListener;
use pocketmine\inventory\ArmorInventory;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\server\CommandEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemOnEntityTransactionData;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;
use pocketmine\player\Player;

class PlayerListener extends RCListener{

    private array $blockInteracted = [];
    private array $clicksData = [];

    const DELTAL_TIME_CLICK = 1;
    const FILES = ["network", "fly", "payload", "chat", "scaffold", "aimassist", "badpackets", "blockbreak", "blockplace", "blockinteract", "fight", "inventory", "moving", "velocity"];
    
    public function __construct(){}

    public function onDataPacketReceive(DataPacketReceiveEvent $event) :void{
        $packet = $event->getPacket();
        $player = $event->getOrigin()->getPlayer();
        if($player !== null){
            $playerAPI = RCPlayerAPI::getRCPlayer($player);
            foreach(self::FILES as $file){
                Utils::callDirectory("checks/$file", function (string $namespace) use($packet, $player): void{
                    $class = new $namespace();
                    if($class->enable()){                                       
                        $class->check($packet, $player);
                    }
                });
            }                
            if($packet instanceof LevelSoundEventPacket){
                if($packet->sound === LevelSoundEvent::ATTACK_NODAMAGE){
                    $this->addCPS($playerAPI);
                    $playerAPI->setCPS($this->getCPS($playerAPI));
                }
            }
            if($packet instanceof InventoryTransactionPacket){
                if($packet->trData instanceof UseItemOnEntityTransactionData){
                    $this->addCPS($playerAPI);
                    $playerAPI->setCPS($this->getCPS($playerAPI));
                }
            }         
        }
    }

    public function onPlayerMove(PlayerMoveEvent $event) :void{
        $player = $event->getPlayer();
        $playerAPI = RCPlayerAPI::getRCPlayer($player);

        $this->checkEvent($event, $playerAPI);
        if($playerAPI->isFlagged()){
            $event->cancel();
            $playerAPI->setFlagged(false);
        }
        $playerAPI->setNLocation($event->getFrom(), $event->getTo());
        $playerAPI->setOnGround(BlockUtil::isOnGround($event->getTo(), 0) || BlockUtil::isOnGround($event->getTo(), 1));
        if($playerAPI->isOnGround()){
            $playerAPI->setLastGroundY($player->getPosition()->getY());
        }else{
            $playerAPI->setLastNoGroundY($player->getPosition()->getY());
        }
        if(BlockUtil::onSlimeBlock($event->getTo(), 0) || BlockUtil::onSlimeBlock($event->getTo(), 1)){
            $playerAPI->setSlimeBlockTicks(microtime(true));
        }
        $playerAPI->setOnIce(BlockUtil::isOnIce($event->getTo(), 1) || BlockUtil::isOnIce($event->getTo(), 2));
        $playerAPI->setOnStairs(BlockUtil::isOnStairs($event->getTo(), 0) || BlockUtil::isOnStairs($event->getTo(), 1));
        $playerAPI->setUnderBlock(BlockUtil::isOnGround($player->getLocation(), -2));
        $playerAPI->setInLiquid(BlockUtil::isOnLiquid($event->getTo(), 0) || BlockUtil::isOnLiquid($event->getTo(), 1));
        $playerAPI->setOnAdhesion(BlockUtil::isOnAdhesion($event->getTo(), 0));
        $playerAPI->setOnPlant(BlockUtil::isOnPlant($event->getTo(), 0));
        $playerAPI->setOnDoor(BlockUtil::isOnDoor($event->getTo(), 0));
        $playerAPI->setOnCarpet(BlockUtil::isOnCarpet($event->getTo(), 0));
        $playerAPI->setOnPlate(BlockUtil::isOnPlate($event->getTo(), 0));
        $playerAPI->setOnSnow(BlockUtil::isOnSnow($event->getTo(), 0));
    }

    public function onPlayerInteract(PlayerInteractEvent $event) :void{
        $player = $event->getPlayer();
        $playerAPI = RCPlayerAPI::getRCPlayer($player);
        $block = $event->getBlock();
        if(!isset($this->blockInteracted[$player->getXuid()])){
            $this->blockInteracted[$player->getXuid()] = $block;
        }else{
            unset($this->blockInteracted[$player->getXuid()]);
        }

        if($playerAPI->isFlagged()){
            $event->cancel();
            $playerAPI->setFlagged(false);
        }
        $this->checkEvent($event, $playerAPI);

    }

    public function onPlayerBreak(BlockBreakEvent $event) :void{
        $block = $event->getBlock();
        $x = $block->getPosition()->getX();
        $z = $block->getPosition()->getZ();
        $player = $event->getPlayer();
        $playerAPI = RCPlayerAPI::getRCPlayer($player);

        $this->checkEvent($event, $playerAPI);
        if($playerAPI->isFlagged()){
            $event->cancel();
            $playerAPI->setFlagged(false);
        }
        if(isset($this->blockInteracted[$player->getXuid()])){
            $blockInteracted = $this->blockInteracted[$player->getXuid()];
            $xI = $blockInteracted->getPosition()->getX();
            $zI = $blockInteracted->getPosition()->getZ();
            if((int)$x != (int)$xI && (int)$z != (int)$zI){
                $playerAPI->setActionBreakingSpecial(true);
                $playerAPI->setBlocksBrokeASec($playerAPI->getBlocksBrokeASec() + 1);
            }else{
                $playerAPI->setBlocksBrokeASec(0);
                unset($this->blockInteracted[$player->getXuid()]);
            }
        }
    }

    public function onPlayerPlace(BlockPlaceEvent $event) :void{
        $block = $event->getBlock();
        $x = $block->getPosition()->getX();
        $z = $block->getPosition()->getZ();
        $player = $event->getPlayer();
        $playerAPI = RCPlayerAPI::getRCPlayer($player);

            $playerAPI->setPlacingTicks(microtime(true));
            $this->checkEvent($event, $playerAPI);
            if($playerAPI->isFlagged()){
                $event->cancel(); 
                $playerAPI->setFlagged(false);
            }
            if(isset($this->blockInteracted[$player->getXuid()])){
                $blockInteracted = $this->blockInteracted[$player->getXuid()];
                $xI = $blockInteracted->getPosition()->getX();
                $zI = $blockInteracted->getPosition()->getZ();
                if((int)$x != (int)$xI && (int)$z != (int)$zI){
                    $playerAPI->setActionPlacingSpecial(true);
                    $playerAPI->setBlocksPlacedASec($playerAPI->getBlocksPlacedASec() + 1);
                }else{
                    $playerAPI->setBlocksPlacedASec(0);
                    unset($this->blockInteracted[$player->getXuid()]);
                }            
            }

    }

    public function onPlayerItemUse(PlayerItemUseEvent $event){
        $player = $event->getPlayer();
        //TODO
    }


    public function onInventoryTransaction(InventoryTransactionEvent $event){
        $player = $event->getTransaction()->getSource();
        $playerAPI = RCPlayerAPI::getRCPlayer($player);

        $this->checkEvent($event, $playerAPI);
        foreach($event->getTransaction()->getInventories() as $inventory){
            if($inventory instanceof ArmorInventory){
                $playerAPI->setTransactionArmorInventory(true);
            }
        }
    }

    public function onInventoryOpen(InventoryOpenEvent $event){
        $player = $event->getPlayer();
        $playerAPI = RCPlayerAPI::getRCPlayer($player);
        $playerAPI->setInventoryOpen(true);
        $this->checkEvent($event, $playerAPI);
    }

    public function onInventoryClose(InventoryCloseEvent $event){
        $player = $event->getPlayer();
        $playerAPI = RCPlayerAPI::getRCPlayer($player);
        $playerAPI->setInventoryOpen(false);
        $this->checkEvent($event, $playerAPI);

    }

    public function onEntityTeleport(EntityTeleportEvent $event){
        $entity = $event->getEntity();
        if(!$entity instanceof Player){
            return;
        }
        $playerAPI = RCPlayerAPI::getRCPlayer($entity);
        $playerAPI->setTeleportTicks(microtime(true));
    }

    public function onPlayerJump(PlayerJumpEvent $event){
        $player = $event->getPlayer();
        $playerAPI = RCPlayerAPI::getRCPlayer($player);

        $playerAPI->setJumpTicks(microtime(true));

    }

    public function onPlayerJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $playerAPI = RCPlayerAPI::getRCPlayer($player);
        $this->checkEvent($event, $playerAPI);
        $playerAPI->setJoinedAtTheTime(microtime(true));
    }

    public function onPlayerPreLogin(PlayerPreLoginEvent $event){
        $this->checkJustEvent($event);
    }

    public function onEntityDamage(EntityDamageEvent $event){
        $this->checkJustEvent($event);
    }

    public function onEntityDamageByEntity(EntityDamageByEntityEvent $event){
        $cause = $event->getCause();
        $entity = $event->getEntity();
        $damager = $event->getDamager();
        if(!$damager instanceof Player){
            return;
        }
        $playerAPI = RCPlayerAPI::getRCPlayer($damager);
        $this->checkJustEvent($event); 
        if($cause === EntityDamageEvent::CAUSE_ENTITY_ATTACK){
            //$event->setAttackCooldown(1);
            if($entity instanceof Player){
                RCPlayerAPI::getRCPlayer($entity)->setAttackTicks(microtime(true));
            }   
            if($playerAPI->isFlagged()){
                $event->cancel(); 
                $playerAPI->setFlagged(false);
            }      
            $playerAPI->setAttackTicks(microtime(true));
        }
        if(in_array($cause, [EntityDamageEvent::CAUSE_ENTITY_EXPLOSION, EntityDamageEvent::CAUSE_BLOCK_EXPLOSION])){
            RCPlayerAPI::getRCPlayer($entity)->setAttackTicks(microtime(true));
        }
    }

    public function onPlayerDeath(PlayerDeathEvent $event){
        $player = $event->getPlayer();
        $playerAPI = RCPlayerAPI::getRCPlayer($player);
        $playerAPI->setDeathTicks(microtime(true));
    }

    public function onPlayerChat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        $playerAPI = RCPlayerAPI::getRCPlayer($player);
        $this->checkEvent($event, $playerAPI);
    }

    public function onPlayerItemHeld(PlayerItemHeldEvent $event){
        $player = $event->getPlayer();
        $playerAPI = RCPlayerAPI::getRCPlayer($player);
        $this->checkEvent($event, $playerAPI);
    }

    public function onCommandEvent(CommandEvent $event){
        $sender = $event->getSender();
        if(!$sender instanceof Player){
            return;
        }
        $playerAPI = RCPlayerAPI::getRCPlayer($sender);
        $this->checkEvent($event, $playerAPI);
    }

    public function onPlayerItemConsume(PlayerItemConsumeEvent $event){
        $player = $event->getPlayer();
        $playerAPI = RCPlayerAPI::getRCPlayer($player);
        $this->checkEvent($event, $playerAPI);
    }

    private function addCPS(RCPlayerAPI $player) :void{
        $time = microtime(true);
        $this->clicksData[$player->getPlayer()->getName()][] = $time;
    }

    private function getCPS(RCPlayerAPI $player) :int{
        $newTime = microtime(true);
        return count(array_filter($this->clicksData[$player->getPlayer()->getName()] ?? [], static function(float $lastTime) use ($newTime):bool{
            return ($newTime - $lastTime) <= self::DELTAL_TIME_CLICK;
        }));
    }
    
    private function checkEvent(Event $event, RCPlayerAPI $player){
        foreach(self::FILES as $file){
            Utils::callDirectory("checks/$file", function (string $namespace) use($event, $player): void{
                $class = new $namespace();
                if($class->enable()){                                       
                    $class->checkEvent($event, $player);
                }
            });
        }
    }

    private function checkJustEvent(Event $event){
        foreach(self::FILES as $file){
            Utils::callDirectory("checks/$file", function (string $namespace) use($event): void{
                $class = new $namespace();
                if($class->enable()){                                       
                    $class->checkJustEvent($event);
                }
            });
        }
    }

}