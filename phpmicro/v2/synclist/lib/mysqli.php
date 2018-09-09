<?php

namespace Resgef\SyncList\Lib\Mysqli;
class QueryResult
{
    public $row;
    public $rows;
    public $num_rows;

    function __construct()
    {
    }
}

final class MySQLi
{
    private $link;

    private $prefix;

    const FETCH_ASSOC = 2;
    const FETCH_OBJ = 1;

    public function __construct($hostname, $username, $password, $database, $prefix)
    {
        $this->prefix = $prefix;
        $this->link = new \mysqli($hostname, $username, $password, $database);

        if ($this->link->connect_error) {
            trigger_error('Error: Could not make a database link (' . $this->link->connect_errno . ') ' . $this->link->connect_error);
            exit();
        }

        $this->link->set_charset("utf8");
        $this->link->query("SET SQL_MODE = ''");
    }

    public function table_name($table_name_without_prefix)
    {
        return "{$this->prefix}$table_name_without_prefix";
    }


    public function get_object($sql, $class_name = 'stdClass')
    {
        return $this->query($sql, self::FETCH_OBJ, $class_name);
    }

    /**
     * @param $sql
     * @param int $fetch_mode
     * @param string $class_name
     * @return bool|null|QueryResult
     */
    public function query($sql, $fetch_mode = self::FETCH_OBJ, $class_name = 'stdClass')
    {
        $query = $this->link->query($sql);

        if (!$this->link->errno) {
            if ($query instanceof \mysqli_result) {
                $data = array();

                if ($fetch_mode == self::FETCH_ASSOC) {
                    while ($row = $query->fetch_assoc()) {
                        $data[] = $row;
                    }
                } elseif ($fetch_mode == self::FETCH_OBJ) {
                    while ($row = $query->fetch_object($class_name)) {
                        $data[] = $row;
                    }
                }

                $result = new QueryResult();
                $result->num_rows = $query->num_rows;
                $result->row = isset($data[0]) ? $data[0] : (($fetch_mode == self::FETCH_OBJ) ? new $class_name() : array());
                $result->rows = $data;

                $query->close();

                return $result;
            } else {
                return true;
            }
        } else {
            trigger_error('Error: ' . $this->link->error . '<br />Error No: ' . $this->link->errno . '<br />' . $sql);
            return null;
        }
    }

    public function db_input($value)
    {
        return $this->escape($value);
    }

    public function escape($value)
    {
        return $this->link->real_escape_string($value);
    }

    public function countAffected()
    {
        return $this->link->affected_rows;
    }

    public function getLastId()
    {
        return $this->link->insert_id;
    }

    public function __destruct()
    {
        $this->link->close();
    }
}
