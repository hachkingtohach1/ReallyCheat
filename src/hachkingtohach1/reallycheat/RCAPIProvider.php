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
namespace hachkingtohach1\reallycheat;

use hachkingtohach1\reallycheat\components\IRCAPI;
use hachkingtohach1\reallycheat\listener\PlayerListener;
use hachkingtohach1\reallycheat\listener\ServerListener;
use hachkingtohach1\reallycheat\listener\CommandsListener;
use hachkingtohach1\reallycheat\task\ServerTickTask;
use hachkingtohach1\reallycheat\task\CaptchaTask;
use hachkingtohach1\reallycheat\task\NetworkTickTask;
use hachkingtohach1\reallycheat\network\ProxyUDPSocket;
use hachkingtohach1\reallycheat\utils\InternetAddress;
use hachkingtohach1\reallycheat\config\ConfigManager;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class RCAPIProvider extends PluginBase implements IRCAPI{

	private static $instance = null;
    private ProxyUDPSocket $proxyUDPSocket;

	public const VERSION_PLUGIN = "PRM-2.1.5";

	public function onLoad() :void{
        self::$instance = $this;
	}
	
	public static function getInstance(): self{
        return self::$instance;
    }

    public function onEnable() :void{			
		$this->proxyUDPSocket = new ProxyUDPSocket();
		if(ConfigManager::getData(ConfigManager::PROXY_ENABLE)){
			$ip = ConfigManager::getData(ConfigManager::PROXY_IP);
			$port = ConfigManager::getData(ConfigManager::PROXY_PORT);
			try{
				$this->proxyUDPSocket->bind(new InternetAddress($ip, $port));
			}catch (\Exception $exception){
				$this->getLogger()->info("{$exception->getMessage()}, stopping proxy...");
				return;
			}
		}
        $this->saveDefaultConfig();
		$this->saveResource("hash.txt");	
		$this->getScheduler()->scheduleRepeatingTask(new ServerTickTask($this), 20);
		$this->getScheduler()->scheduleRepeatingTask(new CaptchaTask($this), 20);
		$this->getScheduler()->scheduleRepeatingTask(new NetworkTickTask($this), 100);
		$this->getServer()->getPluginManager()->registerEvents(new PlayerListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new ServerListener(), $this);		
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) :bool{
		(new CommandsListener())->onCommand($sender, $command, $label, $args);
		return false;
	}
	
}