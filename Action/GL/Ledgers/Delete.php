
<?php 
namespace App\Action\AppName\Ledgers;

use App\Service\AppName\Ledgers;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Http\Exception\InternalServerErrorException;
use PSX\Http\Exception\StatusCodeException;

/**
 * Action which delete a ledgers. 
 */
class Delete extends ActionAbstract {
	

	/**
     * @var Ledgers
     */
    private $ledgersService;

    public function __construct(Ledgers $ledgersService)
    {
        $this->ledgersService = $ledgersService;
    }

	public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
		try {
			$id = (int) $request->getUriFragment('ledgers_id');

            $this->ledgersService->delete($id);
		}catch (StatusCodeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $this->response->build(201, [], $body);
	}
        
}