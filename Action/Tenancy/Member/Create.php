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
        $body = $request->getPayload();

        assert($body instanceof Member_Create);

        $this->tenantMemberService->create($body,$context);

        return [
            'success' => true,
            'message' => 'Tenant Member successful created',
        ];
    }
}
