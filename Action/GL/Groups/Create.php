<?php 
namespace App\Action\GL\Groups;

use App\Service\GL\Groups;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Http\Exception\InternalServerErrorException;
use PSX\Http\Exception\StatusCodeException;

/**
 * Action which create a groups. 
 */
class Create extends ActionAbstract {
	

	/**
     * @var Groups
     */
    private $groupsService;

    public function __construct(Groups $groupsService)
    {
        $this->groupsService = $groupsService;
    }

	public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
		try {
			$this->groupsService->setupTenantConnection($request->getHeader('tenantId'));
			$this->groupsService->create($request->getBody()->getPayload(), $context);
			$body = [
                'success' => true, 
                'message' => 'Groups successful created'
            ];
		}catch (StatusCodeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $this->response->build(201, [], $body);
	}
        
}