<?php
namespace App\Service\Tenancy;

use Fusio\Impl\Authorization\UserContext;
use Fusio\Engine\ContextInterface;
use Fusio\Impl\Service;
use Fusio\Impl\Table;
use Fusio\Model\Backend\User_Create;
use Fusio\Model\Backend\User_Update;
use Fusio\Model\Backend\User_Attributes;
use App\Model\Tenancy\Member_Create;
use PSX\Http\Exception as StatusCode;
use PSX\Sql\Condition;

/**
 * TenantRegister
 * Extend Register Service capability
 * @author  Wira m.s <senasana.wira@gmail.com>
 */
class TenantMember
{
    /**
     * @var \Fusio\Impl\Service\User
     */
    private $userService;

    /**
     * @var \Fusio\Impl\Service\User\Captcha
     */
    private $captchaService;

    /**
     * @var \Fusio\Impl\Service\User\Token
     */
    private $tokenService;

    /**
     * @var \Fusio\Impl\Service\User\Mailer
     */
    private $mailerService;

    /**
     * @var \Fusio\Impl\Service\Config
     */
    private $configService;
    /**
     * @var Table\Role
     */
    private $roleTable;
	/**
     * @var Table\User
     */
    private $tenantOwnerTable;
	/**
     * @var Table\User\Attribute
     */
    private $tenantOwnerAttrTable;
	
	

    /**
     * @param \Fusio\Impl\Service\User $userService
     * @param \Fusio\Impl\Service\Config $configService
     * @param \Fusio\Impl\Service\User\Token $tokenService
     * @param \Fusio\Impl\Service\User\Mailer $mailerService
     * @param \Fusio\Impl\Table\Role $roleTable
     */
    public function __construct(Service\User $userService, Service\User\Token $tokenService, Service\User\Mailer $mailerService, Service\Config $configService, Table\Role $roleTable, Table\User $tenantOwnerTable, Table\User\Attribute $tenantOwnerAttrTable)
    {
        $this->userService    = $userService;
        $this->tokenService   = $tokenService;
        $this->mailerService  = $mailerService;
        $this->configService  = $configService;
        $this->roleTable      = $roleTable;
	$this->tenantOwnerTable      = $tenantOwnerTable;
	$this->tenantOwnerAttrTable = $tenantOwnerAttrTable;
    }

    public function create(Member_Create $member, ContextInterface $context)
    {

        // determine initial user status
        $status   = Table\User::STATUS_DISABLED;
        $approval = $this->configService->getValue('user_approval');
        if (!$approval) {
            $status = Table\User::STATUS_ACTIVE;
        }

        $condition = new Condition();
        $condition->equals('name', $this->configService->getValue('tenant_member_role_default'));
        $role = $this->roleTable->getOneBy($condition);
        if (empty($role)) {
            throw new StatusCode\InternalServerErrorException('Invalid default role configured');
        }

        $user = new User_Create();
        $user->setRoleId((int) $role['id']);
        $user->setStatus($status);
        $user->setName($member->getName());
        $user->setEmail($member->getEmail());
        $user->setPassword($member->getPassword());
		
	//--search current tenant owner data 
	$tenant = $context->getUser();
	$condThisTenant = new Condition();
	$condThisTenant->equals('id', $tenant->getId());
	$tenantOwner = $this->tenantOwnerTable->getOneBy($condThisTenant);
	$condThisTenantAttr = new Condition();
	$condThisTenantAttr->equals('user_id',$tenant->getId());
	$condThisTenantAttr->equals('name','tenant_uid');

	$tenantOwnerAttr = $this->tenantOwnerAttrTable->getOneBy($condThisTenantAttr);
	$userAttrs = new User_Attributes();
	$userAttrs->setProperties(array('tenant_uid'=>$tenantOwnerAttr['value'],'tenant_role'=>'member'));

	$userUpd = new User_Update();
	$userUpd->setRoleId($user->getRoleId());
	$userUpd->setStatus($user->getStatus());
	$userUpd->setName($user->getName());
	$userUpd->setEmail($user->getEmail());
	$userUpd->setAttributes($userAttrs);

        $userId = $this->userService->create($user, UserContext::newActionContext($context));
	//-- soon, update the current new user
	if($userId)	$this->userService->update($userId,$userUpd,UserContext::newActionContext($context));
		
        // send activation mail
        if ($approval) {
            $token = $this->tokenService->generateToken($userId);

            $this->mailerService->sendActivationMail($token, $member->getName(), $member->getEmail());
        }
    }
	
}
