<?php

/**
 * Created by PhpStorm.
 * User: gaoyaning
 * Date: 17/3/8
 * Time: 下午4:45
 */
class DataBase
{
    public static $db_conf= [
        'riskmodel' => [
            'host' => '192.168.1.20',
            'database' => 'server_partner',
            'user' => 'dev',
            'password' => 'qufenqi123!@#',
            'port' => '3306',
        ],
    ];
    public static $default_connection = "riskmodel";
    public static function getDataBase($connection) {
        if (null == $connection) {
            $connection = self::$default_connection;
        }
        return self::$db_conf[$connection];
    }
}