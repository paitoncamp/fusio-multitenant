<?php
namespace App\Action\Tenancy\Member;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use Fusio\Impl\Authorization\UserContext;
use Fusio\Impl\Service\User;


class Delete extends ActionAbstract
{
    /**
     * @var User
     */
    private $userService;

    public function __construct(User $userService)
    {
        $this->userService = $userService;
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
		//prevent tenant owner deleting another tenant member without need to know its Id (random ID deletion)
		$tenantId = $request->getHeader('tenantId');
		$tenantId='84f5f82f-a199-4971-9eee-e77eff9643ad';
         $connection = $this->connector->getConnection('System');

        $sql = "SELECT fusio_user.* ,
				  (SELECT VALUE FROM fusio_user_attribute WHERE NAME='first_name' AND user_id=fusio_user.id) AS first_name,
				  (SELECT VALUE FROM fusio_user_attribute WHERE NAME='last_name' AND user_id=fusio_user.id) AS last_name,
				  (SELECT VALUE FROM fusio_user_attribute WHERE NAME='tenant_role' AND user_id=fusio_user.id) AS tenant_role
				FROM fusio_user INNER JOIN 
				(
					SELECT USER_id FROM fusio_user_attribute WHERE VALUE='$tenantId' AND NAME='tenant_uid'
				) members_id ON fusio_user.id=members_id.user_id
				INNER JOIN 
				(
					SELECT USER_id FROM fusio_user_attribute WHERE VALUE='member' AND NAME='tenant_role'
				) members_role ON members_id.user_id = members_role.user_id
				WHERE members_id.user_id=:member_id
                ORDER BY fusio_user.id ";

        $member = $connection->fetchAssoc($sql, [
            'member_id' => $request->get('member_id')
        ]);
		if (empty($member)) {
            throw new StatusCode\NotFoundException('Member ID is not in current tenant');
        }
        $this->userService->delete(
            (int) $request->get('member_id'),
            UserContext::newActionContext($context)
        );

        return [
            'success' => true,
            'message' => 'Member successful deleted',
        ];
    }
}
