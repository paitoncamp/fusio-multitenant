<?php

namespace App\Action\Tenancy\Member;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use Fusio\Impl\Authorization\UserContext;
//use Fusio\Impl\Service\User;
use App\Service\Tenancy\TenantMember;
//use Fusio\Model\Backend\User_Update;
use App\Model\Tenancy\Member_Update;

/**
 * Update
 *
 * @author  wira m.s <Senasana.wira@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Update extends ActionAbstract
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
        $body = $request->getPayload();

        assert($body instanceof Member_Update);

        $this->tenantMemberService->update(
            (int) $request->get('member_id'),
            $body,
            UserContext::newActionContext($context)
        );

        return [
            'success' => true,
            'message' => 'Member successful updated',
        ];
    }
}
