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
	
	//Nessa variável carrego o arquivo de configuração do plugin, o arquivo config.yml que fica na pasta do plugin
	private $config;
	
	//Essa variável é um facilitador na hora de enviar nossas mensagens com o plugin
	//Ex. [TellBlock] Você não tem permissão para esse comando
	//Para não precisarmos escrever [TellBlock] todas as vezes que decidirmos enviar uma mensagem, usamos essa variável.
	public $logger = "§3[TellBlock]§6 ";
	
	private static $instance = null;
	
    public function onEnable(){
    	@mkdir($this->getDataFolder());//aqui criamos a pasta do plugin caso ela não exista
        $this->saveDefaultConfig();
        $this->reloadConfig();
        $this->saveResource("config.yml", false);//aqui criamos o nosso arquivo config.yml caso ele não exista. ps o arquivo base config.yml deve estar na pasta resources
		
		//$this->config é referência a variável declarada no início do plugin private $config la na linha 38
		//Aqui estamos dizendo que esta variável vai conter todos os dados do arquivo config.yml
		//Lembrando que, se vc não tiver o use pocketmine\utils\Config; vc não consegue chamar o new Config
		//Dentro do Config temos 1 argumento que é o caminho do arquivo que queremos carregar
		//No caso $this->getDataFolder() . "config.yml" - getDataFolder() retorna o caminho criado la na linha 48
        $this->config = new Config($this->getDataFolder() . "config.yml");

        //$this->getServer()->getPluginManager()->registerEvents($this, $this);
		//$this->getServer()->getScheduler()->scheduleRepeatingTask(new Tasks($this), 20);
		$this->logOnConsole("Iniciado com sucesso");//e aqui mostramos no console que o plugin foi devidamente carregado
		
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
				if(isset($args[1])){
					
				}
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