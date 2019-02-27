<?php
/**
 * Created by PhpStorm.
 * User: trungnv
 * Date: 2/27/2019
 * Time: 10:37 AM
 */

class Worker{
    protected $swf;
    protected $domain;
    protected $task_list;

    public function __construct($swfClient, $domain, $task_list) {
        $this->domain = $domain;
        $this->task_list = $task_list;
        $this->swf = $swfClient;
    }

    public function start(){
        $this->_poll();
    }

    protected function _poll()
    {
        while (true){
            // Check with SWF for activities
            $result = $this->swf->pollForActivityTask(array(
                "domain" => $this->domain,
                "taskList" => array(
                    "name" => $this->task_list
                )
            ));

            // Take out task token from the response above
            $task_token = $result["taskToken"];
            writeLog('worker',$result);
            // Do things on the computer that this script is saved on
            $result = getResult('Trung');
            writeLog('result',$result);
            // Tell SWF that we finished what we need to do on this node
            $this->swf->respondActivityTaskCompleted(array(
                "taskToken" => $task_token,
                "result" => "I've finished!"
            ));
        }
    }
}