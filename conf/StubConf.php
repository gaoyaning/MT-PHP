<?php
/**
 * User: gaoyaning
 * Date: 17/3/3
 * Time: 下午5:41
 */
class StubConf
{
    public static function getConf() {
        return [
            "stub_ip" => "127.0.0.1",
            "stub_port" => "12345",
            "model_url" => "http://127.0.0.1:7306/risk/distribute",
        ];
    }
}
