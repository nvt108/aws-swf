<?php
/**
 * Created by PhpStorm.
 * User: trungnv
 * Date: 2/27/2019
 * Time: 10:39 AM
 */


require_once "_init.php";
$swfClient = AmazonSwf::createSwf();
$config = new Config();
// Generate a random workflow ID
$workflowId = "worker-".time();

// Starts a new instance of our workflow
$response = $swfClient->startWorkflowExecution(array(
    "domain" => $config::DOMAIN,
    "workflowId" => $workflowId,
    "workflowType" => array(
        "name" => $config::WORKFLOW_NAME,
        "version" => $config::WORKFLOW_VERSION
    ),
    "taskList" => array(
        "name" => $config::WORKFLOW_NAME
    ),
    "input" => "Input of workflow",
    "executionStartToCloseTimeout" => "300",
    'taskStartToCloseTimeout' => "300",
    "childPolicy" => "TERMINATE"
));

echo 'Workflow started. RunId: ' . $response->body->runId . "\n";