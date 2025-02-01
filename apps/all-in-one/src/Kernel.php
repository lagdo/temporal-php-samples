<?php

namespace App;

use App\Temporal\Compiler\ActivityStubCompilerPass;
use App\Temporal\Compiler\ChildWorkflowCompilerPass;
use App\Temporal\Compiler\ChildWorkflowStubCompilerPass;
use App\Temporal\Compiler\WorkflowCompilerPass;
use App\Temporal\Compiler\WorkflowStubCompilerPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new WorkflowCompilerPass());
        $container->addCompilerPass(new WorkflowStubCompilerPass());
        $container->addCompilerPass(new ChildWorkflowCompilerPass());
        $container->addCompilerPass(new ChildWorkflowStubCompilerPass());
        $container->addCompilerPass(new ActivityStubCompilerPass());
    }
}
