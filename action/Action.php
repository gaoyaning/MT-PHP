<?php

/**
 * User: gaoyaning
 * Date: 17/3/9
 * Time: 下午4:24
 */
require_once __DIR__ . "/../conf/StubConf.php";
require_once __DIR__ . "/../library/Http.php";
require_once __DIR__ . "/../stub/Stub.php";
class Action
{
    public $model_url;
    public $stub;
    public function __construct() {
        $stub_conf = StubConf::getConf();
        $this->model_url = $stub_conf['model_url'];
        $this->stub = new Stub();
    }

    public function startStub($stub_data) {
        $this->stub->setStubData($stub_data);
        $this->stub->start();
    }

    public function stopStub() {
        $this->stub->stop();
        sleep(1);
    }

    public function getCheckData($request_data) {
        return Http::postJson($this->model_url, $request_data);
    }
}
