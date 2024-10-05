<?php

namespace App\Controller;

use App\Workflow\Service\Workflow\MoneyBatch\MoneyBatchWorkflowFacade;
use App\Workflow\Service\Workflow\MoneyBatch\MoneyBatchWorkflowInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Temporal\Client\WorkflowClientInterface;

#[AsController]
#[Route('/api/money/batch', name: 'api_money_batch_')]
class MoneyBatchController extends AbstractController
{
    public function __construct(private WorkflowClientInterface $workflowClient)
    {}

    /**
     * @param string $workflowId
     *
     * @return object
     */
    private function getWorkflowStub(string $workflowId): object
    {
        return $this->workflowClient->newRunningWorkflowStub(MoneyBatchWorkflowInterface::class, $workflowId);
    }

    #[Route(
        '/workflows',
        name: 'start_workflow',
        methods: [Request::METHOD_POST]
    )]
    public function startWorkflow(Request $request): JsonResponse
    {
        $jsonParams = $request->getPayload()->all();
        $workflowParams = $jsonParams["args"] ?? [];
        // TODO: validate the input data here

        $exec = $this->workflowClient
            ->start(MoneyBatchWorkflowFacade::instance(), ...$workflowParams)
            ->getExecution();

        return $this->json(['workflow' => $exec->getID(), 'run' => $exec->getRunID()]);
    }

    #[Route(
        '/workflows/{workflowId}/_status',
        name: 'get_workflow_status',
        methods: [Request::METHOD_GET]
    )]
    public function getStatus(string $workflowId): JsonResponse
    {
        /** @var MoneyBatchWorkflowInterface */
        $workflow = $this->getWorkflowStub($workflowId);

        return $this->json([
            'count'=> $workflow->getCount(),
            'balance' => $workflow->getBalance(),
        ]);
    }

    #[Route(
        '/workflows/{workflowId}/_withdraw',
        name: 'withdraw_on_workflow',
        methods: [Request::METHOD_PATCH]
    )]
    public function withdraw(Request $request, string $workflowId): JsonResponse
    {
        $jsonParams = $request->getPayload()->all();
        $workflowParams = $jsonParams["args"] ?? [];
        // TODO: validate the input data here

        /** @var MoneyBatchWorkflowInterface */
        $workflow = $this->getWorkflowStub($workflowId);
        $workflow->withdraw(...$workflowParams);

        return $this->json(['success'=> true]);
    }
}
