<?php
namespace App\Service\Tenancy; 

use Fusio\Impl\Service;
use Fusio\Impl\Table;
use App\Model\Tenancy\Apps_Update;
use Fusio\Model\Backend\User_Update;
use Fusio\Model\Backend\User_Attributes;
use PSX\Http\Exception as StatusCode;
use PSX\Sql\Condition;
use PSX\Record\Transformer;
use Doctrine\DBAL\Connection;
use Fusio\Impl\Authorization\UserContext;
use Fusio\Engine\ContextInterface;

/**
 * TenantApps
 * @author  Wira m.s <senasana.wira@gmail.com>
 */
class TenantApps
{
	/**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;
	
	/**
     * @var \Fusio\Impl\Service\User
     */
    private $userService;
    
	/**
     * @var Table\User
     */
    private $tenantTable;
	
	/**
     * @var \Fusio\Impl\Table\User\Scope
     */
    private $tenantScopeTable;
	
	/**
     * @var Table\User\Attribute
     */
    private $tenantAttrTable;

    /**
	 * @param \Doctrine\DBAL\Connection $connection
 	 * @param \Fusio\Impl\Service\User $userService
	 * @param \Fusio\Impl\Table\User $tenantTable
	 * @param \Fusio\Impl\Table\User\Scope $userScopeTable
	 * @param \Fusio\Impl\Table\User\Attribute $tenantAttrTable
     */
    public function __construct(Connection $connection, Service\User $userService, Table\User $tenantTable,Table\User\Scope $tenantScopeTable, Table\User\Attribute $tenantAttrTable)
    {
		$this->connection = $connection;
        $this->userService    = $userService;
		$this->tenantTable      = $tenantTable;
		$this->tenantScopeTable = $tenantScopeTable;
		$this->tenantAttrTable = $tenantAttrTable;
    }

    /**
	* Install/Uninstall apps mean, add specific scope to current tenant Owner
	*/	
	public function install(int $ownerId, string $app, UserContext $context,$createDb=true){
		//-- TO DO
		$existing = $this->tenantTable->get($ownerId);
        if (empty($existing)) {
            throw new StatusCode\NotFoundException('Could not find tenant owner id');
        }
		
		
		$allUserAttrCond = new Condition();
        $allUserAttrCond->equals('user_id', $ownerId);	
		
		$allUserAttr =  $this->tenantAttrTable->getOneBy($allUserAttrCond);
		
		$tenantUidAttrCond = new Condition();
        $tenantUidAttrCond->equals('user_id', $ownerId);	
		$tenantUidAttrCond->equals('name','tenant_uid');
		$tenantUidAttr =  $this->tenantAttrTable->getOneBy($tenantUidAttrCond);
		$tenant_uid = $tenantUidAttr['value'];

		$transformer = new Transformer();
		$existingUserAttr = new User_Attributes();
		$existingUserAttr->setProperties($transformer->toArray($allUserAttr));

		
		$currentScopes = array_values($this->getAvailableScopes($ownerId));
		if(in_array($app,$currentScopes)){
			throw new StatusCode\NotFoundException('This App is already installed');
		}
		//add new app_scope into current scope
		$appsScopes = array_merge($currentScopes,array($app));
		

		
        $userUpd = new User_Update();
		$userUpd->setRoleId((int) $existing['role_id']);
		$userUpd->setStatus($existing['status']);
		$userUpd->setName($existing['name']);
		$userUpd->setEmail($existing['email']);
		$userUpd->setAttributes($existingUserAttr);
		$userUpd->setScopes($appsScopes);

        $this->userService->update($ownerId,$userUpd,$context);
		
		//--- to do - create app db
		if($createDb){
			$this->createTenantAppDb($app,$tenant_uid);
		}
	}
	
	/**
	* Install/Uninstall apps mean, add specific scope to current tenant Owner
	*/	
	public function uninstall(int $ownerId, string $app, UserContext $context,$deleteDb=true){
		//-- TO DO
		$existing = $this->tenantTable->get($ownerId);
        if (empty($existing)) {
            throw new StatusCode\NotFoundException('Could not find tenant owner id');
        }
		

		
		$allUserAttrCond = new Condition();
        $allUserAttrCond->equals('user_id', $ownerId);	
		
		$allUserAttr =  $this->tenantAttrTable->getOneBy($allUserAttrCond);
		
		$tenantUidAttrCond = new Condition();
        $tenantUidAttrCond->equals('user_id', $ownerId);	
		$tenantUidAttrCond->equals('name','tenant_uid');
		$tenantUidAttr =  $this->tenantAttrTable->getOneBy($tenantUidAttrCond);
		$tenant_uid = $tenantUidAttr['value'];

		$transformer = new Transformer();
		$existingUserAttr = new User_Attributes();
		$existingUserAttr->setProperties($transformer->toArray($allUserAttr));

		
		$currentScopes = array_values($this->getAvailableScopes($ownerId));
		if(!in_array($app,$currentScopes)){
			throw new StatusCode\NotFoundException("This App doesn't installed");
		}
		//remove  app_scope from current scope
		$appsScopes = array_diff($currentScopes,array($app));
		
		
        $userUpd = new User_Update();
		$userUpd->setRoleId((int) $existing['role_id']);
		$userUpd->setStatus($existing['status']);
		$userUpd->setName($existing['name']);
		$userUpd->setEmail($existing['email']);
		$userUpd->setAttributes($existingUserAttr);
		$userUpd->setScopes($appsScopes);

        $this->userService->update($ownerId,$userUpd,$context);
		
				
		if($deleteDb){
			$this->deleteTenantAppDb($app,$tenant_uid);
		}
	}
	
	protected function createTenantAppDb($app,$tenant_uid){
		//str_replace('-','\-',)
		$dbname = '`'.$app.'-'.$tenant_uid.'`';
		if(in_array($dbname,$this->connection->getSchemaManager()->listDatabases())){
			throw new StatusCode\InternalServerErrorException("This DB $dbname name is already exists");
		}
		$this->connection->getSchemaManager()->createDatabase($dbname);
	}
	
	protected function deleteTenantAppDb($app,$tenant_uid){
		$dbname = $app.'-'.$tenant_uid;
		if(in_array($dbname,$this->connection->getSchemaManager()->listDatabases())){
			$this->connection->getSchemaManager()->dropDatabase('`'.$dbname.'`');
		} else {
			throw new StatusCode\InternalServerErrorException("This DB $dbname name doesn't exists");
		}
	}
	
	public function getAvailableScopes($userId)
    {
        return Table\Scope::getNames($this->tenantScopeTable->getAvailableScopes($userId));
    }
	
}
