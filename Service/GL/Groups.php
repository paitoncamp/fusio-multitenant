<?php 
namespace App\Service\GL;

use App\Schema\GL\Groups as SchemaGroups;
use Doctrine\DBAL\Connection;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\DispatcherInterface;
use Fusio\Engine\Connector;
use PSX\CloudEvents\Builder;
use PSX\Framework\Util\Uuid;
use PSX\Http\Exception as StatusCode;

class Groups{
	/**
     * @var connector
     */
    private $connector;

	/**
     * @var connection
     */
    private $connection;

    /**
     * @var DispatcherInterface
     */
    private $dispatcher;
	
	public function __construct(Connector $connector, DispatcherInterface $dispatcher)
    {
        $this->connector = $connector;
        $this->dispatcher = $dispatcher;
    }
	
	public function setupTenantConnection($tenantId){
		if ($tenantId) {
			/*
            $connection = $this->connection;
            $params     = $this->connection->getParams();

            // we also check if the current connection needs to be closed based on various things
            // have left that part in for information here
            // $appId changed from that in the connection?
            // if ($connection->isConnected()) {
            //     $connection->close();
            // }

            // Set default DB connection using tenantId
            //$params['host']   = $someHost;
            $params['dbname'] = 'gl-'.$tenantId;

            // Set up the parameters for the parent
            $connection->__construct(
                $params, $connection->getDriver(), $connection->getConfiguration(),
                $connection->getEventManager()
            );

            try {
                $connection->connect();
				return $connection;
            } catch (\Throwable $e) {
				throw new StatusCode\InternalServerErrorException('Could not setup a tenant connection', $e);
            }*/
			$this->connection = $this->connector->getConnection('gl-'.$tenantId);
        } else {
			throw new StatusCode\InternalServerErrorException('No tenantId defined on request header', null);
		}
	}

	public function create(SchemaGroups $groups, ContextInterface $context): int
    {
		
        $this->assertGroups($groups);

        $this->connection->beginTransaction();

        try {
            $data = [
                'parent_id' => $groups->getParentId(),
                'name' => $groups->getName(),
                'code' => $groups->getCode(),
                'affects_gross' => $groups->getAffectsGross()
            ];
            $this->connection->insert('groups', $data);
            $id = (int) $this->connection->lastInsertId();

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();

            throw new StatusCode\InternalServerErrorException('Could not create a groups', $e);
        }

        $this->dispatchEvent('groups_created', $data);

        return $id;
    }

	public function update(int $id, SchemaGroups $groups): int
    {
        $row = $this->connection->fetchAssoc('SELECT id FROM groups WHERE id = :id', [
            'id' => $id,
        ]);

        if (empty($row)) {
            throw new StatusCode\NotFoundException('Provided groups does not exist');
        }

        $this->assertGroups($groups);

        $this->connection->beginTransaction();

        try {
            $data = [
                'parent_id' => $groups->getParentId(),
                'name' => $groups->getName(),
                'code' => $groups->getCode(),
                'affects_gross' => $groups->getAffectsGross(),

            ];

            $this->connection->update('groups', $data, ['id' => $id]);

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();

            throw new StatusCode\InternalServerErrorException('Could not update a groups', $e);
        }

        $this->dispatchEvent('groups_updated', $data, $id);

        return $id;
    }

	public function delete(int $id): int
    {
        $row = $this->connection->fetchAssoc('SELECT id FROM groups WHERE id = :id', [
            'id' => $id,
        ]);

        if (empty($row)) {
            throw new StatusCode\NotFoundException('Provided groups does not exist');
        }

        try {
            $this->connection->delete('groups', ['id' => $id]);
        } catch (\Throwable $e) {
            $this->connection->rollBack();

            throw new StatusCode\InternalServerErrorException('Could not delete a groups', $e);
        }

        $this->dispatchEvent('groups_deleted', $row, $id);

        return $id;
    }

	private function dispatchEvent(string $type, array $data, ?int $id = null){
		$event = (new Builder())
            ->withId(Uuid::pseudoRandom())
            ->withSource($id !== null ? '/groups/' . $id : '/groups')
            ->withType($type)
            ->withDataContentType('application/json')
            ->withData($data)
            ->build();

        $this->dispatcher->dispatch($type, $event);
	}

	private function assertGroups(SchemaGroups $groups)
    {

        $name = $groups->getName();
        if (empty($name)) {
            throw new StatusCode\BadRequestException('No name provided');
        }

        $affectsGross = $groups->getAffectsGross();
        if (is_null($affectsGross)) {
            throw new StatusCode\BadRequestException('No affects_gross provided');
        }


    }
}