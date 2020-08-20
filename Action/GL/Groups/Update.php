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
 * Action which update a groups. 
 */
class Update extends ActionAbstract {
	

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
			
			$id = (int) $request->getUriFragment('groups_id');
			$this->groupsService->setupTenantConnection($request->getHeader('tenantId'));
            $this->groupsService->update($id, $request->getBody()->getPayload());
			$body = [
                'success' => true, 
                'message' => 'Groups successful updated'
            ];
		}catch (StatusCodeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $this->response->build(201, [], $body);
	}
        
}