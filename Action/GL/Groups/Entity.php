<?php

namespace App\Action\GL\Groups;

use Fusio\Adapter\Sql\Action\SqlBuilderAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Sql\Builder;
use PSX\Sql\Reference;

/**
 * Action which returns all details for a single groups
 */
class Entity extends SqlBuilderAbstract
{
	
	
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        /** @var \Doctrine\DBAL\Connection $connection */
		
		$tenantId = $request->getHeader('tenantId');
		
        $connection = $this->connector->getConnection('gl-'.$tenantId); //** <<<< Please make sure to use the correct connection here <<< **/
        $builder    = new Builder($connection);

        $sql = 'SELECT 
						id,
						name,
						code,
						affects_gross
                  FROM groups
                 WHERE groups.id = :id';

        $parameters = ['id' => (int) $request->getUriFragment('groups_id')];
        $definition = $builder->doEntity($sql, $parameters, [
				'id' => $builder->fieldInteger('id'),
				//'parent_id' => $builder->fieldInteger('parent_id'),
				'name' => 'name',
				'code' => 'code',
				'affects_gross' => $builder->fieldInteger('affects_gross'),
			//--- if it has a children, should modify below, otherwise, delete it!!!
			'children' => $builder->doCollection('SELECT id, parent_id,name,code,affects_gross FROM groups WHERE parent_id = :parent', ['parent' =>  new Reference('id')], [
                'id' => $builder->fieldInteger('id'),
				'name' => 'name',
				'code' => 'code',
				'affects_gross' => $builder->fieldInteger('affects_gross'),
				// others need to defined here...
                'links' => [
                    'self' => $builder->fieldReplace('/groups/{id}'),
                    'parent' => $builder->fieldReplace('/groups/{parent_id}'),
                ]
            ]),
            'links' => [
                'self' => $builder->fieldReplace('/groups/{id}'),
            ]
        ]);

        return $this->response->build(200, [], $builder->build($definition));
    }
}	
