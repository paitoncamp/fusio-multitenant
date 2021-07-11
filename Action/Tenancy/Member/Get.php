<?php

namespace App\Action\Tenancy\Member;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Http\Exception as StatusCode;

/**
 * GetAll
 *
 * @author  wira m.s <Senasana.wira@gmail.com>
 */
class Get extends ActionAbstract
{

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        $tenantId = $request->getHeader('tenantId');
        $connection = $this->connector->getConnection('System');

        $sql = "SELECT fusio_user.* ,
				  (SELECT VALUE FROM fusio_user_attribute WHERE NAME='first_name' AND user_id=fusio_user.id) AS first_name,
				  (SELECT VALUE FROM fusio_user_attribute WHERE NAME='last_name' AND user_id=fusio_user.id) AS last_name,
				  (SELECT VALUE FROM fusio_user_attribute WHERE NAME='tenant_role' AND user_id=fusio_user.id) AS tenant_role
				FROM fusio_user INNER JOIN 
				(
					SELECT USER_id FROM fusio_user_attribute WHERE VALUE=:tenant_id AND NAME='tenant_uid'
				) members_id ON fusio_user.id=members_id.user_id
				INNER JOIN 
				(
					SELECT USER_id FROM fusio_user_attribute WHERE VALUE='member' AND NAME='tenant_role'
				) members_role ON members_id.user_id = members_role.user_id
				WHERE members_id.user_id=:member_id and status<>0 
                ORDER BY fusio_user.id ";

        $member = $connection->fetchAssoc($sql, [
            'member_id' => $request->get('member_id'),
			'tenant_id' => $tenantId
        ]);
		if (empty($member)) {
            throw new StatusCode\NotFoundException('Member is not available');
        }
        return $this->response->build(200, [], $member);
    }
}
