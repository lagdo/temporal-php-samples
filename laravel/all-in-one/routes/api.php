<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GreetingController;
use App\Http\Controllers\MoneyBatchController;
use App\Http\Controllers\MoneyTransferController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\SimpleBatchController;
use App\Http\Controllers\WorkflowController;

Route::post('/greeting/workflows',
    [GreetingController::class, 'startWorkflow'])
    ->name('api_simple_activity_start_workflow');

Route::post('/money/batch/workflows',
    [MoneyBatchController::class, 'startWorkflow'])
    ->name('api_money_batch_start_workflow');

Route::get('/money/batch/workflows/{workflowId}/_status',
    [MoneyBatchController::class, 'getStatus'])
    ->name('api_money_batch_get_workflow_status');

Route::patch('/money/batch/workflows/{workflowId}/_withdraw',
    [MoneyBatchController::class, 'withdraw'])
    ->name('api_money_batch_withdraw_on_workflow');

Route::post('/money/transfer/workflows',
    [MoneyTransferController::class, 'startWorkflow'])
    ->name('api_money_transfer_start_workflow');

Route::post('/parent/workflows',
    [ParentController::class, 'startWorkflow'])
    ->name('api_parent_start_workflow');

Route::post('/simple/batch/workflows',
    [SimpleBatchController::class, 'startWorkflow'])
    ->name('api_simple_batch_start_workflow');

Route::get('/simple/batch/workflows/{workflowId}/_status',
    [SimpleBatchController::class, 'getStatus'])
    ->name('api_simple_batch_get_workflow_status');

Route::get('/workflows/{workflowId}/runs/{runId}/events',
    [WorkflowController::class, 'getEvents'])
    ->name('api_get_workflow_events');
