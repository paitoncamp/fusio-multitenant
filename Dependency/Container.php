<?php

namespace App\Dependency;

//--- use all custom services here
//use App\Service\Page;
//use App\Service\Post;

//use App\Service\GL\Wzaccounts;
use App\Service\GL\Groups;
//use App\Service\GL\Ledgers;
use App\Service\Tenancy\Tenancy;

use Fusio\Impl\Dependency\Container as FusioContainer;



/**
 * Custom dependency container. We can create a new service by simply defining
 * a new method which returns the service. Those services can be injected at
 * constructor argument at any action. Therefor you only need to specify the
 * fitting type-hint and the DIC injects the fitting service
 */
class Container extends FusioContainer
{
	/*
    public function getPageService(): Page
    {
        return new Page(
            $this->get('connector')->getConnection('System'),
            $this->get('engine_dispatcher')
        );
    }
	
    public function getPostService(): Post
    {
        return new Post(
            $this->get('connector')->getConnection('System'),
            $this->get('engine_dispatcher')
        );
    }
	*/
	/*
	public function getWzaccountsService(): Wzaccounts
    {
        return new Wzaccounts(
            $this->get('connector')->getConnection('OpenGL'),
            $this->get('engine_dispatcher')
        );
    }*/
	//---App service
	public function getGroupsService(): Groups
    {
		//->getConnection('System')
        return new Groups(
            $this->get('connector'),
            $this->get('engine_dispatcher')
        );
    }
	
	//--- App multitenant system service
	public function getTenancyService(): Tenancy
    {
		//->getConnection('System')
        return new Tenancy(
            $this->get('connector'),
            $this->get('engine_dispatcher')
        );
    }
	/*
	public function getLedgersService(): Ledgers
    {
        return new Ledgers(
            $this->get('connector')->getConnection('OpenGL'),
            $this->get('engine_dispatcher')
        );
    }*/
}