# Temporal samples for Symfony

This repo provides sample Symfony applications and Docker Compose config to easily get started with [Temporal durable workflows](https://temporal.io/).

There applications are in the `temporal-apps` subdir.
- An API to interact (start, query, signal) with the workflows: `temporal-apps\workflow-api`.
- A first worker that only execute Temporal workflow functions: `temporal-apps\workflow-worker`.
- A second worker that only execute Temporal activity functions: `temporal-apps\activity-worker`.

The workers run on the [RoadRunner](https://roadrunner.dev/) application server, while the API can be
run either with `Nginx+PHP-FPM`, [Nginx Unit](https://unit.nginx.org/) or [FrankenPHP](https://frankenphp.dev/).
Each of the workers listens on a dedicated queue on the Temporal server.

The workflow examples are taken from the [Temporal PHP SDK sampes](https://github.com/temporalio/samples-php), and adapted to the Symfony applications.

## How it works

Implementing a workflow in these applications requires to define interfaces, classes and [facades](https://github.com/lagdo/symfony-facades), resp. for workflows and activities, together with their respective options.

The [facades](https://github.com/lagdo/symfony-facades) are required here because we are in a case where dependency injection simply doesn't work with workflow classes.

### Workflows and activities

The interfaces and classes for the workflows and activities are the basis when working with Temporal.
Many examples of these can be found in the [Temporal PHP SDK sampes](https://github.com/temporalio/samples-php) repo.

In these Symfony projects, the workflow and activity classes are located in the `src\Workflow\Service\Workflow` and `src\Workflow\Service\Activity` subdirs. Of course, their namespaces are changed accordingly.

- The workflow interfaces must be deployed on the `workflow-api` and `workflow-worker` apps.
- The workflow classes must be deployed only on the `workflow-worker` app.
- The activity interfaces must be deployed on the `activity-worker` and `workflow-worker` apps.
- The activity classes must be deployed only on the `activity-worker` app.

### Stubs and facades

When a workflow or an activity function is called, the Temporal library actually uses a proxy class to forward the call to its server, which will in turn forward the same call to an available worker.
These proxy classes are called `stubs`.

It can be supposed that they implement the interfaces of the workflows and activities they are proxying, althougth they actually do not. That's why the Symfony dependency injection cannot be used to inject a stub where a workflow or an activity interface is required.

As a consequence, [facades](https://github.com/lagdo/symfony-facades) are used anytime a call to a workflow or an activity function needs to be made.
That means:
- When a workflow is started or called in the `workflow-api` app.
- When an actiivity is called or a child workflow started in the `workflow-worker` app.

In summary, a facade will use a workflow or activity interface as service identifier, and forward its calls to a Temporal stub that it has picked in the Symfony service container.
The Symfony apps are configured to automatically register the stubs in the service container, using the interface name as key.

### Options and attributes

With Temporal, the options of a workflow or an activity function are defined when making the call to the server.
In this case, that also means when making a call to a stub using a facade.

In the Symfony apps, the workflow and activity options are defined in the `config/temporal/services.yaml` config file.

```yaml
    moneyBatchWorkflowOptions:
        class: 'Temporal\Client\WorkflowOptions'
        factory: ['App\Temporal\Factory\WorkflowFactory', 'moneyBatchOptions']
```

The options are then applied to a stub (or a facade) using an attribute on the corresponding interface.

```php
namespace App\Workflow\Service\Workflow\MoneyBatch;

use App\Temporal\Attribute\WorkflowOptions;
use Temporal\Workflow\WorkflowInterface;

#[WorkflowInterface]
#[WorkflowOptions(serviceId: "moneyBatchWorkflowOptions")]
interface MoneyBatchWorkflowInterface
{
    //
}
```

Three classes are defined for attributes: `App\Temporal\Attribute\WorkflowOptions`, `App\Temporal\Attribute\ActivityOptions`, and `App\Temporal\Attribute\ChildWorkflowOptions`.

### Summary

## How it is implemented

### Configuration

### Runtimes

### Compiler passes

### Factories

### Attributes

### PHP application servers
