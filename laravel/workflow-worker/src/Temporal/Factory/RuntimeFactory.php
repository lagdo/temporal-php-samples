<?php

declare(strict_types=1);

namespace Sample\Temporal\Factory;

use OpenTelemetry\API\Signals;
use OpenTelemetry\API\Trace\Propagation\TraceContextPropagator;
use OpenTelemetry\Contrib\Grpc\GrpcTransportFactory;
use OpenTelemetry\Contrib\Otlp\OtlpUtil;
use OpenTelemetry\Contrib\Otlp\SpanExporter;
use OpenTelemetry\SDK\Common\Attribute\Attributes;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SDK\Resource\ResourceInfoFactory;
use OpenTelemetry\SDK\Trace\SpanProcessorFactory;
use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SemConv\ResourceAttributes;
use Temporal\Interceptor\SimplePipelineProvider;
use Temporal\OpenTelemetry\Interceptor\OpenTelemetryWorkflowOutboundRequestInterceptor;
use Temporal\OpenTelemetry\Tracer;
use Temporal\Worker\WorkerFactoryInterface;
use Temporal\Worker\WorkerInterface;

class RuntimeFactory
{
    /**
     * @param WorkerFactoryInterface $workerFactory
     * @param Tracer $tracer
     * @param string $workflowTaskQueue
     *
     * @return WorkerInterface
     */
    public static function worker(WorkerFactoryInterface $workerFactory,
        Tracer $tracer, string $workflowTaskQueue): WorkerInterface
    {
        // Create a worker that listens on a task queue.
        return $workerFactory->newWorker(
            taskQueue: $workflowTaskQueue,
            interceptorProvider: new SimplePipelineProvider([
                new OpenTelemetryWorkflowOutboundRequestInterceptor($tracer),
            ])
        );
    }

    /**
     * @param string $serviceName
     * @param string $collectorEndpoint
     *
     * @return Tracer
     */
    public static function tracer(string $serviceName, string $collectorEndpoint): Tracer
    {
        $transport = (new GrpcTransportFactory())->create($collectorEndpoint . OtlpUtil::method(Signals::TRACE));
        $spanProcessor = (new SpanProcessorFactory())->create(new SpanExporter($transport));
        $attributes = Attributes::create([ResourceAttributes::SERVICE_NAME => $serviceName]);
        $resource = ResourceInfoFactory::defaultResource()->merge(ResourceInfo::create($attributes));
        $provider = new TracerProvider(spanProcessors: $spanProcessor, resource: $resource);

        return new Tracer($provider->getTracer('Temporal Symfony samples'), TraceContextPropagator::getInstance());
    }
}
