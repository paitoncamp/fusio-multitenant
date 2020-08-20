<?php 
namespace App\Service\GL;

use App\Schema\GL\Ledgers as SchemaLedgers;
use Doctrine\DBAL\Connection;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\DispatcherInterface;
use PSX\CloudEvents\Builder;
use PSX\Framework\Util\Uuid;
use PSX\Http\Exception as StatusCode;

class Ledgers{
	

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

	public function create(SchemaLedgers $ledgers, ContextInterface $context): int
    {
        $this->assertLedgers($ledgers);

        $this->connection->beginTransaction();

        try {
            $data = [
                'group_id' => $ledgers->getGroupId(),
                'name' => $ledgers->getName(),
                'code' => $ledgers->getCode(),
                'op_balance' => $ledgers->getOpBalance(),
                'op_balance_dc' => $ledgers->getOpBalanceDc(),
                'type' => $ledgers->getType(),
                'reconciliation' => $ledgers->getReconciliation(),
                'notes' => $ledgers->getNotes(),
            ];
            $this->connection->insert('ledgers', $data);
            $id = (int) $this->connection->lastInsertId();

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();

            throw new StatusCode\InternalServerErrorException('Could not create a ledgers', $e);
        }

        $this->dispatchEvent('ledgers_created', $data);

        return $id;
    }

	public function update(int $id, SchemaLedgers $ledgers): int
    {
        $row = $this->connection->fetchAssoc('SELECT id FROM ledgers WHERE id = :id', [
            'id' => $id,
        ]);

        if (empty($row)) {
            throw new StatusCode\NotFoundException('Provided ledgers does not exist');
        }

        $this->assertLedgers($ledgers);

        $this->connection->beginTransaction();

        try {
            $data = [
                'group_id' => $ledgers->getGroupId(),
                'name' => $ledgers->getName(),
                'code' => $ledgers->getCode(),
                'op_balance' => $ledgers->getOpBalance(),
                'op_balance_dc' => $ledgers->getOpBalanceDc(),
                'type' => $ledgers->getType(),
                'reconciliation' => $ledgers->getReconciliation(),
                'notes' => $ledgers->getNotes(),

            ];

            $this->connection->update('ledgers', $data, ['id' => $id]);

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();

            throw new StatusCode\InternalServerErrorException('Could not update a ledgers', $e);
        }

        $this->dispatchEvent('ledgers_updated', $data, $id);

        return $id;
    }

	public function delete(int $id): int
    {
        $row = $this->connection->fetchAssoc('SELECT id FROM ledgers WHERE id = :id', [
            'id' => $id,
        ]);

        if (empty($row)) {
            throw new StatusCode\NotFoundException('Provided ledgers does not exist');
        }

        try {
            $this->connection->delete('ledgers', ['id' => $id]);
        } catch (\Throwable $e) {
            $this->connection->rollBack();

            throw new StatusCode\InternalServerErrorException('Could not delete a ledgers', $e);
        }

        $this->dispatchEvent('ledgers_deleted', $row, $id);

        return $id;
    }

	private function dispatchEvent(string $type, array $data, ?int $id = null){
		$event = (new Builder())
            ->withId(Uuid::pseudoRandom())
            ->withSource($id !== null ? '/ledgers/' . $id : '/ledgers')
            ->withType($type)
            ->withDataContentType('application/json')
            ->withData($data)
            ->build();

        $this->dispatcher->dispatch($type, $event);
	}

	private function assertLedgers(SchemaLedgers $ledgers)
    {

        $groupId = $ledgers->getGroupId();
        if (empty($groupId)) {
            throw new StatusCode\BadRequestException('No group_id provided');
        }

        $name = $ledgers->getName();
        if (empty($name)) {
            throw new StatusCode\BadRequestException('No name provided');
        }

        $opBalance = $ledgers->getOpBalance();
        if (empty($opBalance)) {
            throw new StatusCode\BadRequestException('No op_balance provided');
        }

        $opBalanceDc = $ledgers->getOpBalanceDc();
        if (empty($opBalanceDc)) {
            throw new StatusCode\BadRequestException('No op_balance_dc provided');
        }

        $type = $ledgers->getType();
        if (empty($type)) {
            throw new StatusCode\BadRequestException('No type provided');
        }

        $reconciliation = $ledgers->getReconciliation();
        if (empty($reconciliation)) {
            throw new StatusCode\BadRequestException('No reconciliation provided');
        }

        $notes = $ledgers->getNotes();
        if (empty($notes)) {
            throw new StatusCode\BadRequestException('No notes provided');
        }


    }
}