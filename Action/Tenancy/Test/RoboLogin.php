<?php 
namespace App\Action\Tenancy\Test;

use App\Service\Tenancy\Tenancy;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Http\Exception\InternalServerErrorException;
use PSX\Http\Exception\StatusCodeException;

/**
 * Action which test a tenancy system. 
 */
class RoboLogin extends ActionAbstract {
	

	/**
     * @var Tenancy
     */
    private $tenancyService;

    public function __construct(Tenancy $tenancyService)
    {
        $this->tenancyService = $tenancyService;
    }

	public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
		try {
			//$this->groupsService->setupTenantConnection($request->getHeader('tenantId'));
			$res = $this->tenancyService->roboLogin();
			$body = [
                'success' => true, 
                'message' => 'RoboLogin successful loging in',
				'result' => $res
            ];
		}catch (StatusCodeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $this->response->build(201, [], $body);
	}
        
}