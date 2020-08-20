<?php 
namespace App\Service\GL;

use App\Schema\GL\Wzaccounts as SchemaWzaccounts;
use Doctrine\DBAL\Connection;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\DispatcherInterface;
use PSX\CloudEvents\Builder;
use PSX\Framework\Util\Uuid;
use PSX\Http\Exception as StatusCode;

class Wzaccounts{
	

	/**
     * @var Connection
     */
    private $connection;

    /**
     * @var DispatcherInterface
     */
    private $dispatcher;
	
	public function __construct(Connection $connection, DispatcherInterface $dispatcher)
    {
        $this->connection = $connection;
        $this->dispatcher = $dispatcher;
    }

	public function create(SchemaWzaccounts $wzaccounts, ContextInterface $context): int
    {
        $this->assertWzaccounts($wzaccounts);

        $this->connection->beginTransaction();

        try {
            $data = [
                'label' => $wzaccounts->getLabel(),
                'db_datasource' => $wzaccounts->getDbDatasource(),
                'db_database' => $wzaccounts->getDbDatabase(),
                'db_host' => $wzaccounts->getDbHost(),
                'db_port' => $wzaccounts->getDbPort(),
                'db_login' => $wzaccounts->getDbLogin(),
                'db_password' => $wzaccounts->getDbPassword(),
                'db_prefix' => $wzaccounts->getDbPrefix(),
                'db_persistent' => $wzaccounts->getDbPersistent(),
                'db_schema' => $wzaccounts->getDbSchema(),
                'db_unixsocket' => $wzaccounts->getDbUnixsocket(),
                'db_settings' => $wzaccounts->getDbSettings(),
                'ssl_key' => $wzaccounts->getSslKey(),
                'ssl_cert' => $wzaccounts->getSslCert(),
                'ssl_ca' => $wzaccounts->getSslCa(),
            ];
            $this->connection->insert('wzaccounts', $data);
            $id = (int) $this->connection->lastInsertId();

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();

            throw new StatusCode\InternalServerErrorException('Could not create a wzaccounts', $e);
        }

        $this->dispatchEvent('wzaccounts_created', $data);

        return $id;
    }

	public function update(int $id, SchemaWzaccounts $wzaccounts): int
    {
        $row = $this->connection->fetchAssoc('SELECT id FROM wzaccounts WHERE id = :id', [
            'id' => $id,
        ]);

        if (empty($row)) {
            throw new StatusCode\NotFoundException('Provided wzaccounts does not exist');
        }

        $this->assertWzaccounts($wzaccounts);

        $this->connection->beginTransaction();

        try {
            $data = [
                'label' => $wzaccounts->getLabel(),
                'db_datasource' => $wzaccounts->getDbDatasource(),
                'db_database' => $wzaccounts->getDbDatabase(),
                'db_host' => $wzaccounts->getDbHost(),
                'db_port' => $wzaccounts->getDbPort(),
                'db_login' => $wzaccounts->getDbLogin(),
                'db_password' => $wzaccounts->getDbPassword(),
                'db_prefix' => $wzaccounts->getDbPrefix(),
                'db_persistent' => $wzaccounts->getDbPersistent(),
                'db_schema' => $wzaccounts->getDbSchema(),
                'db_unixsocket' => $wzaccounts->getDbUnixsocket(),
                'db_settings' => $wzaccounts->getDbSettings(),
                'ssl_key' => $wzaccounts->getSslKey(),
                'ssl_cert' => $wzaccounts->getSslCert(),
                'ssl_ca' => $wzaccounts->getSslCa(),

            ];

            $this->connection->update('wzaccounts', $data, ['id' => $id]);

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();

            throw new StatusCode\InternalServerErrorException('Could not update a wzaccounts', $e);
        }

        $this->dispatchEvent('wzaccounts_updated', $data, $id);

        return $id;
    }

	public function delete(int $id): int
    {
        $row = $this->connection->fetchAssoc('SELECT id FROM wzaccounts WHERE id = :id', [
            'id' => $id,
        ]);

        if (empty($row)) {
            throw new StatusCode\NotFoundException('Provided wzaccounts does not exist');
        }

        try {
            $this->connection->delete('wzaccounts', ['id' => $id]);
        } catch (\Throwable $e) {
            $this->connection->rollBack();

            throw new StatusCode\InternalServerErrorException('Could not delete a wzaccounts', $e);
        }

        $this->dispatchEvent('wzaccounts_deleted', $row, $id);

        return $id;
    }

	private function dispatchEvent(string $type, array $data, ?int $id = null){
		$event = (new Builder())
            ->withId(Uuid::pseudoRandom())
            ->withSource($id !== null ? '/wzaccounts/' . $id : '/wzaccounts')
            ->withType($type)
            ->withDataContentType('application/json')
            ->withData($data)
            ->build();

        $this->dispatcher->dispatch($type, $event);
	}

	private function assertWzaccounts(SchemaWzaccounts $wzaccounts)
    {

        $label = $wzaccounts->getLabel();
        if (empty($label)) {
            throw new StatusCode\BadRequestException('No label provided');
        }


    }
}