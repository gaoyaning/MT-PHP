<?php

/**
 * Created by PhpStorm.
 * User: gaoyaning
 * Date: 17/3/9
 * Time: 下午7:47
 */
class ModelPattern {
    public $stub_data = [
        'zm' => [
            'zm' => [
                'data' => [
                    'content' => ['score' => 650],
                ],
                'status' => 1,
            ],
            'identify' => [
                'data' => [
                    'content' => [
                        'iVSVerifyResult' => [
                            'resultMap' => [
                                'infocode_result_list' => 'ADDR_Mismatch',
                            ],
                            'score' => 90,
                        ],
                    ],
                ],
                'status' => 1,
            ],
            'riskinfo' => [
                'data' => [
                    'content' => [
                        'isRisk' => 'F',
                    ],
                ],
                'status' => 1,
            ],
        ],
        'tongdun' => [
            'tongdun' => [
                'data' => [
                    'final_decision' => 'Accept',
                    'final_score' => 0,
                ],
                'status' => 1,
            ],
        ],
        'black' => [
            'detect' => [
                'data' => [
                    'is_hit' => 0,
                ],
                'status' => 1,
            ],
            'grey_detect' => [
                'data' => [
                    'is_hit' => 0,
                ],
                'status' => 1,
            ],
        ],
    ];

    public $request_body = [
        'partner_id' => '110001',
        'params' => '',
        'version' => '1.0',
        'ts' => 1479435821,
        'sign' => 'XWQGZ4z62MSrg+IbufB0jUeOHGyvLRjLwCSsoVCdA8xo8zWLT9rsv/+K678g7/negE76yAwGQUhPAjVflJ94asx1f91Z2qJ4XhwMqx9pWFcMMaV9djla55OAumLatflfyFNR05eachUctdaGUySiLYzTnyPqGFmr+sIa/sY59xQ=',
    ];

    public $request_json = '{"scene":"h_zm_score_credit","user_info":{"user_id":"4054146","name":"庞龙","user_name":"庞龙","mobile":"18860009195","id_number":"331023198708083154","customer_id":"268813897533674036534166269","user_status":{"alipay_user_id":"2088712514113995","gender":"m","user_status":"T","user_type_value":"2","is_id_auth":"T","is_mobile_auth":"T","is_bank_auth":"T","is_student_certified":"F","is_certify_grade_a":"T","is_certified":"T","is_licence_auth":"F","cert_type_value":"0","account_id":"11682645","order_num":0,"hasPaid":0},"ip_address":"117.136.75.84","is_test":0,"registed_at":"2016-02-2202:46:33","user_type":"normal","order_type":"qudian","iou_limit":0,"alipay_user_id":"2088712514113995","token_id":"6d3712cf580d59fe07d85e93d9419bc9","bqs_token_key":"3005ecdef7f6f4176bbb0caefff4eb24","latitude":"24.532611","longitude":"118.157503","user_address":[{"prov":"福建省","city":"厦门市","area":"湖里区","mobile":"18860009195","name":"庞龙","address":"火炬高技术开发区安>岭路989号>裕隆国际大厦707室"}]},"partner_id":"10003","microtime":"0.783923001478677845","account_id":10546073}';

    public function setStubData($stub_datas) {
        foreach ($stub_datas as $index => $value) {
            $this->arraySet($this->stub_data, $index, $value);
        }
        return $this->stub_data;
    }

    public function setRequestData($request_datas) {
        $partner_id = null;
        if (isset($request_datas['partner_id'])) {
            $partner_id = $request_datas['partner_id'];
            unset($request_datas['partner_id']);
        }

        $request_arr = json_decode($this->request_json, true);
        foreach ($request_datas as $indexs => $value) {
            $this->arraySet($request_arr, $indexs, $value);
        }
        $this->request_body['params'] = json_encode($request_arr);
        if ($partner_id) {
            $this->request_body['partner_id'] = $partner_id;
        }
        return $this->request_body;

    }

    public function arraySet(&$arr, $index, $value) {
        $index_key = explode(".", $index);
        $this->recursiveSet($arr, $index_key, $value);
    }

    public function checkResult($diff_data, $check_data) {
        if (1 == $check_data['status']) {
            $check_data['data'] = json_decode($check_data['data'], true);
        }
        foreach ($diff_data as $index => $value) {
            $keys = explode('.', $index);
            $check_value = $this->recursiveGet($check_data, $keys);
            if ($value === $check_value) {
                continue;
            } else {
                return 1;
            }
        }
        return 0;
    }

    public function recursiveGet($arr, $keys) {
        $key = array_shift($keys);
        if (!isset($arr[$key])) {
            return null;
        } elseif (empty($keys)) {
            return $arr[$key];
        } else {
            $this->recursiveGet($arr[$key], $keys);
        }
    }

    public function recursiveSet(&$arr, $keys, $value) {
        $key = array_shift($keys);
        if (empty($keys)) {
            $arr[$key] = $value;
            return;
        } else {
            if (!isset($arr[$key])) {
                $arr[$key] = [];
            }
            $this->recursiveSet($arr[$key], $keys, $value);
        }
    }
}
