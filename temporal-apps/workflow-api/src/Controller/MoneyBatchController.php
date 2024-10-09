<?php

namespace App\Controller;

use App\Workflow\Service\Workflow\MoneyBatch\MoneyBatchWorkflowFacade;
use App\Workflow\Service\Workflow\MoneyBatch\MoneyBatchWorkflowInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/money/batch', name: 'api_money_batch_')]
class MoneyBatchController extends AbstractController
{
    #[Route(
        '/workflows',
        name: 'start_workflow',
        methods: [Request::METHOD_POST]
    )]
    public function startWorkflow(Request $request): JsonResponse
    {
        $jsonArguments = $request->getPayload()->all();
        $workflowArguments = $jsonArguments["args"] ?? [];
        // TODO: validate the input data here

        $workflowExecution = MoneyBatchWorkflowFacade::startWorkflow(...$workflowArguments);

        return $this->json([
            'workflow' => $workflowExecution->getID(),
            'run' => $workflowExecution->getRunID(),
        ]);
    }

    #[Route(
        '/workflows/{workflowId}/_status',
        name: 'get_workflow_status',
        methods: [Request::METHOD_GET]
    )]
    public function getStatus(string $workflowId): JsonResponse
    {
        /** @var MoneyBatchWorkflowInterface */
        $workflow = MoneyBatchWorkflowFacade::getRunningWorkflow($workflowId);

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
        $jsonArguments = $request->getPayload()->all();
        $workflowArguments = $jsonArguments["args"] ?? [];
        // TODO: validate the input data here

        /** @var MoneyBatchWorkflowInterface */
        $workflow = MoneyBatchWorkflowFacade::getRunningWorkflow($workflowId);
        $workflow->withdraw(...$workflowArguments);

        return $this->json(['success'=> true]);
    }
}
