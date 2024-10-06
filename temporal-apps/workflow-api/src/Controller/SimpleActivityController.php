<?php

namespace App\Controller;

use App\Workflow\Service\Workflow\SimpleActivity\GreetingWorkflowFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/greeting', name: 'api_simple_activity_')]
class SimpleActivityController extends AbstractController
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

        $workflowExecution = GreetingWorkflowFacade::startWorkflow(...$workflowParams);

        return $this->json([
            'workflow' => $workflowExecution->getID(),
            'run' => $workflowExecution->getRunID(),
        ]);
    }
}
