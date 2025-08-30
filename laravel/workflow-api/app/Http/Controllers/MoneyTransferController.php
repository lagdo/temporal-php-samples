<?php

namespace App\Http\Controllers;

use Sample\Workflow\Service\Workflow\MoneyTransfer\AccountTransferWorkflowFacade;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MoneyTransferController extends Controller
{
    public function startWorkflow(Request $request): JsonResponse
    {
        $jsonArguments = $request->getPayload()->all();
        $workflowArguments = $jsonArguments["args"] ?? [];
        // TODO: validate the input data here

        $workflowExecution = AccountTransferWorkflowFacade::startWorkflow(...$workflowArguments);

        return response()->json([
            'workflow' => $workflowExecution->getID(),
            'run' => $workflowExecution->getRunID(),
        ]);
    }
}
