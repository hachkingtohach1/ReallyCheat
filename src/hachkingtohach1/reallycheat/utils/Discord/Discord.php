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
namespace hachkingtohach1\reallycheat\utils\Discord;

use hachkingtohach1\reallycheat\config\ConfigManager;
use hachkingtohach1\reallycheat\utils\Discord\Webhook;
use hachkingtohach1\reallycheat\utils\Discord\Message;
use hachkingtohach1\reallycheat\player\RCPlayerAPI;
use hachkingtohach1\reallycheat\utils\ReplaceText;

class Discord extends ConfigManager{

	public static function sendMessage(string $text){
		$discord = new Webhook(self::getData(self::DISCORD_WEBHOOK));
		$msg = new Message();
		$msg->setUsername("ReallyCheat");
		$msg->setAvatarURL("https://raw.githubusercontent.com/hachkingtohach1/Image/main/icon.png");
		$msg->setContent($text); 
		$discord->send($msg);
	}

	public static function onJoin(RCPlayerAPI $player){
		if(self::getData(self::DISCORD_PLAYER_JOIN_ENABLE) === true){
			self::sendMessage(ReplaceText::replace($player, self::getData(self::DISCORD_PLAYER_JOIN_TEXT)));
		}
	}

	public static function onLeft(RCPlayerAPI $player){
		if(self::getData(self::DISCORD_PLAYER_LEFT_ENABLE) === true){
			self::sendMessage(ReplaceText::replace($player, self::getData(self::DISCORD_PLAYER_LEFT_TEXT)));
		}
	}

	public static function onKick(RCPlayerAPI $player, string $reason){
		if(self::getData(self::DISCORD_PLAYER_KICK_ENABLE) === true){
			self::sendMessage(ReplaceText::replace($player, self::getData(self::DISCORD_PLAYER_KICK_TEXT), $reason));
		}
	}

	public static function onBan(RCPlayerAPI $player, string $reason){
		if(self::getData(self::DISCORD_PLAYER_BAN_ENABLE) === true){
			self::sendMessage(ReplaceText::replace($player, self::getData(self::DISCORD_PLAYER_BAN_TEXT), $reason));
		}
	}

	public static function onLagging(RCPlayerAPI $player){
		if(self::getData(self::DISCORD_SERVER_LAGGING_ENABLE) === true){
			self::sendMessage(ReplaceText::replace($player, self::getData(self::DISCORD_SERVER_LAGGING_TEXT)));
		}
	}
	
}
