<?php

namespace App\Dependency;

use App\Service;
use App\Repository;
use Fusio\Impl\Dependency\Container as FusioContainer;
use PSX\Http\Server\RequestFactory;
use PSX\Http\Request;
//use Fusio\Impl\Service;
use Fusio\Impl\Table;


/**
 * Custom dependency container. We can create a new service by simply defining a new method which returns the service.
 * Those services can be injected at constructor argument at any action. Therefore you only need to specify the fitting
 * type-hint and the DIC injects the fitting service
 */
class Container extends FusioContainer
{
	public function getTenantRegisterService(): Service\Tenancy\TenantRegister
    {
        return new Service\Tenancy\TenantRegister(
            $this->get('user_service'),
            $this->get('user_captcha_service'),
            $this->get('user_token_service'),
            $this->get('user_mailer_service'),
            $this->get('config_service'),
            $this->get('table_manager')->getTable(Table\Role::class)
        );
    }
	
    public function getCommentService(): Service\Comment
    {
        return new Service\Comment(
            //$this->get('comment_repository'),
			$this->getCommentRepository(),
            $this->get('engine_dispatcher')
        );
    }

    public function getPageService(): Service\Page
    {
        return new Service\Page(
            $this->get('page_repository'),
            $this->get('engine_dispatcher')
        );
    }

    public function getPostService(): Service\Post
    {
        return new Service\Post(
            $this->get('post_repository'),
            $this->get('engine_dispatcher')
        );
    }

    public function getCommentRepository(): Repository\Comment
    {
		$requestFactory= new RequestFactory();
		
		$request = $requestFactory->createRequest();
		//-- set default connection if current header no tenant ID set, otherwise use current header
		//-- especially on ACTION registration which is fusio will run dependency resolver 
		$tenantId = $request->getHeader('tenantId')==null?'System':'System-'.$request->getHeader('tenantId');
		//$commentConnection = $this->get('connector')->getConnection('system-'.$request->getHeader('tenantId'));
        return new Repository\Comment(
            $this->get('connector')->getConnection($tenantId)
        );
    }

    public function getPageRepository(): Repository\Page
    {
        return new Repository\Page(
            $this->get('connector')->getConnection('System')
        );
    }

    public function getPostRepository(): Repository\Post
    {
        return new Repository\Post(
            $this->get('connector')->getConnection('System')
        );
    }
	
	/**
	Setup Tenancy Service
	*/
	public function getTenancyService(): Service\Tenancy\Tenancy
	{
		return new Service\Tenancy\Tenancy(
			$this->get('config'),
			$this->get('connector'),
			$this->get('engine_dispatcher')
		);
	}
	

	/**
	Setup services & repository for OpenGL app
	**/
	public function getOpenGLGroupService(): Service\OpenGL\Group
    {
        return new Service\OpenGL\Group(
            $this->get('openGL_group_repository'),
            $this->get('engine_dispatcher'),
			$this->get('connector')
        );
    }

    public function getOpenGLGroupRepository(): Repository\OpenGL\Group
    {
        return new Repository\OpenGL\Group();
    }
}
