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
namespace hachkingtohach1\reallycheat\components\config;

use hachkingtohach1\reallycheat\components\registry\ComponentWithName;

abstract class ConfigPaths implements ComponentWithName{

      public const PREFIX = "reallycheat.prefix";

      public const ANTIBOT_MESSAGE = "reallycheat.antibot.message";

      public const NETWORK_LIMIT = "reallycheat.network.limit";
      public const NETWORK_MESSAGE = "reallycheat.network.message";

      public const PING_NORMAL = "reallycheat.ping.normal";
      public const PING_LAGGING = "reallycheat.ping.lagging";

      public const PROXY_ENABLE = "reallycheat.proxy.enable";
      public const PROXY_IP = "reallycheat.proxy.ip";
      public const PROXY_PORT = "reallycheat.proxy.port";

      public const VERSION = "reallycheat.version";
      
      public const PROCESS_AUTO = "reallycheat.process.auto";

      public const XRAY_ENABLE = "reallycheat.xray.enable";
      public const XRAY_DISTANCE = "reallycheat.xray.distance";

      public const ALERTS_MESSAGE = "reallycheat.alerts.message";
      public const ALERTS_ENABLE = "reallycheat.alerts.enable";
      public const ALERTS_PERMISSION = "reallycheat.alerts.permission";
      public const ALERTS_ADMIN = "reallycheat.alerts.admin";

      public const BAN_COMMANDS = "reallycheat.ban.commands";
      public const BAN_MESSAGE = "reallycheat.ban.message";
      public const BAN_ENABLE = "reallycheat.ban.enable";
      public const BAN_RANDOMIZE = "reallycheat.ban.randomize";
      public const BAN_RECENT_LOGS_MESSAGE = "reallycheat.ban.recentlogs.message";  

      public const TRANSFER_IP = "reallycheat.transfer.ip";
      public const TRANSFER_ENABLE = "reallycheat.transfer.enable";
      public const TRANSFER_USECOMMAND_ENABLE = "reallycheat.transfer.usecommand.enable";
      public const TRANSFER_USECOMMAND_COMMANDS = "reallycheat.transfer.usecommand.commands";
      public const TRANSFER_MESSAGE = "reallycheat.transfer.message";
      public const TRANSFER_RANDOMIZE = "reallycheat.transfer.randomize";
      public const TRANSFER_RECENT_LOGS_MESSAGE = "reallycheat.transfer.recentlogs.message"; 

      public const PERMISSION_BYPASS_ENABLE = "reallycheat.permissions.enable";
      public const PERMISSION_BYPASS_PERMISSION = "reallycheat.permissions.permission";

      public const DISCORD_ENABLE = "reallycheat.discord.enable";
      public const DISCORD_WEBHOOK = "reallycheat.discord.webhook";
      public const DISCORD_PLAYER_JOIN_ENABLE = "reallycheat.discord.player.joined.enable";
      public const DISCORD_PLAYER_JOIN_TEXT = "reallycheat.discord.player.joined.text";
      public const DISCORD_PLAYER_LEFT_ENABLE = "reallycheat.discord.player.left.enable";
      public const DISCORD_PLAYER_LEFT_TEXT = "reallycheat.discord.player.left.text";
      public const DISCORD_PLAYER_KICK_ENABLE = "reallycheat.discord.player.kick.enable";
      public const DISCORD_PLAYER_KICK_TEXT = "reallycheat.discord.player.kick.text";
      public const DISCORD_PLAYER_BAN_ENABLE = "reallycheat.discord.player.ban.enable";
      public const DISCORD_PLAYER_BAN_TEXT = "reallycheat.discord.player.ban.text";
      public const DISCORD_SERVER_LAGGING_ENABLE = "reallycheat.discord.server.lagging.enable";
      public const DISCORD_SERVER_LAGGING_TEXT = "reallycheat.discord.server.lagging.text";

      public const CAPTCHA_ENABLE = "reallycheat.captcha.enable";
      public const CAPTCHA_TEXT = "reallycheat.captcha.text";
      public const CAPTCHA_MESSAGE = "reallycheat.captcha.message";
      public const CAPTCHA_TIP = "reallycheat.captcha.tip";
      public const CAPTCHA_TITLE = "reallycheat.captcha.title";
      public const CAPTCHA_RANDOMIZE = "reallycheat.captcha.randomize";
      public const CAPTCHA_CODE_LENGTH = "reallycheat.captcha.code.length";

      public const CHAT_SPAM_TEXT = "reallycheat.chat.spam.text";    
      public const CHAT_SPAM_DELAY = "reallycheat.chat.spam.delay";
      public const CHAT_COMMAND_SPAM_TEXT = "reallycheat.chat.command.text";
      public const CHAT_COMMAND_SPAM_DELAY = "reallycheat.chat.command.delay";
      public const CHAT_COMMAND_SPAM_COMMANDS = "reallycheat.chat.command.commands";
      public const CHAT_REPEAT_TEXT = "reallycheat.chat.repeat.text";

      public const CHECK = "reallycheat.check";

      public function getComponentName() :string{
        return "ReallyCheat_ConfigPaths";
      }

}