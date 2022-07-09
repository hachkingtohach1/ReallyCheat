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
namespace hachkingtohach1\reallycheat\checks\chat;

use hachkingtohach1\reallycheat\checks\Check;
use hachkingtohach1\reallycheat\player\RCPlayerAPI;
use pocketmine\event\Event;
use pocketmine\event\server\CommandEvent;
use pocketmine\network\mcpe\protocol\DataPacket;

class SpamC extends Check{

    public function getName() :string{
        return "Spam";
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
        return false;
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

    public function check(DataPacket $packet, RCPlayerAPI $player) :void{}

    public function checkEvent(Event $event, RCPlayerAPI $player) :void{     
        if($event instanceof CommandEvent){
            if(!$event->isCancelled()){
                $command = $event->getCommand();
                $lastTicks = $player->getExternalData("lastTickSC");
                if($lastTicks !== null){
                    $diff = microtime(true) - $lastTicks;
                    if($diff < self::getData(self::CHAT_COMMAND_SPAM_DELAY)){
                        if(in_array($command, self::getData(self::CHAT_COMMAND_SPAM_COMMANDS))){
                            $player->sendMessage(self::getData(self::CHAT_COMMAND_SPAM_TEXT));
                            $event->setCommand("");
                            $event->cancel();
                            var_dump("Hú");
                        }                  
                    }else{
                        $player->unsetExternalData("lastTickSC"); 
                    }
                }else{
                    $player->setExternalData("lastTickSC", microtime(true));
                }
            }             
        }
    }

}