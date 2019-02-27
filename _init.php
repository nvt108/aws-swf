<?php
/**
 * Created by PhpStorm.
 * User: trungnv
 * Date: 2/27/2019
 * Time: 10:40 AM
 */
require_once __DIR__."/vendor/autoload.php";
use Aws\Swf\SwfClient;


class AmazonSwf{
    public static function createSwf(){
        $configs = include_once __DIR__."/config.php";
        return SwfClient::factory($configs['aws']);
    }
}

class Config{
    const DOMAIN = 'Aluent-Trung';
    const WORKFLOW_NAME = 'bbeOrders';
    const WORKFLOW_VERSION = "1.0";

    const ACTIVITY_NAME = 'CreateBasicOrder';
    const ACTIVITY_NAME_VERSION = '1.0';
}


function register_workflow_type($swf, $domain, $name, $version, $description) {
    try{

        $opts = array(
            'domain' => $domain,
            'name' => $name,
            'version' => $version,
            'description' => $description,
            'defaultTaskList' => [
                'name' => Config::WORKFLOW_NAME,
            ],
            "defaultChildPolicy" => "TERMINATE"
        );
        $response = $swf->registerWorkflowType($opts);

    }catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}

function register_activity_type($swf, $domain, $name, $version, $description) {
    try{
        $opts = array(
            'domain' => $domain,
            'name' => $name,
            'version' => $version,
            'description' => $description,
            'defaultTaskList' => [
                'name' => Config::WORKFLOW_NAME,
            ],
        );

        $response = $swf->registerActivityType($opts);
    }catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}
function writeLog($type = 'worker',$message = '', $messType = 'json'){
    $handle = fopen(__DIR__."/logs/{$type}.txt","a+");
    if(is_array($message) && $messType == 'json') $message = json_encode($message);
    if(is_array($message) && $messType == 'array') $message = print_r($message,true);
    fwrite($handle,$message.PHP_EOL);
    fclose($handle);
}

function getResult($input){
    return "Hello $input!!";
}
