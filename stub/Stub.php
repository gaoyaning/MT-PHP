<?php

/**
 * User: gaoyaning
 * Date: 17/3/2
 * Time: 下午3:20
 */

require_once __DIR__ . "/../conf/StubConf.php";
require_once __DIR__ . "/../library/Log.php";

class Stub
{
    const pidfile = __CLASS__;
    const logfile = __DIR__ . "/../log/stub.log";
    public $stub_data;
    public $stub_conf;
    private $pid;

    public function __construct() {
        $this->stub_conf = StubConf::getConf();
        $this->pidfile = dirname(__FILE__).'/'.self::pidfile.'.pid';
    }


    public function socket($address, $port) {
        $max_backlog = 16;
        $res_len = 0;

        //Create, bind and listen to socket
        if(($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === FALSE) {
            echo "Create socket failed!\n";
            exit;
        }
        socket_set_option($socket, SOL_SOCKET, SO_KEEPALIVE,0);

        if((socket_bind($socket, $address, $port)) === FALSE) {
            echo "Bind socket failed!\n";
            exit;
        }

        if((socket_listen($socket, $max_backlog)) === FALSE) {
            echo "Listen to socket failed!\n";
            exit;
        }
        if (false === (socket_set_block($socket))) {
            echo "block to socket failed!\n";
            exit;    
        }

        //Loop
        while(TRUE) {
            $buffer = "";
            if(($accept_socket = socket_accept($socket)) === FALSE) {
                continue;
            } else {
                // 读取数据
                $buffer = socket_read($accept_socket, 4096);
                $msg = $this->decode($buffer);
                // 按报文长度再次获取报文
                $get_length = strlen($msg['data']);
                $msg['data'] = $msg['data'] .socket_read($accept_socket, $msg['Content-Length'] - $get_length);
                //print_r($params);
                $params = json_decode($msg['data'], true);
                $response = $this->getStubData($params);
                $response = $this->setResponse($response);
                socket_write($accept_socket, $response, strlen($response));
                socket_close($accept_socket);
            }
        }
    }

    public function setResponse($response) {
        $msg_length = strlen($response);
        $return_response = <<<EOF
HTTP/1.1 200 OK
Connection: close
Content-Type: application/json
Content-Length: $msg_length

$response
EOF;
        return $return_response;
    }

    public function getStubData($request) {
        if (!is_array($request)) {
            $request = json_deocde($request, true);
        }
        if (isset($request['params'])) {
            $request = $request['params'];
        }
        $request = json_decode($request, true);
        if (isset($request['risk_type'])) { 
            $risk_type = $request['risk_type'];
            $risk_sub_type = $request['risk_sub_type'];
            if (isset($this->stub_data[$risk_type]) && isset($this->stub_data[$risk_type][$risk_sub_type])) {
                return json_encode($this->stub_data[$risk_type][$risk_sub_type]);
            } else {
                return json_encode([
                    'status' => -1,
                    'msg' => '无法获取数据',
                ], JSON_UNESCAPED_UNICODE);
            }
        } else {
            return json_encode($this->stub_data);
        }
    }

    private function decode($data) {
        $return_data = [];
        $arr = explode("\r\n", $data);
        $size = count($arr) -1;
        foreach ($arr as $k => $v) {
            if (0 == $k) {
                list($method, $version) = explode(" ", $v);
                $return_data["method"] = $method;
            } elseif ($size == $k) {
                $return_data["data"] = $v;
            } elseif ($size -1 == $k) {
                continue;
            } else {
                list($key, $value) = explode(": ", $v);
                $return_data[$key] = $value;
            }
        }
        return $return_data;
    }

    public function start() {
        if (file_exists($this->pidfile)) {
            print_r(__CLASS__ . "already running\n");
            exit;
        }
        $pid = pcntl_fork();
        if (-1 == $pid) {
            die("could not fork");
        } else if ($pid) {
            //pcntl_wait($status);
            //exit;
        } else {
            posix_setsid();
            if (0 === pcntl_fork()) {
                file_put_contents($this->pidfile, posix_getpid());
                $this->socket($this->stub_conf['stub_ip'], $this->stub_conf['stub_port']);
            } else {
                exit;
            }
        }
    }

    public function stop() {
        if (file_exists($this->pidfile)) {
            $pid = file_get_contents($this->pidfile);
            posix_kill($pid, 9); 
            unlink($this->pidfile);
        }
    }

    public function setStubData($stub_data) {
        $this->stub_data = $stub_data;
    }

    public function getPid() {
        print_r($this->pid);
    }
}
