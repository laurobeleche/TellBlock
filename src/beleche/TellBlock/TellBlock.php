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
use pocketmine\event\player\PlayerCommandProcessEvent;
use pocketmine\Player;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;



/*
------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------
------------------------------------████     ███    ████ ---------------------------------------------
------------------------------------█   █   █   █	█   █---------------------------------------------
------------------------------------████    █   █   ████ ---------------------------------------------
------------------------------------█       █   █   █  █ ---------------------------------------------
------------------------------------█        ███    █   █---------------------------------------------
------------------------------------------------------------------------------------------------------
------█      ███    █   █   ████     ███    ████	█████	█		█████	 ████	█	█	█████-----
------█     █   █   █   █   █   █   █   █   █	█	█		█		█		█		█	█	█---------
------█     █████   █   █   ████    █   █   ████	████	█		████	█		█████	████------
------█     █   █   █   █   █  █    █   █   █	█	█		█		█		█		█	█	█---------
------█████ █   █   █████   █   █    ███    ████	█████	█████	█████	 ████	█	█	█████-----
------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------
*/

class TellBlock extends PluginBase implements Listener{
	//Nessa variável carrego o arquivo de configuração do plugin, o arquivo config.yml que fica na pasta do plugin
	private $config;
	
	//Essa variável é um facilitador na hora de enviar nossas mensagens com o plugin
	//Ex. [TellBlock] Você não tem permissão para esse comando
	//Para não precisarmos escrever [TellBlock] todas as vezes que decidirmos enviar uma mensagem, usamos essa variável.
	public $logger = "§3[TellBlock]§6 ";
	
	//Essa variável é usada para que outro plugin possa fazer acesso aos dados desse plugin, explicarei mais a frente
	private static $instance = null;
	
	//Aqui declaramos no Array que vai ser responsavel por guardar todos os jogadores que bloquearam o tell explicarei o que é array mais para frente
	private $tells = [];
	
	//Essa funcão é chamada na hora de habilitar nosso plugin
    public function onEnable(){
    	@mkdir($this->getDataFolder());//aqui criamos a pasta do plugin caso ela não exista
        //$this->saveDefaultConfig();
        $this->reloadConfig();
        $this->saveResource("config.yml", false);//aqui criamos o nosso arquivo config.yml caso ele não exista. ps o arquivo base config.yml deve estar na pasta resources
		
		//$this->config é referência a variável declarada no início do plugin private $config la na linha 38
		//Aqui estamos dizendo que esta variável vai conter todos os dados do arquivo config.yml
		//Lembrando que, se vc não tiver o use pocketmine\utils\Config; vc não consegue chamar o new Config
		//Dentro do Config temos 1 argumento que é o caminho do arquivo que queremos carregar
		//No caso $this->getDataFolder() . "config.yml" - getDataFolder() retorna o caminho criado la na linha 53
        $this->config = new Config($this->getDataFolder() . "config.yml");

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
		//$this->getServer()->getScheduler()->scheduleRepeatingTask(new Tasks($this), 20);
		$this->logOnConsole("Iniciado com sucesso");//e aqui mostramos no console que o plugin foi devidamente carregado
		
    }
    
	//Essa função é usada para enviar mensagens no console
	//Você pode usar Server::getInstance()->getLogger()->info("sua mensagem");
	//Mas desta forma fica como um atalho.
	public function logOnConsole($message){
		$logger = Server::getInstance()->getLogger();
		$logger->info($this->logger . $message);
	}
	//Quando nosso plugin é desabilitado ou o servidor é desligado, essa função é chamada
	//Nela podemos colocar mensagens de finalização como também salvar os dados que são necessários
	//Se seu plugin precisa salvar dados para que quando o servidor voltar regras continuem valendo
	//É bom ter um save aqui nessa função.
    public function onDisable(){
		$this->saveStatus();
        $this->logOnConsole(TextFormat::RED." Desabilitado !");
    }
	
	/*Como falei anteriormente a variavel $instance serve para fornecer dados a outros plugins
	Para que isso seja possível, nós temos que dizer para essa variável tudo que acontece aqui
	Então usamos a função a seguir.
	*/
	public function onLoad(){
		self::$instance = $this;
		
	}
	/*E aqui temos a função responsável por fornecer os dados desse plugin a outros plugins
	Se vc precisar de algum dado desse plugin bas criar um plugin e colocar nele a linha:
	use beleche\TellBlock\TellBlock;
	depois Chamar o plugin com a seguinte linha.
	
	TellBlock::getInstance();
	
	Em nosso plugin não teremos nenhum dado a fornecer, então por enquanto nada pode ser feito com essa linha.
	Darei mais explicações quando tivermos algum dado a fornecer.
	*/
	public static function getInstance(){
		return self::$instance;
	}
	
