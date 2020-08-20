<?php

namespace App\Action\GL\Groups;

use Fusio\Adapter\Sql\Action\SqlBuilderAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Sql\Builder;
use PSX\Sql\Condition;

/**
 * Action which returns a collection response of all groups. It shows how to
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
						parent_id,
						name,
						code,
						affects_gross
                  FROM app_opengl.groups
                 WHERE 1=1
                   AND ' . $condition->getExpression($connection->getDatabasePlatform()) . '
              ORDER BY groups.id DESC';

        $parameters = array_merge($condition->getValues(), ['startIndex' => $startIndex]);
        $definition = [
            'totalResults' => $builder->doValue('SELECT COUNT(*) AS cnt FROM app_opengl.groups WHERE 1 = 1', [], $builder->fieldInteger('cnt')),
            'startIndex' => $startIndex,
            'entries' => $builder->doCollection($sql, $parameters, [
				
				'id' => $builder->fieldInteger('id'),
				'parent_id' => $builder->fieldInteger('parent_id'),
				'name' => name,
				'code' => code,
				'affects_gross' => $builder->fieldInteger('affects_gross'),
                'links' => [
                    'self' => $builder->fieldReplace('/groups/{id}'),
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
                    $condition->equals('groups.id', (int) $value);
                    break;
				
                case 'parentId':
                    $condition->equals('groups.parentId', (int) $value);
                    break;
				
                case 'name':
                    $condition->like('groups.name', '%' . $value . '%');
                    break;
				
                case 'code':
                    $condition->like('groups.code', '%' . $value . '%');
                    break;
				
                case 'affectsGross':
                    $condition->equals('groups.affectsGross', (int) $value);
                    break;
				
            }
        }

        return $condition;
    }
}
