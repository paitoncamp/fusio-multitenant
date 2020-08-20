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
	
	
	
	public function __construct(Connector $connector, DispatcherInterface $dispatcher)
    {
        $this->connector = $connector;
        $this->dispatcher = $dispatcher;
		//$this->connection = $this->connector->getConnection("System");  //set connection to system default
		$this->intSysClient = $this->connector->getConnection('Http-Connection');
    }
	
	//private function intSysRequest($reqMethod, $reqPath, )
	
	public function setupTenantConnection($tenantId){
		if ($tenantId) {
			
			$this->connection = $this->connector->getConnection('gl-'.$tenantId);
        } else {
			throw new StatusCode\InternalServerErrorException('No tenantId defined on request header', null);
		}
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
	
	public function createAppConnection():int 
	{
		
	}

	
}