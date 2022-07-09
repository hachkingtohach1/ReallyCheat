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
namespace hachkingtohach1\reallycheat\events;

use hachkingtohach1\reallycheat\player\RCPlayerAPI;
use hachkingtohach1\reallycheat\config\ConfigManager;
use hachkingtohach1\reallycheat\utils\CharUtil;
use hachkingtohach1\reallycheat\utils\ReplaceText;

class CaptchaEvent extends ConfigManager{

    private RCPlayerAPI $player;

    public function __construct(RCPlayerAPI $player){
        $this->player = $player;
	}

    public function getPlayer() :RCPlayerAPI{
        return $this->player;
    }

    public function sendMessage(){
        $this->player->sendMessage(ReplaceText::replace($this->player, self::getData(self::CAPTCHA_TEXT)));
    }

    public function sendTip(){
        $this->player->sendTip(ReplaceText::replace($this->player, self::getData(self::CAPTCHA_TEXT)));
    }

    public function sendTitle(){
        $this->player->sendSubTitle(ReplaceText::replace($this->player, self::getData(self::CAPTCHA_TEXT)));
    }

    public function sendCaptcha(){
        if($this->player->isCaptcha()){
            if($this->player->getCaptchaCode() === "nocode"){
                $this->player->setCaptchaCode(CharUtil::generatorCode(self::getData(self::CAPTCHA_CODE_LENGTH)));
            }
            if(self::getData(self::CAPTCHA_RANDOMIZE) === true){               
                switch(rand(1, 3)){
                    case 1:
                        $this->sendMessage();
                        break;                    
                    case 2:
                        $this->sendTip();
                        break;
                    case 3:
                        $this->sendTitle();
                        break;
                }
            }else{
                if(self::getData(self::CAPTCHA_MESSAGE) === true){
                    $this->sendMessage();
                }
                if(self::getData(self::CAPTCHA_TIP) === true){
                    $this->sendTip();
                }
                if(self::getData(self::CAPTCHA_TITLE) === true){
                    $this->sendTitle();
                }
            }
        }
    }

}