	/*La na função onDisable() eu chamo a função saveStatus()
	Faço isso em todos os plugins que desenvolvo, assim fica padrão, então se eu tiver algum dado a gravar para ser carregado nada
	próxima vez que esse plugin for iniciado, vou colocar os códigos de save aqui.	
	*/
	public function saveStatus(){
		
	}

	/*
	E aqui finalmente começamos a fazer nosso plugin trabalhar
	PlayerCommandPreprocessEvent é chamado sempre que algum jogador executa algum comando ex. /help, /tell, /pay, /tp, /gamemode
	Então como em nosso plugin queremos saber sempre que um jogador executar o /tell, vamos verificar qual comando o jogador executou
	*/
	public function onPlayerCommand(PlayerCommandPreprocessEvent $event){//$event contém todos os dados reponsáveis pelo comando do jogador
		$message = $event->getMessage();//aqui temos a mensagem comleta escrita pelo jogador ex. /tell laurobeleche ola me da staff?
		$command = substr($message, 1);//aqui removemos a "/" do inicio da frasa com o função substr($message, 1)
		// após isso nosso comando está assim "tell laurobeleche ola me da staff?"

		$args = explode(" ", $command);//aqui usamos o explode(" ", $command) para separar as palavras criado a array $args
		/*
		Nossa a array está assim:
		$args[0] => tell
		$args[1] => laurobeleche
		$args[2] => ola
		$args[3] => me
		$args[4] => da
		$args[5] => staff?
		*/
		$sender = $event->getPlayer();//aqui descobrimos quem enviou o comando
		$cmd = $args[0];//aqui definimos que a variável $cmd é igual a variável $args[0] ou seja "tell"
		$nome = $sender->getName();//aqui nós pegamos o nome do jogador que enviou o comando
		
		/*
		Para quem não conhece a função switch e como ela funciona sugiro que leiam esse link
		http://php.net/manual/pt_BR/control-structures.switch.php
		*/
		switch($cmd)//Veja que a nossa condição do switch é a variável $cmd
		{
			case "tell"://aqui dizemos: Caso $cmd seja igual a "tell" execute os comando abaixo
			/*
			Aqui verificamos se existe a variável $args[1], pq se la em cima o comando foi só /tell, a variável $args[1] não será criada no explode
			Então usamos uma condicional IF = SE --- se a variável $args[1] foi definida, execute os camando de dentro dos colchetes
			*/
				if(isset($args[1])){
					if(isset($this->tells[strtolower($args[1])])){//aqui verificamos se o nome usado no tell está listado como bloqueado
						//Esses comandos só serão executados se a condição for verdadeira
						$event->setCancelled();//Aqui nos CANCELAMOS o envio do comando tell
						$sender->sendMessage($this->logger . "O jogador que você tentou contactar decidiu não receber mensagens privadas.");//E aqui notificamos o jogador que o destinatário não receberá a mensagem.
					}
					
				}
				break;// com o break finalizamos os comandos do caso "tell"
			
		}
	}
	public function onCommand(CommandSender $sender,Command $command,$label,array $args){
		/*
		Nesse função temos os parametros enviados para nós pelo servidor
		$sender - Contem os dados do jogador que enviou o comando
		$command - Contem os dados do comando executados
		$label - Não iremo utiliza esse
		$args - Uma array com todos os argumenti usados no comando
		Argumentos são as palavras usadas depois do comando exemplo: /tell laurobeleche Posso ser mod?
		Tell é o $command, laurobeleche é o $args[0], o resto é a mensagem.
		no nosso caso o comando que queremos identificar é o /tb, e o argumento $args[0] que no caso deve ser tell, se qualquer outra
		palavra for enviada no $args[0] a palavra será ignorada e retornaremos um erro ao jogador.
		*/
		switch($command)
		{
			case "tb":
				//Aqui verificamos se o remetente do comando é um jogador, para evitar que o comando seja executado no console
				if($sender instanceof Player){
					if(isset($args[0])){
						switch($args[0]){
							case "tell":
								/*
								Nessa condicional verificamos se o nome do jogador ja está na lista de bloqueados,
								se a condição for verdadeira ele remove da lista de bloqueados, se for falsa ele inclue na 
								lista de bloqueados, é uma função bem simples, mas fundamental para o funcionamento do nosso plugin
								*/
								if(isset($this->tells[strtolower($sender->getName())])){
									unset($this->tells[strtolower($sender->getName())]);
									$sender->sendMessage($this->logger . "Agora você receberá mensagens privadas novamente.");
								}else{
									$this->tells[strtolower($sender->getName())] = 1;
									$sender->sendMessage($this->logger . "Você acaba de bloquear todas as mensagens privadas.");
									$sender->sendMessage($this->logger . "Para desbloquear use /tb tell.");
									
								}
								return true;
							DEFAULT:
								return false;
						}
						return true;
					}else{
						return false;
					}
				}else{
					$sender->sendMessage($this->logger . "Esse comando funcionará apenas no jogo.");
					return true;
				}
				
		}
	}
}