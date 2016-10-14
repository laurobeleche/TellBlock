<?php

namespace beleche\TellBlock;

use pocketmine\scheduler\PluginTask;
use pocketmine\Player;

class Tasks extends PluginTask{
    private $plugin;
    

    public function __construct(TellBlock $plugin){
        parent::__construct($plugin);
        $this->plugin = $plugin;
        
    }
	
    public function onRun($currentTick){
		
	}
}
