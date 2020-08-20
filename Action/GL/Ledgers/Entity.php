
<?php

namespace App\\{Action}\AppName\Ledgers;

use Fusio\Adapter\Sql\Action\SqlBuilderAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Sql\Builder;

/**
 * Action which returns all details for a single ledgers
 */
class Entity extends SqlBuilderAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $this->connector->getConnection('System'); //** <<<< Please make sure to use the correct connection here <<< **/
        $builder    = new Builder($connection);

        $sql = 'SELECT 
						id,
						group_id,
						name,
						code,
						op_balance,
						op_balance_dc,
						type,
						reconciliation,
						notes
                  FROM ledgers
                 WHERE ledgers.id = :id';

        $parameters = ['id' => (int) $request->getUriFragment('ledgers_id')];
        $definition = $builder->doEntity($sql, $parameters, [
				'id' => $builder->fieldInteger('id'),
				'group_id' => $builder->fieldInteger('group_id'),
				'name' => name,
				'code' => code,
				'op_balance_dc' => op_balance_dc,
				'type' => $builder->fieldInteger('type'),
				'reconciliation' => $builder->fieldInteger('reconciliation'),
				'notes' => notes,
			//--- if it has a children, should modify below, otherwise, delete it!!!
			'children' => $builder->doCollection('SELECT id, ... FROM ... WHERE parent_id = :parent', ['parent' =>  new Reference('id')], [
                'id' => $builder->fieldInteger('id'),
				// others need to defined here...
                'links' => [
                    'self' => $builder->fieldReplace('/ledgers/{id}'),
                    'parent' => $builder->fieldReplace('/ledgers/{parent_id}'),
                ]
            ]),
            'links' => [
                'self' => $builder->fieldReplace('/ledgers/{id}'),
            ]
        ]);

        return $this->response->build(200, [], $builder->build($definition));
    }
}	
