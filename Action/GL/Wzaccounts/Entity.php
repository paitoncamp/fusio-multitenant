
<?php

namespace App\\{Action}\AppName\Wzaccounts;

use Fusio\Adapter\Sql\Action\SqlBuilderAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Sql\Builder;

/**
 * Action which returns all details for a single wzaccounts
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
						label,
						db_datasource,
						db_database,
						db_host,
						db_port,
						db_login,
						db_password,
						db_prefix,
						db_persistent,
						db_schema,
						db_unixsocket,
						db_settings,
						ssl_key,
						ssl_cert,
						ssl_ca
                  FROM wzaccounts
                 WHERE wzaccounts.id = :id';

        $parameters = ['id' => (int) $request->getUriFragment('wzaccounts_id')];
        $definition = $builder->doEntity($sql, $parameters, [
				'id' => $builder->fieldInteger('id'),
				'label' => label,
				'db_datasource' => db_datasource,
				'db_database' => db_database,
				'db_host' => db_host,
				'db_port' => $builder->fieldInteger('db_port'),
				'db_login' => db_login,
				'db_password' => db_password,
				'db_prefix' => db_prefix,
				'db_persistent' => db_persistent,
				'db_schema' => db_schema,
				'db_unixsocket' => db_unixsocket,
				'db_settings' => db_settings,
				'ssl_key' => ssl_key,
				'ssl_cert' => ssl_cert,
				'ssl_ca' => ssl_ca,
			//--- if it has a children, should modify below, otherwise, delete it!!!
			'children' => $builder->doCollection('SELECT id, ... FROM ... WHERE parent_id = :parent', ['parent' =>  new Reference('id')], [
                'id' => $builder->fieldInteger('id'),
				// others need to defined here...
                'links' => [
                    'self' => $builder->fieldReplace('/wzaccounts/{id}'),
                    'parent' => $builder->fieldReplace('/wzaccounts/{parent_id}'),
                ]
            ]),
            'links' => [
                'self' => $builder->fieldReplace('/wzaccounts/{id}'),
            ]
        ]);

        return $this->response->build(200, [], $builder->build($definition));
    }
}	
