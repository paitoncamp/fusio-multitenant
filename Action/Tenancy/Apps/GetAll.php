<?php
namespace App\Action\Tenancy\Apps;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Http\Exception as StatusCode;

/**
 * GetAll
 *
 * @author  Wira M Sukoco <senasana.wira@gmail.com>
 */
class GetAll extends ActionAbstract
{


    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        $tenantId = $request->getHeader('tenantId');
		
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
				"current_user_id"=>$context->getUser()->getId(),
				"tenant_id"=>$tenantId
				]);
		if($isTenantExists==0){
			throw new StatusCode\NotFoundException('TenantId is not valid ');
		}
		
		$sql="SELECT id, name, description,
				EXISTS(select 1 from fusio_user_scope where scope_id=fusio_scope.id) as installed
			  FROM fusio_scope 
			  WHERE status=1 and category_id=1 and name like 'app-%' 
			  ORDER BY id ";
			  
		$sql = $connection->getDatabasePlatform()->modifyLimitQuery($sql, 16);

        $count   = $connection->fetchColumn("SELECT COUNT(*)
				FROM fusio_scope 
			  WHERE status=1 and category_id=1 and name like 'app-%' ");
				
        $entries = $connection->fetchAll($sql);

		return $this->response->build(200, [], [
            'totalResults' => $count,
            'entry' => $entries
			//,'user' => $context->getUser()->getId()
        ]);
    }
}
