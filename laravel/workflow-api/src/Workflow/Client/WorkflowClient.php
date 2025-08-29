<?php

namespace Sample\Workflow\Client;

use Temporal\Api\History\V1\HistoryEvent;
use Temporal\Client\WorkflowClientInterface;

class WorkflowClient
{
    public function __construct(private WorkflowClientInterface $workflowClient)
    {}

    /**
     * Decide if an event is returned in the list.
     *
     * @param HistoryEvent $event
     *
     * @return bool
     */
    private function filter(HistoryEvent $event): bool
    {
        return true;
    }

    /**
     * Format a returned event.
     *
     * @param HistoryEvent $event
     *
     * @return array<string,string|int>
     */
    private function format(HistoryEvent $event): array
    {
        return [
            'task' => $event->getTaskId(),
            'type' => $event->getEventType(),
            'timestamp' => $event->getEventTime()?->toDateTime()->format('Y-m-d H:i:s') ?? '',
        ];
    }

    /**
     * @param string $workflowId
     * @param string $runId
     *
     * @return array<array<string,string|int>>|null
     */
    public function getWorkflowEvents(string $workflowId, string $runId): ?array
    {
        $workflow = $this->workflowClient
            ->newUntypedRunningWorkflowStub($workflowId, $runId);
        if(!$workflow->hasExecution())
        {
            return null;
        }

        $workflowHistory = $this->workflowClient->getWorkflowHistory($workflow->getExecution());

        $events = [];
        foreach($workflowHistory->getEvents() as $event)
        {
            if($this->filter($event))
            {
                $events[] = $this->format($event);
            }
        }

        return $events;
    }
}
