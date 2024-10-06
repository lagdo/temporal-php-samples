<?php

namespace App\Controller;

use App\Workflow\Service\Workflow\MoneyTransfer\AccountTransferWorkflowFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/money/transfer', name: 'api_money_transfer_')]
class MoneyTransferController extends AbstractController
{
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

        $workflowExecution = AccountTransferWorkflowFacade::startWorkflow(...$workflowParams);

        return $this->json([
            'workflow' => $workflowExecution->getID(),
            'run' => $workflowExecution->getRunID(),
        ]);
    }
}
