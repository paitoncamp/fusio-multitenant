<?php 
namespace App\Service\Tenancy;

//use App\Schema\GL\Groups as SchemaGroups;
use Doctrine\DBAL\Connection;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\DispatcherInterface;
use Fusio\Engine\Connector;
use PSX\CloudEvents\Builder;
use PSX\Framework\Util\Uuid;
use PSX\Http\Exception as StatusCode;
use PSX\Framework\Config\Config;
//use GuzzleHttp\Client;

class Tenancy{
	/**
	* TO DO :
	* - User Registration
	* - User Tenancy Creation
	* - User Apps Creation
	* - User Connection Creation 
	* - User Apps user administration
	*/
	
	/**
	* @const BaseSysUrl
	*/
	private const BaseSysUrl='http://localhost/fusio-1.9.4';
	
	/**
     * @var multitenant app code
     */
	private $appCode;
	
	/**
     * @var COnfig
     */
    private $config;
	
	/**
     * @var connector
     */
    private $connector;

	/**
     * @var connection
     */
    private $connection;

    /**
     * @var DispatcherInterface
     */
    private $dispatcher;
	
	/**
	*  @var \GuzzleHttp\Client
	*/
	private $intSysClient;
	
	//private $baseSysUrl;
	
	/**
	* @var String
	*/
	private $roboLoginToken;
	
	
	
	public function __construct(Config $config, Connector $connector, DispatcherInterface $dispatcher)
    {
		//$this->appCode = $appCode;
		$this->config = $config;
        $this->connector = $connector;
        $this->dispatcher = $dispatcher;
		//$this->connection = $this->connector->getConnection("System");  //set connection to system default
		$this->intSysClient = $this->connector->getConnection('Http-Connection');
    }
	
	//private function intSysRequest($reqMethod, $reqPath, )
	
	private function getUserTenantId(){
		
	}
	
	public function setupTenantConnection($tenantId){
		if ($tenantId) {
			
			$this->connection = $this->connector->getConnection($this->appCode.'-'.$tenantId);
        } else {
			throw new StatusCode\InternalServerErrorException('No tenantId defined on request header', null);
		}
	}
	
	
	
	
	public function createAppDatabase($app, $tenantId){
		//$Uuid = new Uuid();
		$dbcfg = $this->config->get('psx_connection');
		$config = array(
			'driver'   => $dbcfg['driver'],
			'host'     => $dbcfg['host'],
			'user'     => $dbcfg['user'],
			'password' => $dbcfg['password'],
		);
		/** @var \Doctrine\DBAL\Connection */
		$tmpConnection = \Doctrine\DBAL\DriverManager::getConnection($dbcfg);
		
		$name = $app.'-'.$tenantId;

		// Check if the database already exists
		if (in_array($name, $tmpConnection->getSchemaManager()->listDatabases())) {
			//$this->dbCreatorLogger->log('Fail to create database '.$name. '!, its already exist!');
			return;
		}

		// Create the database
		
		$tmpConnection->getSchemaManager()->createDatabase($name);
		//$this->dbCreatorLogger->log('Database '.$name. ' created!');
		$tmpConnection->close();
	}
	
	public function createAppConnection(){
		//{"name":"Fusio_app_gl","class":"Fusio\\Adapter\\Sql\\Connection\\Sql","config":{"type":"pdo_mysql","host":"localhost","username":"root","database":"fusio_app_gl"}}
		$connectionCfg = array(
			"name"=>"connection_name",
			"class"=>"Fusio\\Adapter\\Sql\\Connection\\Sql",
			"config"=>array("type"=>"pdo_mysql",
							"host"=>"localhost",
							"username"=>"root",
							"password"=>null,
							"database"=>"app_db_name"
							)
		);
		
		$jsonResp = $this->intSysClient->request('POST',self::BaseSysUrl.'/backend/connection',[
						'body'=>json_encode($connectionCfg)
					]);
	}
	
	public function roboLogin():string
	{
		session_start();
		$jsonResp = $this->intSysClient->request('POST',self::BaseSysUrl.'/consumer/login',[
					'body'=>'{
								"username":"robogen",
								"password":"password"
							 }'
					]);
		$data = json_decode((string) $jsonResp->getBody());
		$this->roboLoginToken = $data->token;
		$_SESSION['token']=$data->token;
		return $data->token;
	}
	
	public function roboLogout()
	{
		session_start();
		$this->roboLoginToken = $_SESSION['token'];
		$jsonResp = $this->intSysClient->request('POST',self::BaseSysUrl.'/authorization/revoke',[
					'headers'=>['Authorization'=>"Bearer {$this->roboLoginToken}"]
							 
					]);
		$data = json_decode((string) $jsonResp->getBody());
		return $data;
	}
	
	

	
}