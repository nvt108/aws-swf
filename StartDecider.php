<?php
/**
 * Created by PhpStorm.
 * User: trungnv
 * Date: 2/27/2019
 * Time: 10:38 AM
 */
require_once "_init.php";
require_once "Decider.php";
$swfClient = AmazonSwf::createSwf();
$config = new Config();
// Register a workflow
//register_workflow_type($swfClient,$config::DOMAIN,$config::WORKFLOW_NAME,$config::WORKFLOW_VERSION,"This workflow create by Trung");
echo "Starting Decider polling\n";
$decider = new Decider($swfClient, $config::DOMAIN, $config::WORKFLOW_NAME);
$decider->start();