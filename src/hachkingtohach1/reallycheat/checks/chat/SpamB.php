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

class SpamB extends Check{

    public function getName() :string{
        return "Spam";
    }

    public function getSubType() :string{
        return "B";
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
        if($event instanceof PlayerChatEvent){
            if(!$event->isCancelled()){
                $message = $event->getMessage();
                $lastMessage = $player->getExternalData("lastMessage");
                if($lastMessage !== null){
                    $violation = false;
                    $explode = explode(" ", $message);
                    $explode2 = explode(" ", $lastMessage);
                    $countChar = count($explode);
                    $countChar2 = count($explode2);
                    if($countChar === $countChar2 and $countChar === 1){
                        $explode3 = str_split(strtolower($explode[0]));
                        $explode4 = str_split(strtolower($explode2[0]));
                        $count = 0;
                        foreach($explode3 as $key){
                            if(isset($explode4[$key])){
                                $count++;
                            }
                        }
                        if(count($explode4) - $count <= $count){
                            $violation = true;
                        }
                    }
                    $count2 = 0;
                    $chars = [];
                    foreach($explode as $text){     
                        $chars[strtolower($text)] = strtolower($text);               
                    }
                    foreach($explode2 as $text){
                        if(isset($chars[strtolower($text)])){
                            $count2++;
                        }
                    }
                    if(count($chars) - $count2 <= $count2){
                        $violation = true;
                    }
                    if($violation === true){
                        $player->sendMessage($this->replaceText($player, self::getData(self::CHAT_REPEAT_TEXT), $this->getName(), $this->getSubType()));
                        $event->cancel();
                    }
                    $player->setExternalData("lastMessage", $message);
                }else{
                    $player->setExternalData("lastMessage", $message);
                }
            }             
        }
    }

}