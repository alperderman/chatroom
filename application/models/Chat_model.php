<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat_model extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->load->database('chat');
    }

    public function get_chat($input = 'all'){  
        if($input == 'count'){
            $query = $this->db->count_all('chat');
            return $query;
        }elseif($input == 'all'){
            $query = $this->db->get('chat');
            return $query->result_array();
        }else{
            $query = $this->db->get('chat', -1, $input);
            return $query->result_array();
        }
	}

	public function insert_chat($fields, $table){
        $fields = $this->escape_input($fields);
        $table = $this->escape_input($table);
        $this->db->insert($table, $fields);
    }

    public function escape_input($input){
        if(!is_array($input)){
            $esc = $this->db->escape_str($input);
        }else{
            $esc = array();
            if(!empty($input)){
                foreach($input as $key => $val){  
                    $esc[$key] = $this->db->escape_str($val);
                }
            }
        }
        return $esc; 
    }

    public function rebound($id){
        $id = $this->escape_input($id);
        $sqlId = 'select exists(select 1 from rebound where visitor_id = "'.$id.'") as bool;';
        $time = time();
        $checkId = $this->db->query($sqlId);
        $checkId = $checkId->result_array();
        if($checkId[0]['bool'] == 0){
            $insertData = array(
                'visitor_id' => $id,
                'tries' => 1,
                'time' => $time
            );
            $this->chat_model->insert_chat($insertData, 'rebound');
            return 't';
            exit;
        }else{
            $sqlRebound = 'select * from rebound where visitor_id = "'.$id.'";';
            $rebound = $this->db->query($sqlRebound);
            $rebound = $rebound->result_array();
            $reboundTries = $rebound[0]['tries'];
            $reboundTime = $rebound[0]['time'] + (60);
            if($reboundTries >= 5){
                if($reboundTime < $time){
                    $sqlUpdate = 'update rebound set tries = 1, time = '.$time.' where visitor_id = "'.$id.'";';
                    $this->db->query($sqlUpdate);
                    return 't';
                    exit;
                }else{
                    $sqlUpdate = 'update rebound set time = '.$time.' where visitor_id = "'.$id.'";';
                    $this->db->query($sqlUpdate);
                    return 'f';
                    exit;
                }
            }else{
                if($reboundTime < $time){
                    $tries = 1;
                }else{
                    $tries = $reboundTries+1;
                }
                $sqlUpdate = 'update rebound set tries = '.$tries.', time = '.$time.' where visitor_id = "'.$id.'";';
                $this->db->query($sqlUpdate);
                return 't';
                exit;
            }
        }
    }

}