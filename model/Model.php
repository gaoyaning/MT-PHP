<?php

require_once("DB.php");
/**
 * User: gaoyaning
 * Date: 17/3/8
 * Time: 下午5:19
 */
class Model extends DB {

    public function flush($db_mock) {
        foreach ($db_mock as $connect => $db_info) {
            $this->connection = $connect;
            foreach ($db_info as $table_name => $sqls) {
                $this->table_name = $table_name;
                $this->clear();
                foreach ($sqls as $sql) {
                    $this->insert($sql);
                }
                $this->close();
            }
        }
    }

    public function update($db_mock) {
        foreach ($db_mock as $connect => $db_info) {
            $this->connection = $connect;
            foreach ($db_info as $table_name => $sqls) {
                $this->table_name = $table_name;
                foreach ($sqls as $sql) {
                    $this->modify($sql);
                }
            }
        }
    }
}
/*
$model = new Model();
$arr = [
    'riskmodel' => [
        'partners' => [
            0 => [
                'set' => [
                    'status' => 0,
                    'payment_validate_url' => 'http://www.gyning.com',
                ],
                'where' => [
                    'id' => 100022,
                ],
            ],
        ],
    ],
];
$insert = [
    'riskmodel' => [
        'partners' => [
            '0' => [
                'id' => 1234,
                'name' => "gyning",
            ],
        ],
    ],
];
$model->update($arr);
*/
