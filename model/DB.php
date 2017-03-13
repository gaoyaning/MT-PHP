<?php
/**
 * User: gaoyaning
 * Date: 17/3/8
 * Time: 下午4:39
 */
require_once __DIR__ . "/../conf/DataBase.php";
abstract class DB {
    protected $_DB;
    public $connection = null;
    public $table_name = null;
    public $columns = [];

    public function __construct() {
        // set table_name
        if (null == $this->table_name) {
            $this->table_name = __CLASS__;
            // 驼峰命名发
            $this->table_name = preg_replace_callback(
                "/(_([a-z]))/",
                function($match){
                    return strtoupper($match[2]);
                },
                $this->table_name
            );
            if ("s" == substr($this->table_name, -1)) {
                $this->table_name = $this->table_name . "es";
            } else if ("y" == substr($this->table_name, -1)) {
                $this->table_name = substr($this->table_name, 0, -1) . "ies";
            } else {
                $this->table_name = $this->table_name . "s";
            }
        }
    }

    public function clear() {
        $db_conf = DataBase::getDataBase($this->connection);
        $this->_DB = mysqli_connect($db_conf['host'], $db_conf['user'], $db_conf['password'], $db_conf['database'], $db_conf['port']);
        $sql = "delete from $this->table_name";
    }

    public function close() {
        $this->_DB->close();
    }
    
    public function insert($sql) {
        $this->getTableProperty();
        print_r($sql);
        $insert_sql = "insert into ".$this->table_name."(";
        $values = "values(";
        foreach ($sql as $key => $value) {
            $insert_sql = $insert_sql . $key . ",";
            $values = $values . $this->resetValue($key, $value) . ",";
        }
        $insert_sql = substr($insert_sql, 0, -1) . ")";
        $values = substr($values, 0, -1) . ")";
        $insert_sql = $insert_sql . " " . $values;
        mysqli_query($this->_DB, $insert_sql);
    }
    
    public function modify($sql) {
        $db_conf = DataBase::getDataBase($this->connection);
        $this->_DB = mysqli_connect($db_conf['host'], $db_conf['user'], $db_conf['password'], $db_conf['database'], $db_conf['port']);
        $this->getTableProperty();
        $update_sql = "update " . $this->table_name . " ";
        $set_sql = " set ";
        $where_sql = " where ";
        foreach ($sql as $type => $values) {
            if ("set" == $type) {
                foreach ($values as $k => $v) {
                    $set_sql = $set_sql . $k . "=" . $this->resetValue($k, $v) . ",";
                }
            } elseif ("where" == $type) {
                foreach ($values as $k => $v) {
                    $where_sql = $where_sql . $k . "=" . $this->resetValue($k, $v) . ",";
                }
            }
        }
        $set_sql = substr($set_sql, 0, -1);
        $where_sql = substr($where_sql, 0, -1);
        $update_sql = $update_sql . $set_sql . $where_sql;
        mysqli_query($this->_DB, $update_sql);
        $this->close();
    }

    public function getTableProperty() {
        $this->columns = [];
        $sql = "show columns from $this->table_name";
        $res = mysqli_query($this->_DB, $sql);
        if ($res) {
            while ($column = mysqli_fetch_assoc($res)) {
                $this->columns[$column['Field']] = $column['Type'];
            }
        }
    }

    public function resetValue($k, $v) {
        list($type) = explode("(", $this->columns[$k]);
        if ("int" == $type || "tinyint" == $type) {
            return $v;
        } else {
            return "'".$v."'";
        }
    }
}
