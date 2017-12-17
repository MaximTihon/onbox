<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 22.10.17
 * Time: 19:31
 */

namespace onbox\block\vs;

use onbox\block\service\sInitBase;


class Model {

    public static $class;
    public static $table = null;

    public static function __callStatic($name, $arguments) {

        self::$class = 'onbox\block\model\\'.$name;
        self::$table = strtolower($name);
        self::$class = new self::$class();

        return self::$class;
    }


    private static function initBD() {

        $DataBD = sInitBase::parseDataDB();

        try {
            $options = array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

            $db = new \PDO('mysql:host=' . $DataBD['localhost'] . ';dbname=' . $DataBD['db'],
                $DataBD['user'], $DataBD['password'], $options);

            return $db;

        } catch (\PDOException $e) {

             die('BD:'. $e->getMessage());
        }
    }

    public function get($add_request = null) {

        $db = self::initBD();

        if($add_request == null) {

            $query = 'SELECT * FROM `'.self::$table.'`';

        } else {

            $query = 'SELECT * FROM `'.self::$table.'` WHERE '.$add_request;
        }


        $q =  $db->prepare($query);
        $q->execute();

        $row = $q->fetchAll(\PDO::FETCH_ASSOC);

        if(false === $row) {

            $error = $db->errorInfo();

            die("SELECT: SQL Error={$error[0]}, DB Error={$error[1]}, Message={$error[2]} \n");

        } else {

            return $row;
        }

    }

    public function set($arr = array(), $flag = false) {

        $db = self::initBD();

        $q =  $db->query('SELECT * FROM `'.self::$table.'`');

        if(false === $q) {

            $error = $db->errorInfo();

            die("SELECT: SQL Error={$error[0]}, DB Error={$error[1]}, Message={$error[2]} \n");

        }

        $row = $q->fetch(\PDO::FETCH_ASSOC); // !!!не дастоет если пустая таблица

        if($arr) {

            if(!$row){

                $list = $arr;

            } else {

                $list = array_intersect_key($arr, $row);
            }

            $params = array_values($list);

            $place_holders = implode(',', array_fill(0, count($params), '?'));

        }

            $stmt = $db->prepare('INSERT INTO `' . self::$table . '` (' . implode(', ', array_keys($list)) . ') VALUES (' . $place_holders . ')');

            $result = $stmt->execute($params);


        if(false === $result) {

            $error = $db->errorInfo();

            die("INSERT: SQL Error={$error[0]}, DB Error={$error[1]}, Message={$error[2]} \n");

        } else {

            if(false === $flag) {

                return $result;

            } else {

                return $db->lastInsertId();
            }
        }
   }

    public function updata( $arr = array(), $add_request = null) {

       $db = self::initBD();

       $q =  $db->query('SELECT * FROM `'.self::$table.'`');

        if(false === $q) {

            $error = $db->errorInfo();

            die("SELECT: SQL Error={$error[0]}, DB Error={$error[1]}, Message={$error[2]} \n");

        }

       $row = $q->fetch(\PDO::FETCH_ASSOC);

        if(!$row){

            $list = $arr;

        } else {

            $list = array_intersect_key($arr, $row);
        }

       foreach ((array)$list as $k => $v) {

           $str .= $k.'= \''.$v.'\',';
       }

       $item = substr($str, 0 , -1);

         if($add_request == null) {

             $result = $db->exec( 'UPDATE `'.self::$table.'` SET '.$item);

         } else {

             $result = $db->exec( 'UPDATE `'.self::$table.'` SET '.$item.' WHERE '.$add_request);

         }

        if(false === $result) {

            $error = $db->errorInfo();

            die("UPDATE: SQL Error={$error[0]}, DB Error={$error[1]}, Message={$error[2]} \n");

        } else {

             return $result;
        }
    }

    public function del($add_request = null) {

        $db = self::initBD();

            if(null == $add_request) {

                $result = $db->exec('DELETE FROM '.self::$table);

            } else {

                $result = $db->exec('DELETE FROM '.self::$table.' WHERE '.$add_request);
            }


        if($result == false) {

            $error = $db->errorInfo();

            die("DELETE: SQL Error={$error[0]}, DB Error={$error[1]}, Message={$error[2]} \n");
        }
    }

    public function query($query) {

        $db = self::initBD();

       $result = $db->query($query);

       if(false === $result) {

           $error = $db->errorInfo();

           die('ERROR MySQL:'.print_r($error));

       } else {

           return $result;
       }
    }

    public static function getModel() {

        $my_class = get_class(self::$class);

        if(class_exists($my_class)) {

            $my_obj = new self::$class;

            return get_object_vars($my_obj);
        }
    }

}