
<?php

namespace App\Action\AppName\Wzaccounts;

use Fusio\Adapter\Sql\Action\SqlBuilderAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Sql\Builder;
use PSX\Sql\Condition;

/**
 * Action which returns a collection response of all wzaccounts. It shows how to
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
                  FROM app_opengl.wzaccounts
                 WHERE 1=1
                   AND ' . $condition->getExpression($connection->getDatabasePlatform()) . '
              ORDER BY wzaccounts.id DESC';

        $parameters = array_merge($condition->getValues(), ['startIndex' => $startIndex]);
        $definition = [
            'totalResults' => $builder->doValue('SELECT COUNT(*) AS cnt FROM app_opengl.wzaccounts WHERE 1 = 1', [], $builder->fieldInteger('cnt')),
            'startIndex' => $startIndex,
            'entries' => $builder->doCollection($sql, $parameters, [
				
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
                'links' => [
                    'self' => $builder->fieldReplace('/wzaccounts/{id}'),
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
                    $condition->equals('wzaccounts.id', (int) $value);
                    break;
				
                case 'label':
                    $condition->like('wzaccounts.label', '%' . $value . '%');
                    break;
				
                case 'dbDatasource':
                    $condition->like('wzaccounts.dbDatasource', '%' . $value . '%');
                    break;
				
                case 'dbDatabase':
                    $condition->like('wzaccounts.dbDatabase', '%' . $value . '%');
                    break;
				
                case 'dbHost':
                    $condition->like('wzaccounts.dbHost', '%' . $value . '%');
                    break;
				
                case 'dbPort':
                    $condition->equals('wzaccounts.dbPort', (int) $value);
                    break;
				
                case 'dbLogin':
                    $condition->like('wzaccounts.dbLogin', '%' . $value . '%');
                    break;
				
                case 'dbPassword':
                    $condition->like('wzaccounts.dbPassword', '%' . $value . '%');
                    break;
				
                case 'dbPrefix':
                    $condition->like('wzaccounts.dbPrefix', '%' . $value . '%');
                    break;
				
                case 'dbPersistent':
                    $condition->like('wzaccounts.dbPersistent', '%' . $value . '%');
                    break;
				
                case 'dbSchema':
                    $condition->like('wzaccounts.dbSchema', '%' . $value . '%');
                    break;
				
                case 'dbUnixsocket':
                    $condition->like('wzaccounts.dbUnixsocket', '%' . $value . '%');
                    break;
				
                case 'dbSettings':
                    $condition->like('wzaccounts.dbSettings', '%' . $value . '%');
                    break;
				
                case 'sslKey':
                    $condition->like('wzaccounts.sslKey', '%' . $value . '%');
                    break;
				
                case 'sslCert':
                    $condition->like('wzaccounts.sslCert', '%' . $value . '%');
                    break;
				
                case 'sslCa':
                    $condition->like('wzaccounts.sslCa', '%' . $value . '%');
                    break;
				
            }
        }

        return $condition;
    }
}
