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
namespace hachkingtohach1\reallycheat\task;

use hachkingtohach1\reallycheat\RCAPIProvider;
use pocketmine\scheduler\Task;

class ServerTickTask extends Task{

	private float $tick;
	private static $instance = null;
	
	public function __construct(RCAPIProvider $plugin){
        $this->plugin = $plugin;
	}
	
	public function onRun() :void{
		self::$instance = $this;
		$this->tick = microtime(true);     
	}

	public static function getInstance(): self{
        return self::$instance;
    }

	public function getTick() :float{
		return $this->tick;
	}

	public function getLastTick() :float{
		return $this->lastTick;
	}

	public function isLagging(float $l) :bool{
        $lsat = $l - $this->tick;
        return $lsat >= 5;
    }

}