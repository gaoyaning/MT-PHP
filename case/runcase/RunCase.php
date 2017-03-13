<?php

/**
 * User: gaoyaning
 * Date: 17/3/10
 * Time: 上午10:59
 */
require_once __DIR__ . "/../../action/Action.php";
require_once __DIR__ . "/../../pattern/ModelPattern.php";
class RunCase
{
    public $action;
    public $pattern;
    public function __construct() {
        $this->pattern = new ModelPattern();
        $this->action  = new Action();
    }

    public function createStub($stub_data) {
        $stub_data = $this->pattern->setStubData($stub_data);
        $this->action->startStub($stub_data);
    }

    public function destroyStub() {
        $this->action->stopStub();
    }

    public function createRequest($request_data) {
        try {
            $request_data = $this->pattern->setRequestData($request_data);
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
        return $request_data;
    }

    public function getCheckData($request_data) {
        return $this->action->getCheckData($request_data);
    }

    public function checkData($diff_data, $check_data) {
        return true;//false
    }
}
