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
namespace hachkingtohach1\reallycheat\checks;

use hachkingtohach1\reallycheat\RCAPIProvider;
use hachkingtohach1\reallycheat\player\RCPlayerAPI;
use hachkingtohach1\reallycheat\config\ConfigManager;
use hachkingtohach1\reallycheat\api\logging\LogManager;
use hachkingtohach1\reallycheat\utils\ReplaceText;
use hachkingtohach1\reallycheat\events\BanEvent;
use hachkingtohach1\reallycheat\events\KickEvent;
use hachkingtohach1\reallycheat\events\ServerLagEvent;
use hachkingtohach1\reallycheat\task\ServerTickTask;
use pocketmine\event\Event;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\console\ConsoleCommandSender;

abstract class Check extends ConfigManager{

    public abstract function getName() :string;

    public abstract function getSubType() :string;

    public abstract function enable() :bool;

    public abstract function ban() :bool;

    public abstract function transfer() :bool;

    public abstract function flag() :bool;

    public abstract function captcha() :bool;

    public abstract function maxViolations() :int;

    public function check(DataPacket $packet, RCPlayerAPI $player) :void{}

    public function checkEvent(Event $event, RCPlayerAPI $player) :void{}

    public function checkJustEvent(Event $event) :void{}

    public function replaceText(RCPlayerAPI $player, string $text, string $reason = "", string $subType = "") :string{
        return ReplaceText::replace($player, $text, $reason, $subType);
    }

    public function failed(RCPlayerAPI $player) :bool{
        $canCheck = self::getData(self::CHECK.".".strtolower($this->getName()).".enable");
        $maxViolations = self::getData(self::CHECK.".".strtolower($this->getName()).".maxvl");
        if($canCheck !== null){
            if($canCheck === false){
                return false;
            }
        }
        if(ServerTickTask::getInstance()->isLagging(microtime(true)) === true){
            (new ServerLagEvent($player))->isLagging();
            return false;
        }
        $randomNumber = rand(0, 100);
        $server = $player->getServer();
        $randomizeBan = self::getData(self::BAN_RANDOMIZE) === true ? ($randomNumber > 75 ? true : false) : true;
        $randomizeTransfer = self::getData(self::TRANSFER_RANDOMIZE) === true ? ($randomNumber > 75 ? true : false) : true;
        $randomizeCaptcha = self::getData(self::CAPTCHA_RANDOMIZE) === true ? ($randomNumber > 75 ? true : false) : true;
        $notify = self::getData(self::ALERTS_ENABLE) === true ? true : false;
        $byPass = self::getData(self::PERMISSION_BYPASS_ENABLE) === true ? ($player->hasPermission(self::getData(self::PERMISSION_BYPASS_PERMISSION)) ? true : false) : false;
        $reachedMaxViolations = $player->getViolation($this->getName()) >= $this->maxViolations() ? true : false;
        $reachedMaxRealViolations = $player->getRealViolation($this->getName()) >= $maxViolations ? true : false;
        $player->addViolation($this->getName());
        $automatic = self::getData(self::PROCESS_AUTO) === true ? true : false;  
        if(!$this->enable()){
            return false;
        } 
        if($notify && $reachedMaxViolations){
            $player->addRealViolation($this->getName());
            if(self::getData(self::PERMISSION_BYPASS_ENABLE) === true){
                foreach(RCAPIProvider::getInstance()->getServer()->getOnlinePlayers() as $p){
                    if($p->hasPermission(self::getData(self::PERMISSION_BYPASS_PERMISSION))){
                        $p->sendMessage(ReplaceText::replace($player, self::getData(self::ALERTS_MESSAGE), $this->getName(), $this->getSubType()));
                    }
                }
            }else{
                $player->sendMessage(ReplaceText::replace($player, self::getData(self::ALERTS_MESSAGE), $this->getName(), $this->getSubType()));
            }          
        } 
        if($byPass){
            return false;
        }      
        if($this->flag()){
            $player->setFlagged(true);
            return true;
        }
        if($automatic && $reachedMaxRealViolations && $this->ban() && $randomizeBan && self::getData(self::BAN_ENABLE) === true){           
            foreach(self::getData(self::BAN_COMMANDS) as $command){
                $server->dispatchCommand(new ConsoleCommandSender($server, $server->getLanguage()), ReplaceText::replace($player, $command, $this->getName(), $this->getSubType()));
                $server->broadcastMessage(ReplaceText::replace($player, self::getData(self::BAN_MESSAGE), $this->getName(), $this->getSubType()));           
            }
            LogManager::sendLogger(ReplaceText::replace($player, self::getData(self::BAN_RECENT_LOGS_MESSAGE), $this->getName(), $this->getSubType()));
            (new BanEvent($player, $this->getName()))->ban();
            return true;
        }
        if($automatic && $reachedMaxRealViolations && $this->transfer() && $randomizeTransfer && self::getData(self::TRANSFER_ENABLE) === true){         
            if(self::getData(self::TRANSFER_USECOMMAND_ENABLE) === true){
                foreach(self::getData(self::TRANSFER_USECOMMAND_COMMANDS) as $command){
                    $server->dispatchCommand(new ConsoleCommandSender($server, $server->getLanguage()), ReplaceText::replace($player, $command, $this->getName(), $this->getSubType()));        
                }
            }else{
                $ip = explode(":", self::TRANSFER_IP);
                $port = isset($ip[1]) ? (is_numeric($ip[1]) ? $ip[1] : 19132) : 19123;
                $player->transfer($ip[0], $port);
            }
            $server->broadcastMessage(ReplaceText::replace($player, self::getData(self::TRANSFER_MESSAGE), $this->getName(), $this->getSubType()));
            LogManager::sendLogger(ReplaceText::replace($player, self::getData(self::TRANSFER_RECENT_LOGS_MESSAGE), $this->getName(), $this->getSubType()));
            (new KickEvent($player, $this->getName()))->kick();
        }
        if($reachedMaxRealViolations && $randomizeCaptcha && $this->captcha() && self::getData(self::CAPTCHA_ENABLE) === true){
            $player->setCaptcha(true);
        }
        return true;      
    }

}