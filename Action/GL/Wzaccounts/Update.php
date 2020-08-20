
<?php 
namespace App\Action\AppName\Wzaccounts;

use App\Service\AppName\Wzaccounts;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Http\Exception\InternalServerErrorException;
use PSX\Http\Exception\StatusCodeException;

/**
 * Action which update a wzaccounts. 
 */
class Update extends ActionAbstract {
	

	/**
     * @var Wzaccounts
     */
    private $wzaccountsService;

    public function __construct(Wzaccounts $wzaccountsService)
    {
        $this->wzaccountsService = $wzaccountsService;
    }

	public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
		try {
			$id = (int) $request->getUriFragment('wzaccounts_id');

            $this->wzaccountsService->update($id, $request->getBody()->getPayload());
		}catch (StatusCodeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $this->response->build(201, [], $body);
	}
        
}