<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('chat_model');
    }

    public function index(){
        $page = 'chat';

        if(!file_exists(APPPATH.'views/page/'.$page.'.php'))
        {
            show_404();
        }

        $data['title'] = $page;
        $data['path'] = 'page/'.$page;

        $this->load->view('templates/header', $data);
        $this->load->view('page/'.$page, $data);
        $this->load->view('templates/footer', $data);
    }

    public function get(){
        session_write_close();
        ignore_user_abort(true);
        set_time_limit(0);

        $this->output->set_content_type('application/json', 'utf-8');

        try{
            $postRow = $this->input->post('row', true);
            $postTime = $this->input->post('time', true);

            if ($postRow == 'f' || $postTime == 'f') {

                $postRow = $this->chat_model->get_chat('count')-1;
                $postTime = time();

                if($postRow < 0){
                    $data = 'f';
                }else{
                    $data = $this->chat_model->get_chat();
                }

                $response = array (
                    'status' => true,
                    'data' => $data,
                    'row' => $postRow,
                    'time' => $postTime
                );
                $this->output
                    ->set_status_header(200)
                    ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                    ->_display();
                    exit;
            }

            while(1){
                $chatTime = filemtime(FCPATH.'/assets/db/chat.db');

                if($chatTime > $postTime){
                    $dbRow = $this->chat_model->get_chat('count')-1;
                    if($dbRow != $postRow){
                        $postTime = time();

                        if($dbRow < 0){
                            $data = 'f';
                        }elseif($dbRow < 1){
                            $data = $this->chat_model->get_chat();
                        }else{
                            $data = $this->chat_model->get_chat($dbRow);
                        }

                        $response = array (
                            'status' => true,
                            'data' => $data,
                            'row' => $dbRow,
                            'time' => $postTime
                        );

                        $this->output
                            ->set_status_header(200)
                            ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                            ->_display();
                        exit;
                    }
                }

                while (ob_get_level()){
                    ob_get_contents();
                    ob_end_clean();
                }

                ob_flush();
                flush();

                if(connection_status() != CONNECTION_NORMAL){
                    $this->db->close();
                    exit;
                }

                clearstatcache();
                sleep(2);
            }

        }catch(Exception $e){
            $response = array (
                'status' => false,
                'response' => $e -> getMessage()
            );
            $this->output
                ->set_status_header(500)
                ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                ->_display();
            exit;
        }
    }

    public function send(){
        $this->output->set_content_type('application/json', 'utf-8');

        try{
            $postIp = $this->input->ip_address();
            $postLang = $this->input->post('lang', true);
            $postZone = $this->input->post('zone', true);
            $postUserAgent = $this->input->user_agent(true);
            $postId = hexdec(crc32($postIp.$postLang.$postZone.$postUserAgent));

            $checkRebound = $this->chat_model->rebound($postId);

            if($checkRebound == 'f'){
                throw new Exception("You've sent too much messages in a short amount of time span, Try again in a minute!");
            }

            $postName = $this->input->post('name', true);
            $postText = $this->input->post('text', true);
            
            if(strlen($postName) > 18 || strlen($postText) > 80 ||  empty($postName) || empty($postText)){
                throw new Exception("Error!");
            }

            $insertData = array(
                'visitor_id' => $postId,
                'name' => $postName,
                'text' => $postText
            );

            $this->chat_model->insert_chat($insertData, 'chat');

            $response = array (
                'status' => true,
                'response' => 'Success!'
            );

            $this->output
                ->set_status_header(200)
                ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                ->_display();
            exit;


        }catch(Exception $e){
            $response = array (
                'status' => false,
                'response' => $e -> getMessage()
            );
            $this->output
                ->set_status_header(500)
                ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                ->_display();
            exit;
        }

    }

}