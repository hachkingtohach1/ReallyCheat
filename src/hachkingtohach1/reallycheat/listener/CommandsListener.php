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

use hachkingtohach1\reallycheat\RCAPIProvider;
use hachkingtohach1\reallycheat\config\ConfigManager;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class CommandsListener{

    public function __construct(){}

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) :bool{
		$prefix = ConfigManager::getData(ConfigManager::PREFIX);
        $namecmd = $command->getName();
        if(in_array($namecmd, ["reallycheat", "rc"])){
            if(isset($args[0])){
                switch($args[0]){
                    case "about":
                        $sender->sendMessage(TextFormat::AQUA."Build: ".TextFormat::GRAY.RCAPIProvider::VERSION_PLUGIN.TextFormat::AQUA." Author: ".TextFormat::GRAY."hachkingtohach1(DragoVN)");
                        break;
                    case "notify":
                        if(isset($args[1])){
                            switch($args[1]){
                                case "toggle":
                                    $data = ConfigManager::getData(ConfigManager::ALERTS_ENABLE) === true ? ConfigManager::setData(ConfigManager::ALERTS_ENABLE, false) : ConfigManager::setData(ConfigManager::ALERTS_ENABLE, true);
                                    $sender->sendMessage($prefix.TextFormat::GRAY." Notify toggle is ".(ConfigManager::getData(ConfigManager::ALERTS_ENABLE) ? TextFormat::GREEN."enable" : TextFormat::RED."disable"));
                                    break;
                                case "admin":
                                    $data = ConfigManager::getData(ConfigManager::ALERTS_ADMIN) === true ? ConfigManager::setData(ConfigManager::ALERTS_ADMIN, false) : ConfigManager::setData(ConfigManager::ALERTS_ADMIN, true);
                                    $sender->sendMessage($prefix.TextFormat::GRAY." Notify admin mode is ".(ConfigManager::getData(ConfigManager::ALERTS_ADMIN) ? TextFormat::GREEN."enable" : TextFormat::RED."disable"));
                                    break;
                                default: $sender->sendMessage(TextFormat::RED."/".$namecmd.TextFormat::RESET." notify (toggle/admin) - Use to on/off notify.");
                            }
                        }else{
                            $sender->sendMessage(TextFormat::RED."/".$namecmd.TextFormat::RESET." notify (toggle/admin) - Use to on/off notify.");
                        }
                        break;
                    case "process":
                        if(isset($args[1])){
                            switch($args[1]){
                                case "auto":
                                    $data = ConfigManager::getData(ConfigManager::PROCESS_AUTO) === true ? ConfigManager::setData(ConfigManager::PROCESS_AUTO, false) : ConfigManager::setData(ConfigManager::PROCESS_AUTO, true);
                                    $sender->sendMessage($prefix.TextFormat::GRAY." Automatic processing is ".(ConfigManager::getData(ConfigManager::PROCESS_AUTO) ? TextFormat::GREEN."enable" : TextFormat::RED."disable"));
                                    break;                               
                                default: $sender->sendMessage(TextFormat::RED."/".$namecmd.TextFormat::RESET." process (auto/immediately) - Use to on/off process.");
                            }
                        }else{
                            $sender->sendMessage(TextFormat::RED."/".$namecmd.TextFormat::RESET." process (auto/immediately) - Use to on/off process.");
                        }
                        break;
                    case "xray":
                        $data = ConfigManager::getData(ConfigManager::XRAY_ENABLE) === true ? ConfigManager::setData(ConfigManager::XRAY_ENABLE, false) : ConfigManager::setData(ConfigManager::XRAY_ENABLE, true);
                        $sender->sendMessage($prefix.TextFormat::GRAY." AntiXray is ".(ConfigManager::getData(ConfigManager::XRAY_ENABLE) ? TextFormat::GREEN."enable" : TextFormat::RED."disable"));
                        break;
                    case "banmode":
                        if(isset($args[1])){
                            switch($args[1]){
                                case "toggle":
                                    $data = ConfigManager::getData(ConfigManager::BAN_ENABLE) === true ? ConfigManager::setData(ConfigManager::BAN_ENABLE, false) : ConfigManager::setData(ConfigManager::BAN_ENABLE, true);
                                    $sender->sendMessage($prefix.TextFormat::GRAY." Ban Mode is ".(ConfigManager::getData(ConfigManager::BAN_ENABLE) ? TextFormat::GREEN."enable" : TextFormat::RED."disable"));
                                    break;
                                case "randomize":
                                    $data = ConfigManager::getData(ConfigManager::BAN_RANDOMIZE) === true ? ConfigManager::setData(ConfigManager::BAN_RANDOMIZE, false) : ConfigManager::setData(ConfigManager::BAN_RANDOMIZE, true);
                                    $sender->sendMessage($prefix.TextFormat::GRAY." Ban Randomize mode is ".(ConfigManager::getData(ConfigManager::BAN_RANDOMIZE) ? TextFormat::GREEN."enable" : TextFormat::RED."disable"));
                                    break;
                                default: $sender->sendMessage(TextFormat::RED."/".$namecmd.TextFormat::RESET." banmode (toggle/randomize) - Use to on/off ban mode.");
                            }
                        }else{
                            $sender->sendMessage(TextFormat::RED."/".$namecmd.TextFormat::RESET." banmode (toggle/randomize) - Use to on/off ban mode.");
                        }
                        break;
                    case "transfermode":
                        if(isset($args[1])){
                            switch($args[1]){
                                case "toggle":
                                    $data = ConfigManager::getData(ConfigManager::TRANSFER_ENABLE) === true ? ConfigManager::setData(ConfigManager::TRANSFER_ENABLE, false) : ConfigManager::setData(ConfigManager::TRANSFER_ENABLE, true);
                                    $sender->sendMessage($prefix.TextFormat::GRAY." Transfer mode is ".(ConfigManager::getData(ConfigManager::TRANSFER_ENABLE) ? TextFormat::GREEN."enable" : TextFormat::RED."disable"));
                                    break;
                                case "randomize":
                                    $data = ConfigManager::getData(ConfigManager::TRANSFER_RANDOMIZE) === true ? ConfigManager::setData(ConfigManager::TRANSFER_RANDOMIZE, false) : ConfigManager::setData(ConfigManager::TRANSFER_RANDOMIZE, true);
                                    $sender->sendMessage($prefix.TextFormat::GRAY." Transfer Randomize mode is ".(ConfigManager::getData(ConfigManager::TRANSFER_RANDOMIZE) ? TextFormat::GREEN."enable" : TextFormat::RED."disable"));
                                    break;
                                default: $sender->sendMessage(TextFormat::RED."/".$namecmd.TextFormat::RESET." transfermode (toggle/randomize) - Use to on/off transfer mode.");
                            }
                        }else{
                            $sender->sendMessage(TextFormat::RED."/".$namecmd.TextFormat::RESET." transfermode (toggle/randomize) - Use to on/off transfer mode.");
                        }
                        break;
                    case "captcha":
                        if(isset($args[1])){
                            switch($args[1]){
                                case "toggle":
                                    $data = ConfigManager::getData(ConfigManager::CAPTCHA_ENABLE) === true ? ConfigManager::setData(ConfigManager::CAPTCHA_ENABLE, false) : ConfigManager::setData(ConfigManager::CAPTCHA_ENABLE, true);
                                    $sender->sendMessage($prefix.TextFormat::GRAY." Captcha is ".(ConfigManager::getData(ConfigManager::CAPTCHA_ENABLE) ? TextFormat::GREEN."enable" : TextFormat::RED."disable"));
                                    break;
                                case "message":
                                    $data = ConfigManager::getData(ConfigManager::CAPTCHA_MESSAGE) === true ? ConfigManager::setData(ConfigManager::CAPTCHA_MESSAGE, false) : ConfigManager::setData(ConfigManager::CAPTCHA_MESSAGE, true);
                                    $sender->sendMessage($prefix.TextFormat::GRAY." Message Captcha is ".(ConfigManager::getData(ConfigManager::CAPTCHA_MESSAGE) ? TextFormat::GREEN."enable" : TextFormat::RED."disable"));
                                    break;
                                case "tip":
                                    $data = ConfigManager::getData(ConfigManager::CAPTCHA_TIP) === true ? ConfigManager::setData(ConfigManager::CAPTCHA_TIP, false) : ConfigManager::setData(ConfigManager::CAPTCHA_TIP, true);
                                    $sender->sendMessage($prefix.TextFormat::GRAY." Tip Captcha is ".(ConfigManager::getData(ConfigManager::CAPTCHA_TIP) ? TextFormat::GREEN."enable" : TextFormat::RED."disable"));
                                    break;
                                case "title":
                                    $data = ConfigManager::getData(ConfigManager::CAPTCHA_TITLE) === true ? ConfigManager::setData(ConfigManager::CAPTCHA_TITLE, false) : ConfigManager::setData(ConfigManager::CAPTCHA_TITLE, true);
                                    $sender->sendMessage($prefix.TextFormat::GRAY." Title Captcha is ".(ConfigManager::getData(ConfigManager::CAPTCHA_TITLE) ? TextFormat::GREEN."enable" : TextFormat::RED."disable"));
                                    break;
                                case "randomize":
                                    $data = ConfigManager::getData(ConfigManager::CAPTCHA_RANDOMIZE) === true ? ConfigManager::setData(ConfigManager::CAPTCHA_RANDOMIZE, false) : ConfigManager::setData(ConfigManager::CAPTCHA_RANDOMIZE, true);
                                    $sender->sendMessage($prefix.TextFormat::GRAY." Randomize Mode is ".(ConfigManager::getData(ConfigManager::CAPTCHA_RANDOMIZE) ? TextFormat::GREEN."enable" : TextFormat::RED."disable"));
                                    break;
                                default: $sender->sendMessage(TextFormat::RED."/".$namecmd.TextFormat::RESET." captcha (toggle/message/tip/title/randomize/length) - Use to on/off and set length code for captcha.");
                            }
                        }else{
                            $sender->sendMessage(TextFormat::RED."/".$namecmd.TextFormat::RESET." captcha (toggle/message/tip/title/randomize/length) - Use to on/off and set length code for captcha.");
                        }
                        break;
                    case "bypass":
                        $data = ConfigManager::getData(ConfigManager::PERMISSION_BYPASS_ENABLE) === true ? ConfigManager::setData(ConfigManager::PERMISSION_BYPASS_ENABLE, false) : ConfigManager::setData(ConfigManager::PERMISSION_BYPASS_ENABLE, true);
                        $sender->sendMessage($prefix.TextFormat::GRAY." Bypass mode is ".(ConfigManager::getData(ConfigManager::PERMISSION_BYPASS_ENABLE) ? TextFormat::GREEN."enable" : TextFormat::RED."disable"));
                        break;
                    default: 
                }
            }else{
                $sender->sendMessage(TextFormat::RED."----- ReallyCheat -----");
                $sender->sendMessage(TextFormat::RED."/".$namecmd.TextFormat::RESET." about".TextFormat::GRAY." - Show infomation the plugin.");
                $sender->sendMessage(TextFormat::RED."/".$namecmd.TextFormat::RESET." notify (toggle/admin)".TextFormat::GRAY." - Use to on/off notify.");
                $sender->sendMessage(TextFormat::RED."/".$namecmd.TextFormat::RESET." process (auto)".TextFormat::GRAY." - Use to on/off process.");
                $sender->sendMessage(TextFormat::RED."/".$namecmd.TextFormat::RESET." xray".TextFormat::GRAY." - Use to on/off check xray.");
                $sender->sendMessage(TextFormat::RED."/".$namecmd.TextFormat::RESET." banmode (toggle/randomize)".TextFormat::GRAY." - Use to on/off ban mode.");
                $sender->sendMessage(TextFormat::RED."/".$namecmd.TextFormat::RESET." transfermode (toggle/randomize)".TextFormat::GRAY." - Use to on/off transfer mode.");
                $sender->sendMessage(TextFormat::RED."/".$namecmd.TextFormat::RESET." captcha (toggle/message/tip/title/randomize)".TextFormat::GRAY." - Use to on/off mode for captcha.");
                $sender->sendMessage(TextFormat::RED."/".$namecmd.TextFormat::RESET." bypass".TextFormat::GRAY." - Use to on/off for bypass mode.");
                $sender->sendMessage(TextFormat::RED."----------------------");
                return true;
            }
        }
        return false;
    }
    
}