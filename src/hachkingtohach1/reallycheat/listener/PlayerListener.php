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

class PlayerListener extends RCListener{

    private array $blockInteracted = [];
    private array $clicksData = [];

    const DELTAL_TIME_CLICK = 1;
    const FILES = ["network", "fly", "payload", "chat", "scaffold", "aimassist", "badpackets", "blockbreak", "blockplace", "blockinteract", "fight", "inventory", "moving", "velocity"];
    
    public function __construct(){}

    public function onPlayerCreation(PlayerCreationEvent $event) {
		$event->setPlayerClass(RCPlayerAPI::class);
	}

    public function onDataPacketReceive(DataPacketReceiveEvent $event) :void{
        $packet = $event->getPacket();
        $player = $event->getOrigin()->getPlayer();
        if($player instanceof RCPlayerAPI){           
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
                    $this->addCPS($player);
                    $player->setCPS($this->getCPS($player));
                }
            }
            if($packet instanceof InventoryTransactionPacket){
                if($packet->trData instanceof UseItemOnEntityTransactionData){
                    $this->addCPS($player);
                    $player->setCPS($this->getCPS($player));                 
                }
            }         
        }
    }

	public function onPlayerMove(PlayerMoveEvent $event) :void{
        $player = $event->getPlayer();
        if($player instanceof RCPlayerAPI){
            $this->checkEvent($event, $player);           
            if($player->isFlagged()){
                $event->cancel(); 
                $player->setFlagged(false);
            }
            $player->setNLocation($event->getFrom(), $event->getTo());
            $player->setOnGround(BlockUtil::isOnGround($event->getTo(), 0) || BlockUtil::isOnGround($event->getTo(), 1));
            if($player->isOnGround()){
                $player->setLastGroundY($player->getPosition()->getY());
            }else{             
                $player->setLastNoGroundY($player->getPosition()->getY());
            }    
            if(BlockUtil::onSlimeBlock($event->getTo(), 0) || BlockUtil::onSlimeBlock($event->getTo(), 1)){ 
                $player->setSlimeBlockTicks(microtime(true));     
            }
            $player->setOnIce(BlockUtil::isOnIce($event->getTo(), 1) || BlockUtil::isOnIce($event->getTo(), 2));
            $player->setOnStairs(BlockUtil::isOnStairs($event->getTo(), 0) || BlockUtil::isOnStairs($event->getTo(), 1));
            $player->setUnderBlock(BlockUtil::isOnGround($player->getLocation(), -2));
            $player->setInLiquid(BlockUtil::isOnLiquid($event->getTo(), 0) || BlockUtil::isOnLiquid($event->getTo(), 1));
            $player->setOnAdhesion(BlockUtil::isOnAdhesion($event->getTo(), 0));
            $player->setOnPlant(BlockUtil::isOnPlant($event->getTo(), 0));
            $player->setOnDoor(BlockUtil::isOnDoor($event->getTo(), 0));
            $player->setOnCarpet(BlockUtil::isOnCarpet($event->getTo(), 0));
            $player->setOnPlate(BlockUtil::isOnPlate($event->getTo(), 0));
            $player->setOnSnow(BlockUtil::isOnSnow($event->getTo(), 0));
        }
    }

    public function onPlayerInteract(PlayerInteractEvent $event) :void{
        $player = $event->getPlayer();
        $block = $event->getBlock();        
        if(!isset($this->blockInteracted[$player->getXuid()])){
            $this->blockInteracted[$player->getXuid()] = $block;
        }else{
            unset($this->blockInteracted[$player->getXuid()]);
        }
        if($player instanceof RCPlayerAPI){
            if($player->isFlagged()){
                $event->cancel(); 
                $player->setFlagged(false);
            }
            $this->checkEvent($event, $player);     
        }
    }

    public function onPlayerBreak(BlockBreakEvent $event) :void{
        $block = $event->getBlock();
        $x = $block->getPosition()->getX();
        $z = $block->getPosition()->getZ();
        $player = $event->getPlayer();
        if($player instanceof RCPlayerAPI){ 
            $this->checkEvent($event, $player);
            if($player->isFlagged()){
                $event->cancel(); 
                $player->setFlagged(false);
            } 
            if(isset($this->blockInteracted[$player->getXuid()])){
                $blockInteracted = $this->blockInteracted[$player->getXuid()];       
                $xI = $blockInteracted->getPosition()->getX();
                $zI = $blockInteracted->getPosition()->getZ();
                if((int)$x != (int)$xI && (int)$z != (int)$zI){                  
                    $player->setActionBreakingSpecial(true);
                    $player->setBlocksBrokeASec($player->getBlocksBrokeASec() + 1);                   
                }else{
                    $player->setBlocksBrokeASec(0);  
                    unset($this->blockInteracted[$player->getXuid()]);
                }
            }           
		}
    }

    public function onPlayerPlace(BlockPlaceEvent $event) :void{
        $block = $event->getBlock();
        $x = $block->getPosition()->getX();
        $z = $block->getPosition()->getZ();
        $player = $event->getPlayer();
        if($player instanceof RCPlayerAPI){ 
            $player->setPlacingTicks(microtime(true));
            $this->checkEvent($event, $player);           
            if($player->isFlagged()){
                $event->cancel(); 
                $player->setFlagged(false);
            }
            if(isset($this->blockInteracted[$player->getXuid()])){
                $blockInteracted = $this->blockInteracted[$player->getXuid()];       
                $xI = $blockInteracted->getPosition()->getX();
                $zI = $blockInteracted->getPosition()->getZ();
                if((int)$x != (int)$xI && (int)$z != (int)$zI){
                    $player->setActionPlacingSpecial(true);
                    $player->setBlocksPlacedASec($player->getBlocksPlacedASec() + 1);                                      
                }else{
                    $player->setBlocksPlacedASec(0);
                    unset($this->blockInteracted[$player->getXuid()]);  
                }            
            }
		}
    }

    public function onPlayerItemUse(PlayerItemUseEvent $event){
        $player = $event->getPlayer();       
        if($player instanceof RCPlayerAPI){
            //TODO
        }
    }

    public function onInventoryTransaction(InventoryTransactionEvent $event){
        $player = $event->getTransaction()->getSource();
        if($player instanceof RCPlayerAPI){
            $this->checkEvent($event, $player);
            foreach($event->getTransaction()->getInventories() as $inventory){          
                if($inventory instanceof ArmorInventory){
                    $player->setTransactionArmorInventory(true);
                }
            }
        }
    }

    public function onInventoryOpen(InventoryOpenEvent $event){
        $player = $event->getPlayer();
        if($player instanceof RCPlayerAPI){
            $player->setInventoryOpen(true);
            $this->checkEvent($event, $player);
        }
    }

    public function onInventoryClose(InventoryCloseEvent $event){
        $player = $event->getPlayer();
        if($player instanceof RCPlayerAPI){
            $player->setInventoryOpen(false);
            $this->checkEvent($event, $player);
        }
    }

    public function onEntityTeleport(EntityTeleportEvent $event){
        $entity = $event->getEntity();
        if($entity instanceof RCPlayerAPI){
            $entity->setTeleportTicks(microtime(true));
        }
    }

    public function onPlayerJump(PlayerJumpEvent $event){
        $player = $event->getPlayer();
        if($player instanceof RCPlayerAPI){
            $player->setJumpTicks(microtime(true));         
        }
    }

    public function onPlayerJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        if($player instanceof RCPlayerAPI){
            $this->checkEvent($event, $player);
            $player->setJoinedAtTheTime(microtime(true));
        }
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
        $this->checkJustEvent($event); 
        if($cause === EntityDamageEvent::CAUSE_ENTITY_ATTACK && $damager instanceof RCPlayerAPI){
			//$event->setAttackCooldown(1);
            if($entity instanceof RCPlayerAPI){               
                $entity->setAttackTicks(microtime(true));               
            }   
            if($damager->isFlagged()){
                $event->cancel(); 
                $damager->setFlagged(false);
            }      
            $damager->setAttackTicks(microtime(true));           
        }
        if(in_array($cause, [EntityDamageEvent::CAUSE_ENTITY_EXPLOSION, EntityDamageEvent::CAUSE_BLOCK_EXPLOSION])){
            if($entity instanceof RCPlayerAPI){               
                $entity->setAttackTicks(microtime(true));               
            } 
        }
    }

    public function onPlayerDeath(PlayerDeathEvent $event){
        $player = $event->getPlayer();
        if($player instanceof RCPlayerAPI){
            $player->setDeathTicks(microtime(true));
        }
    }

    public function onPlayerChat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        if($player instanceof RCPlayerAPI){ 
            $this->checkEvent($event, $player);
        }
    }

    public function onPlayerItemHeld(PlayerItemHeldEvent $event){
        $player = $event->getPlayer();
        if($player instanceof RCPlayerAPI){ 
            $this->checkJustEvent($event);
        }
    }

    public function onCommandEvent(CommandEvent $event){
        $sender = $event->getSender();
        if($sender instanceof RCPlayerAPI){ 
            $this->checkEvent($event, $sender);
        }
    }

    public function onPlayerItemConsume(PlayerItemConsumeEvent $event){
        $player = $event->getPlayer();
        if($player instanceof RCPlayerAPI){
            $this->checkEvent($event, $player);
        }
    }

    private function addCPS(RCPlayerAPI $player) :void{
        $time = microtime(true);
        $this->clicksData[$player->getName()][] = $time;
    }

    private function getCPS(RCPlayerAPI $player) :int{
        $newTime = microtime(true);
        return count(array_filter($this->clicksData[$player->getName()] ?? [], static function(float $lastTime) use ($newTime):bool{
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