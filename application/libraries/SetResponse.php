<?php
    // This can be removed if you use __autoload() in config.php OR use Modular Extensions
    /** @noinspection PhpIncludeInspection */
    require_once APPPATH . '/libraries/REST_Controller.php';
    require_once APPPATH . '/libraries/JWT.php';
    require_once APPPATH . '/libraries/BeforeValidException.php';
    require_once APPPATH . '/libraries/ExpiredException.php';
    require_once APPPATH . '/libraries/SignatureInvalidException.php';
    use \Firebase\JWT\JWT;
    
    class SetResponse extends CI_Model{
        public function jsonResponse($data,$message,$isError){
            return array(
                'isError'   => $isError,
                'message'   => $message,
                'data'      => $data,
            );
        }
        public function getTokenData(){
            $headers = $this->input->get_request_header('Authorization');
            $kunci   = $this->config->item('thekey'); //secret key for encode and decode
            $token   = "token";
            if (!empty($headers)) {
                if (preg_match('/Bearer\s(\S+)/', $headers , $matches)) {
                    $token = $matches[1];
                }
            }

            return JWT::decode($token, $kunci, array('HS256'));
        }
    }
?>