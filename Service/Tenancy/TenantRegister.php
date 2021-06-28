<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2020 Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\Service\Tenancy; //Fusio\Impl\Service\User;

use Fusio\Impl\Authorization\UserContext;
use Fusio\Impl\Service;
use Fusio\Impl\Table;
use Fusio\Model\Backend\User_Create;
use Fusio\Model\Backend\User_Update;
use Fusio\Model\Backend\User_Attributes;
//use Fusio\Model\Consumer\User_Register;
use App\Model\Tenancy\Tenant_Register;
use PSX\Framework\Util\Uuid;
use PSX\Http\Exception as StatusCode;
use PSX\Sql\Condition;

/**
 * TenantRegister
 * Extend Register Service capability
 * @author  Wira m.s <senasana.wira@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
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
        $condition->equals('name', $this->configService->getValue('role_default'));
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
		//$userAttrs = array('tenant_uid'=>$Uuid->pseudoRandom(),'tenant_role'=>'owner');
		
		$userAttrs = new User_Attributes();
		$userAttrs->setProperties(array('tenant_uid'=>$Uuid->pseudoRandom(),'tenant_role'=>'owner'));
		
		//$user->setAttributes($userAttrs);
		
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
