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

use hachkingtohach1\reallycheat\components\registry\RCListener;
use hachkingtohach1\reallycheat\player\RCPlayerAPI;
use hachkingtohach1\reallycheat\utils\Discord\Discord;
use hachkingtohach1\reallycheat\config\ConfigManager;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class ServerListener extends RCListener{

    private array $ip = [];

    public function __construct(){}

    public function onPlayerPreLogin(PlayerPreLoginEvent $event){
        $ip = $event->getIp();
        if(!isset($this->ip[$ip])){
            $this->ip[$ip] = 1;
        }else{
            if($this->ip[$ip] >= ConfigManager::getData(ConfigManager::NETWORK_LIMIT)){
                $event->setKickReason(0, ConfigManager::getData(ConfigManager::NETWORK_MESSAGE));
                $event->getFinalKickMessage();
            }else{
                $this->ip[$ip] += 1;
            }
        } 
    }

    public function onPlayerJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        if($player instanceof RCPlayerAPI){
            Discord::onJoin($player);
        }
    }

    public function onPlayerQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        if($player instanceof RCPlayerAPI){
            Discord::onLeft($player);
        }
        $ip = $player->getNetworkSession()->getIp();
        if(isset($this->ip[$ip])){
            $this->ip[$ip] -= 1;
        }
    }

    public function onPlayerChat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        $message = $event->getMessage();
        if($player instanceof RCPlayerAPI){
            if($player->isCaptcha()){
                if($message === $player->getCaptchaCode()){
                    $player->setCaptcha(false);
                    $player->setCaptchaCode("nocode");
                }
                $event->cancel();
            }
        }
    }

    public function onEntityDamageByEntity(EntityDamageByEntityEvent $event){
        $damager = $event->getDamager();
        if($damager instanceof RCPlayerAPI){
            if($damager->isCaptcha()){
                $event->cancel();
            }
        }
    }

    public function onPlayerInteract(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        if($player instanceof RCPlayerAPI){
            if($player->isCaptcha()){
                $event->cancel();
            }
        }
    }

    public function onPlayerMove(PlayerMoveEvent $event){
        $player = $event->getPlayer();
        if($player instanceof RCPlayerAPI){
            if($player->isCaptcha()){
                $event->cancel();
            }
        }
    }

    public function onBlockBreak(BlockBreakEvent $event){
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if($player instanceof RCPlayerAPI){
            if($player->isCaptcha()){
                $event->cancel();
            }
        }        
    }
    
}
