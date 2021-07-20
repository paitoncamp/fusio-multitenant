<?php
namespace App\Action\Tenancy\Member;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use Fusio\Impl\Authorization\UserContext;
use App\Service\Tenancy\TenantMember;
use App\Model\Tenancy\Member_Create;

/**
 * Create
 *
 * @author  wira ms <senasana.wira@gmail.com>
 */
class Create extends ActionAbstract
{
    /**
     * @var TenantMember
     */
    private $userService;

    public function __construct(TenantMember $tenantMemberService)
    {
        $this->tenantMemberService = $tenantMemberService;
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
		$tenantId = $request->getHeader('tenantId');
		$ownerId = $context->getUser()->getId();
		$connection = $this->connector->getConnection('System');

        $sql = "SELECT fusio_user.* ,
				  (SELECT VALUE FROM fusio_user_attribute WHERE NAME='first_name' AND user_id=fusio_user.id) AS first_name,
				  (SELECT VALUE FROM fusio_user_attribute WHERE NAME='last_name' AND user_id=fusio_user.id) AS last_name,
				  (SELECT VALUE FROM fusio_user_attribute WHERE NAME='tenant_role' AND user_id=fusio_user.id) AS tenant_role
				FROM fusio_user INNER JOIN 
				(
					SELECT USER_id FROM fusio_user_attribute WHERE VALUE=:tenant_id AND NAME='tenant_uid'
				) owner_id ON fusio_user.id=owner_id.user_id
				INNER JOIN 
				(
					SELECT USER_id FROM fusio_user_attribute WHERE VALUE='owner' AND NAME='tenant_role'
				) owner_role ON owner_id.user_id = owner_role.user_id
				WHERE owner_id.user_id=:owner_id and status<>0 
                ORDER BY fusio_user.id ";

        $owner = $connection->fetchAssoc($sql, [
            'owner_id' => $ownerId,
			'tenant_id' => $tenantId
        ]);
		//if current ID is owner of current tenant, continue to update its data otherwise reject
		if (empty($owner)) {
            throw new StatusCode\NotFoundException('Current User is not tenant owner');
        }									  
        $body = $request->getPayload();

        assert($body instanceof Member_Create);

        $this->tenantMemberService->create($body,$context);

        return [
            'success' => true,
            'message' => 'Tenant Member successful created',
        ];
    }
}
