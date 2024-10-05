<?php

namespace App\Controller;

use App\Workflow\Service\Workflow\MoneyTransfer\AccountTransferWorkflowFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Temporal\Client\WorkflowClientInterface;

#[AsController]
#[Route('/api/money/transfer', name: 'api_money_transfer_')]
class MoneyTransferController extends AbstractController
{
    public function __construct(private WorkflowClientInterface $workflowClient)
    {}

    #[Route(
        '/workflows',
        name: 'start_workflow',
        methods: [Request::METHOD_POST],
    )]
    public function startWorkflow(Request $request): JsonResponse
    {
        $jsonParams = $request->getPayload()->all();
        $workflowParams = $jsonParams["args"] ?? [];
        // TODO: validate the input data here

        $exec = $this->workflowClient
            ->start(AccountTransferWorkflowFacade::instance(), ...$workflowParams)
            ->getExecution();

        return $this->json(['workflow' => $exec->getID(), 'run' => $exec->getRunID()]);
    }
}
