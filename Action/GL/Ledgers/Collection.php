
<?php

namespace App\Action\AppName\Ledgers;

use Fusio\Adapter\Sql\Action\SqlBuilderAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Sql\Builder;
use PSX\Sql\Condition;

/**
 * Action which returns a collection response of all ledgers. It shows how to
 * build complex nested JSON structures based on SQL queries
 */
class Collection extends SqlBuilderAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $this->connector->getConnection('System');  //** <<<< Please make sure to use the correct connection here <<< **/
        $builder    = new Builder($connection);

        $startIndex = (int) $request->getParameter('startIndex');
        $startIndex = $startIndex <= 0 ? 0 : $startIndex;
        $condition  = $this->getCondition($request);
		
		/** NEED to Customize the sql query here **/
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
                  FROM app_opengl.ledgers
                 WHERE 1=1
                   AND ' . $condition->getExpression($connection->getDatabasePlatform()) . '
              ORDER BY ledgers.id DESC';

        $parameters = array_merge($condition->getValues(), ['startIndex' => $startIndex]);
        $definition = [
            'totalResults' => $builder->doValue('SELECT COUNT(*) AS cnt FROM app_opengl.ledgers WHERE 1 = 1', [], $builder->fieldInteger('cnt')),
            'startIndex' => $startIndex,
            'entries' => $builder->doCollection($sql, $parameters, [
				
				'id' => $builder->fieldInteger('id'),
				'group_id' => $builder->fieldInteger('group_id'),
				'name' => name,
				'code' => code,
				'op_balance_dc' => op_balance_dc,
				'type' => $builder->fieldInteger('type'),
				'reconciliation' => $builder->fieldInteger('reconciliation'),
				'notes' => notes,
                'links' => [
                    'self' => $builder->fieldReplace('/ledgers/{id}'),
                ]
            ])
        ];

        return $this->response->build(200, [], $builder->build($definition));
    }

    private function getCondition(RequestInterface $request)
    {
        $parameters = $request->getParameters();
        $condition  = new Condition();
		
		/** currently parameter is auto-generated for int & string field type only, others need to defined manually **/
        foreach ($parameters as $name => $value) {
            switch ($name) {
                case 'id':
                    $condition->equals('ledgers.id', (int) $value);
                    break;
				
                case 'groupId':
                    $condition->equals('ledgers.groupId', (int) $value);
                    break;
				
                case 'name':
                    $condition->like('ledgers.name', '%' . $value . '%');
                    break;
				
                case 'code':
                    $condition->like('ledgers.code', '%' . $value . '%');
                    break;
				
                case 'opBalanceDc':
                    $condition->like('ledgers.opBalanceDc', '%' . $value . '%');
                    break;
				
                case 'type':
                    $condition->equals('ledgers.type', (int) $value);
                    break;
				
                case 'reconciliation':
                    $condition->equals('ledgers.reconciliation', (int) $value);
                    break;
				
                case 'notes':
                    $condition->like('ledgers.notes', '%' . $value . '%');
                    break;
				
            }
        }

        return $condition;
    }
}
