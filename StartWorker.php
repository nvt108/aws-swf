<?php
/**
 * Created by PhpStorm.
 * User: trungnv
 * Date: 2/27/2019
 * Time: 10:38 AM
 */
require_once "_init.php";
require_once "Worker.php";

$swfClient = AmazonSwf::createSwf();
$config = new Config();
//register_activity_type($swfClient,$config::DOMAIN,'GeneratePrintFiles','1.0',"Activity create by Trung");
echo "Starting activity worker polling\n";
$worker = new Worker($swfClient, $config::DOMAIN, $config::WORKFLOW_NAME);
$worker->start();