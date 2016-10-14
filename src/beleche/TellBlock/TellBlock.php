<?php

namespace beleche\TellBlock;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerCommandProcessEvent;
use pocketmine\Player;
/*
-------------------------------------------------------------------------------------------------------------
-------------------------------------------------------------------------------------------------------------
------------------------------------		████	 ███	████		-------------------------------------
------------------------------------		█	█	█	█	█	█		-------------------------------------
------------------------------------		████	█	█	████		-------------------------------------
------------------------------------		█		█	█	█  █		-------------------------------------
------------------------------------		█		 ███	█	█		-------------------------------------
-------------------------------------------------------------------------------------------------------------
--------█		 ███	█	█	████	 ███	████	█████	█		█████	 ████	█	█	█████--------
--------█		█	█	█	█	█	█	█	█	█	█	█		█		█		█		█	█	█------------
--------█		█████	█	█	████	█	█	████	████	█		████	█		█████	████---------
--------█		█	█	█	█	█  █	█	█	█	█	█		█		█		█		█	█	█------------
--------█████	█	█	█████	█	█	 ███	████	█████	█████	█████	 ████	█	█	█████--------
-------------------------------------------------------------------------------------------------------------
-------------------------------------------------------------------------------------------------------------
*/

class zSpllef extends PluginBase implements Listener{
	
	private $config;
	
		
	public $logger = "§3[TellBlock]§6 ";
	
	private static $instance = null;
	
    public function onEnable(){
    	@mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->reloadConfig();
        $this->saveResource("config.yml", false);
		
        $this->config = new Config($this->getDataFolder() . "config.yml");

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
		//$this->getServer()->getScheduler()->scheduleRepeatingTask(new Tasks($this), 20);
		$this->logOnConsole("Iniciado com sucesso");
		
    }
    
	public function logOnConsole($message){
		$logger = Server::getInstance()->getLogger();
		$logger->info("§3[TellBlock]§6 " . $message);
	}
    public function onDisable(){
		$this->saveStatus();
        $this->logOnConsole(TextFormat::RED." Desabilitado !");
    }
	public static function getInstance(){
		return self::$instance;
	}
	public function onLoad(){
		self::$instance = $this;
		
	}
	public function saveStatus(){
		
	}
	public function onPlayerCommand(PlayerCommandPreprocessEvent $event){
		$message = $event->getMessage();
		$command = substr($message, 1);

		$args = explode(" ", $command);
		$sender = $event->getPlayer();	
		$cmd = $args[0];
		$nome = $sender->getName();
		

		switch($cmd)
		{
			case "tell":
				break;
			
		}
	}
	public function onCommand(CommandSender $sender,Command $command,$label,array $args){
		
		switch($command)
		{
			case "tb":
			if(isset($args[0])){
				switch($args[0]){
					case "tell":
						
						return true;
				}
				return true;
			}else{
				
			}
		}
	}
}