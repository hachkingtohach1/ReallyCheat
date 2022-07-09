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
namespace hachkingtohach1\reallycheat\utils;

use hachkingtohach1\reallycheat\player\RCPlayerAPI;
use hachkingtohach1\reallycheat\config\ConfigManager;

class ReplaceText extends ConfigManager{

    public static function replace(RCPlayerAPI $player, string $text, string $module = "", string $subType = "") :string{
        $keys = [
            "{prefix}",
            "{player}",
            "{module}",
            "{subtype}",
            "{time}",
            "{violation}",
            "{timechat}",
            "{code}",
            "{tick}"
        ];
        $replace = [
            self::getData(self::PREFIX), 
            $player->getName(), 
            $module, 
            $subType,
            date("F d, Y h:i:sA", time()), 
            $player->getRealViolation($module),
            self::getData(self::CHAT_SPAM_DELAY),
            $player->getCaptchaCode(),
            microtime()
        ];
        return str_replace($keys, $replace, $text);
    }

}