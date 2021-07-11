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
