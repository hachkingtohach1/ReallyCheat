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
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\network\mcpe\protocol\DataPacket;

class SpamA extends Check{

    public function getName() :string{
        return "Spam";
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
        return true;
    }

    public function maxViolations() :int{
        return 5;
    }

    public function check(DataPacket $packet, RCPlayerAPI $playerAPI) :void{}

    public function checkEvent(Event $event, RCPlayerAPI $playerAPI) :void{
        if($event instanceof PlayerChatEvent){
            $chatTick = $playerAPI->getExternalData("SpamATick");
            $violationChat = $playerAPI->getExternalData("ViolationSpamA");
            if(!$event->isCancelled()){
                if($chatTick !== null || $violationChat !== null){
                    $diff = microtime(true) - $chatTick;
                    if($diff <= self::getData(self::CHAT_SPAM_DELAY)){
                        if($violationChat <= 5){                       
                            $playerAPI->getPlayer()->sendMessage($this->replaceText($playerAPI, self::getData(self::CHAT_SPAM_TEXT), $this->getName(), $this->getSubType()));
                            $playerAPI->setExternalData("SpamATick", microtime(true));
                            $playerAPI->setExternalData("ViolationSpamA", $violationChat + 1);
                            $this->failed($playerAPI);
                            $event->cancel();
                        }else{                          
                            $playerAPI->setExternalData("SpamATick", microtime(true));
                            $playerAPI->setExternalData("ViolationSpamA", 0);
                            $event->cancel();
                        }
                    }else{
                        $playerAPI->setExternalData("SpamATick", microtime(true));
                        $playerAPI->setExternalData("ViolationSpamA", 0);
                    }
                }else{
                    $playerAPI->setExternalData("SpamATick", microtime(true));
                    $playerAPI->setExternalData("ViolationSpamA", 0);
                }
            }
        }
    }

}