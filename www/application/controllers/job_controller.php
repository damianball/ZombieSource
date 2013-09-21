<?php
date_default_timezone_set('UTC');
require_once(APPPATH . 'libraries/Notification.php');

class job_controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Notification_model','',TRUE);
        $this->load->model('Job_model','',TRUE);
        $this->load->library('GameCreator');
        $this->load->library('ActionHandler');

    }

    public function daily_update(){
        $notification_id = $this->Notification_model->getNotificationIDByName("daily_update");
        $games = $this->gamecreator->getActiveGames();
        foreach($this->gamecreator->getActiveGames() as $game){
            $time_zone_offset_seconds = $game->UTCoffset() * 3600;
            $today = date('Y-m-d', time() + $time_zone_offset_seconds);
            $game_id = $game->getGameID();
            $job_params = $this->Job_model->getJobParamsByNotificationIDGameID($notification_id, $game_id);
            if($this->daily_job_ready_to_run($job_params->start_time_date, $job_params->last_run_date)){
                $data_obj = new stdClass();
                $data_obj->notification_name = 'daily_update';
                $data_obj->game_date_id = $today;
                $notification = new Notification($game_id, $data_obj);
                $notification->send();
                $this->Job_model->updateLastRunTime($notification_id, $game_id);
            }
        }
    }

    public function daily_job_ready_to_run($start_time_date, $last_run_date){
        //UTC Datetimes
        $last_run_datetime = new DateTime($last_run_date);
        $start_datetime = new DateTime($start_time_date);

        //epochs
        $start_epoch = strtotime($start_datetime->format('Y-m-d H:i:s'));
        $now = time();

        //epoch rounded to current day, no time. For day comparison.
        $last_run_day = strtotime($last_run_datetime->format('Y-m-d'));
        $current_day = strtotime(date('Y-m-d', $now));

        //hour of the d
        $start_hour = $start_datetime->format('H');
        $current_hour = date('H', $now);

        //if we're past or at the start time
        //AND in the right hour of the day
        //AND the job didn't run today.
        //start_hour == current_hour
        if($start_epoch <= $now && $start_hour == $current_hour &&  $last_run_day != $current_day){
            return true;
        }else{
            return false;
        }
    }
}













