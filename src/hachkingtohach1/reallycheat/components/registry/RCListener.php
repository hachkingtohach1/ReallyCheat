<?php

namespace hachkingtohach1\reallycheat\components\registry;

use pocketmine\event\Listener;

abstract class RCListener implements Listener, ComponentWithName{

	public function getComponentName() :string{
		return "ReallyCheat_Listener";
	}

}