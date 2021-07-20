<?php
namespace App\Action\Tenancy\Apps;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Http\Exception as StatusCode;

/**
 * OwnerAppsInstalled
 *
 * @author  Wira M Sukoco <senasana.wira@gmail.com>
 */
class OwnerAppsInstalled extends ActionAbstract
{


    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        $tenantId = $request->getHeader('tenantId');
		$ownerId = $context->getUser()->getId();
        $connection = $this->connector->getConnection('System');
		//check whether current tenantId is exists otherwise reject this request 
		$isTenantExists   = $connection->fetchColumn("SELECT COUNT(*)
				FROM fusio_user INNER JOIN 
				(
					SELECT USER_id FROM fusio_user_attribute WHERE VALUE=:tenant_id AND NAME='tenant_uid'
				) members_id ON fusio_user.id=members_id.user_id
				INNER JOIN 
				(
					SELECT USER_id FROM fusio_user_attribute WHERE VALUE='owner' AND NAME='tenant_role'
				) members_role ON members_id.user_id = members_role.user_id 
				WHERE fusio_user.id=:current_user_id",
				[
				"current_user_id"=>$ownerId,
				"tenant_id"=>$tenantId
				]);
		if($isTenantExists==0){
			throw new StatusCode\NotFoundException('TenantId is not valid ');
		}
		
		$sql="SELECT fusio_scope.id, fusio_scope.name, fusio_scope.description,
				EXISTS(select 1 from fusio_user_scope where scope_id=fusio_scope.id and user_id=:member_id) as installed
			  FROM fusio_scope inner join fusio_user_scope on fusio_scope.id=fusio_user_scope.scope_id 
			  WHERE fusio_scope.status=1 and fusio_scope.category_id=1 and fusio_scope.name like 'app-%' and fusio_user_scope.user_id=:owner_id
			  ORDER BY fusio_scope.id ";
			  
		$sql = $connection->getDatabasePlatform()->modifyLimitQuery($sql, 16);

        $count   = $connection->fetchColumn("SELECT COUNT(*)
				FROM fusio_scope inner join fusio_user_scope on fusio_scope.id=fusio_user_scope.scope_id 
			  WHERE fusio_scope.status=1 and fusio_scope.category_id=1 and fusio_scope.name like 'app-%' and fusio_user_scope.user_id=:owner_id
			  ",
			  ["owner_id"=>$ownerId]);
				
        $entries = $connection->fetchAll($sql,[
				"member_id"=>$request->get("member_id"),
				"owner_id"=>$ownerId]);

		return $this->response->build(200, [], [
            'totalResults' => $count,
            'entry' => $entries
			//,'user' => $context->getUser()->getId()
        ]);
    }
}
