<?php
namespace App\Service\Tenancy;

use Fusio\Impl\Authorization\UserContext;
use Fusio\Impl\Service;
use Fusio\Impl\Table;
use Fusio\Model\Backend\User_Create;
use Fusio\Model\Backend\User_Update;
use Fusio\Model\Backend\User_Attributes;
use App\Model\Tenancy\Tenant_Register;
use PSX\Framework\Util\Uuid;
use PSX\Http\Exception as StatusCode;
use PSX\Sql\Condition;

/**
 * TenantRegister
 * Extend Register Service capability
 * @author  Wira m.s <senasana.wira@gmail.com>
 */
class TenantRegister
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
     * @param \Fusio\Impl\Service\User $userService
     * @param \Fusio\Impl\Service\Config $configService
     * @param \Fusio\Impl\Service\User\Captcha $captchaService
     * @param \Fusio\Impl\Service\User\Token $tokenService
     * @param \Fusio\Impl\Service\User\Mailer $mailerService
     * @param \Fusio\Impl\Table\Role $roleTable
     */
    public function __construct(Service\User $userService, Service\User\Captcha $captchaService, Service\User\Token $tokenService, Service\User\Mailer $mailerService, Service\Config $configService, Table\Role $roleTable)
    {
        $this->userService    = $userService;
        $this->captchaService = $captchaService;
        $this->tokenService   = $tokenService;
        $this->mailerService  = $mailerService;
        $this->configService  = $configService;
        $this->roleTable      = $roleTable;
    }

    public function register(Tenant_Register $register)
    {
        $this->captchaService->assertCaptcha($register->getCaptcha());

        // determine initial user status
        $status   = Table\User::STATUS_DISABLED;
        $approval = $this->configService->getValue('user_approval');
        if (!$approval) {
            $status = Table\User::STATUS_ACTIVE;
        }

        $condition = new Condition();
        $condition->equals('name', $this->configService->getValue('tenant_role_default'));
        $role = $this->roleTable->getOneBy($condition);
        if (empty($role)) {
            throw new StatusCode\InternalServerErrorException('Invalid default role configured');
        }

        $user = new User_Create();
        $user->setRoleId((int) $role['id']);
        $user->setStatus($status);
        $user->setName($register->getName());
        $user->setEmail($register->getEmail());
        $user->setPassword($register->getPassword());
		
	$Uuid = new Uuid();

	$userAttrs = new User_Attributes();
	$userAttrs->setProperties(array('tenant_uid'=>$Uuid->pseudoRandom(),'tenant_role'=>'owner'));

	$userUpd = new User_Update();
	$userUpd->setRoleId($user->getRoleId());
	$userUpd->setStatus($user->getStatus());
	$userUpd->setName($user->getName());
	$userUpd->setEmail($user->getEmail());
	$userUpd->setAttributes($userAttrs);

        $userId = $this->userService->create($user, UserContext::newAnonymousContext());
	//-- soon, update the current new user
	if($userId)	$this->userService->update($userId,$userUpd,UserContext::newAnonymousContext());
		
        // send activation mail
        if ($approval) {
            $token = $this->tokenService->generateToken($userId);

            $this->mailerService->sendActivationMail($token, $register->getName(), $register->getEmail());
        }
    }
}
