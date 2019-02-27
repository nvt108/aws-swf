<?php

class Decider{


    protected $swf;
    protected $domain;
    protected $task_list;

    public function __construct($swfClient, $domain, $task_list)
    {
        $this->domain = $domain;
        $this->task_list = $task_list;
        $this->swf = $swfClient;
    }

    public function start()
    {
        $this->_poll();
    }

    protected function _poll()
    {
        while (true) {
            // Ask SWF for things the decider needs to know
            $result = $this->swf->pollForDecisionTask(array(
                "domain" => $this->domain,
                "taskList" => array(
                    "name" => $this->task_list
                ),
                "identify" => "default",
                "maximumPageSize" => 50,
                "reverseOrder" => true
            ));
            // Current version of activity types we are using
            $activity_type_version = "1.0";

            // Parse info we need returned from the pollForDecisionTask call
            $task_token = $result["taskToken"];
            $workflow_id = $result["workflowExecution"]["workflowId"];
            $run_id = $result["workflowExecution"]["runId"];
            $last_event = $result["events"][0]["eventId"];
            writeLog('decider','workflow_id '.$workflow_id);
            writeLog('decider','run_id '.$run_id);
            writeLog('decider','last_event '.$last_event);
            // Our logic that decides what happens next
            if($last_event == "3"){
                $activity_type_name = "GeneratePrintFiles";
                $task_list = $this->task_list;
                $activity_id = "1";
                $continue_workflow = true;
            }
            else{
                $activity_type_name = "CreateBasicOrder";
                $task_list = $this->task_list;
                $activity_id = "2";
                $continue_workflow = true;
            }

            // Now that we populated our variables based on what we received
            // from SWF, we need to tell SWF what we want to do next
            if($continue_workflow === true){
                $this->swf->respondDecisionTaskCompleted(array(
                    "taskToken" => $task_token,
                    "decisions" => array(
                        array(
                            "decisionType" => "ScheduleActivityTask",
                            "scheduleActivityTaskDecisionAttributes" => array(
                                "activityType" => array(
                                    "name" => $activity_type_name,
                                    "version" => $activity_type_version
                                ),
                                "activityId" => $activity_id,
                                "control" => "this is a sample message",
                                // Customize timeout values
                                "scheduleToCloseTimeout" => "360",
                                "scheduleToStartTimeout" => "300",
                                "startToCloseTimeout" => "60",
                                "heartbeatTimeout" => "60",
                                "taskList" => array(
                                    "name" => $task_list
                                ),
                                "input" => "this is a sample message"
                            )
                        )
                    )
                ));
            }
            // End workflow if last event ID was 8
            else if($continue_workflow === false){
                $this->swf->respondDecisionTaskCompleted(array(
                    "taskToken" => $task_token,
                    "decisions" => array(
                        array(
                            "decisionType" => "CompleteWorkflowExecution"
                        )
                    )
                ));
            }
        }
    }
}