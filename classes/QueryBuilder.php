<?php

require_once __DIR__ . '/config.php';

/**
 * BASIC QUERY BUILDER
 * @Author Mitchell Murphy
 * @Author_Email mitchell.murphy96@gmail.com
 * 
 * Standard for this file
 * 
 * After every modification to query string, buffer spacing left at end of string for next update.
 * 
 * @SUPPORTED QUERIES:
 * SELECT, UPDATE, DELETE, INSERT
 * 
 * @version 1.0.0
 */
class QueryBuilder {

    private $query;
    private $state;
    private $db;
    private $firstWhere, $firstSet;
    private $qryType;

    /**
     * Creates a new instance with a connection to the database
     */
    function __construct() {
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $this->state = 0;
        $this->firstWhere = false;
    }

    /**
     * Returns an instance
     * @return type $instance of QueryBuilder
     */
    public static function getInstance() {
        return new QueryBuilder();
    }

    /**
     * Begins a new instance of the querybuilder
     * @return \QueryBuilder
     */
    public function start() {
        return new QueryBuilder();
    }

    /**
     *  increment of state
     */
    function transition($qryType = NULL) {
        if (isset($qryType)) {
            $this->qryType = $qryType;
        }
        $this->state++;
    }

    /**
     * Cleans inputs
     * @param type $input
     * @return type cleaned $input as $output
     */
    function clean($input) {
        if (is_array($input)) {
            $output = $input;
            foreach ($input as $key => $val) {
                $key = mysqli_real_escape_string($this->db, $key);
                $val = mysqli_real_escape_string($this->db, $val);
                $output[$key] = $val;
            }
        } else {
            $output = htmlspecialchars(mysqli_real_escape_string($this->db, $input));
        }
        return $output;
    }

    /**
     * Draws out beginning of SELECT statement, depending on WHAT target to select and from WHREE
     * @param String/Array $what fields to select from table
     * @param String $where tablename
     * @return String $this->query 
     */
    function select($column) {
        $column = $this->clean($column);
        if ($this->state == 0) {
            if ($column != "*") {
                if (!is_array($column)) {
                    $this->query .= "SELECT `" . $column . "` ";
                } else {
                    $this->query .= "SELECT ";
                    $numtargets = count($column);
                    for ($i = 0; $i < $numtargets; $i++) {
                        if ($i < ($numtargets - 1)) {
                            $this->query .= "`" . $column[$i] . "`, ";
                        } else
                            $this->query .= "`" . $column[$i] . "` ";
                    }
                }
            } else {
                $this->query .= "SELECT " . $column . " ";
            }
        } else {
            throw new Exception("Select MUST be called first.");
        }
        $this->transition("SELECT");
        return $this;
    }

    /**
     * 
     * @param type $table to update
     * @return $this
     */
    function update($table) {
        $table = $this->clean($table);
        if ($this->state == 0) {
            $this->query .= "UPDATE `" . $table . "` ";
            $this->transition("UPDATE");
        } else {
            throw new Exception("Update MUST be called first.");
        }
        return $this;
    }

    /**
     * Draws the rest of the UPDATE query to set $column to $toVal
     * @param type $column
     * @param type $toVal
     * @return $this
     * @throws Exception
     */
    function set($column, $toVal) {
        $column = $this->clean($column);
        $toVal = $this->clean($toVal);
        if ($this->state > 0 && $this->qryType == "UPDATE") {
            if (!is_array($column) && !is_array($toVal)) {
                if (!$this->firstSet) {
                    $this->query .= "SET `" . $column . "` = '" . $toVal . "' ";
                    $this->firstSet = true;
                } else {
                    $this->query .= ",`" . $column . "` = '" . $toVal . "' ";
                }
            } else {
                $columnC = count($column);
                $varC = count($toVal);
                if ($columnC == $varC) {
                    $this->query .= "SET ";
                    for ($i = 0; $i < $varC; $i++) {
                        if ($i > 0)
                            $this->query .= ", ";
                        $this->query .= "`" . $column[$i] . "` = '" . $toVal[$i] . "' ";
                    }
                }
            }
            $this->transition();
        } else {
            throw new Exception("Set MUST be called AFTER an Update");
        }
        return $this;
    }

    /**
     * Begins an insert into $table query, takes an 1D array of colum names and values
     * @param type $table
     * @param type $values
     * @return $this
     * @throws Execption
     */
    function insert_into($table, $values) {
        $table = $this->clean($table);
        $values = $this->clean($values);
        if ($this->state == 0) {
            $this->query .= "INSERT INTO `" . $table . "` ";
            $n = count($values);
            $iteration = 0;
            $this->query .= "( ";
            $newVals = array();
            foreach ($values as $key => $val) {
                $newVals[] = $val;
                if ($iteration == ($n - 1))
                    $this->query .= "`" . $key . "`";
                else
                    $this->query .= "`" . $key . "`, ";
                $iteration++;
            }
            $this->query .= ") VALUES ( ";
            $iteration = 0;
            for ($i = 0; $i < $n; $i++) {
                if ($iteration == ($n - 1))
                    $this->query .= "'" . $newVals[$i] . "'";
                else
                    $this->query .= "'" . $newVals[$i] . "', ";
                $iteration++;
            }
            $this->query .= ");";
        } else {
            throw new Execption("INSERT INTO must be called first.");
        }
        return $this;
    }

    /**
     * Applies a limit to the end of the query
     * @param type $n
     * @return $this
     */
    function limit($n) {
        if ($this->state > 0) {
            $this->query .= "LIMIT " . $n;
        }
        return $this;
    }

    /**
     * Begins drawing a DELETE FROM query
     * @param type $table
     * @return $this
     */
    function delete_from($table) {
        $table = $this->clean($table);
        if ($this->state == 0) {
            $this->query .= "DELETE FROM `" . $table . "` ";
        }
        $this->transition();
        return $this;
    }

    /**
     * FROM
     */
    function from($table) {
        $table = $this->clean($table);
        if ($this->state == 1) {
            $this->query .= "FROM `" . $table . "` ";
        }
        $this->transition();
        return $this;
    }

    /**
     * Appends the WHERE clause to SELECT statement
     * @param String $field to check against
     * @param String $comparison operator
     * @param String $target value
     * @return string $this->query
     */
    function where($field, $comparison, $target) {
        $allowedComparisons = array('LIKE', '=', '>', '<', '<=', '>=');
        if (!in_array($comparison, $allowedComparisons)) {
            return "Failed to provide correct comparison";
        }
        if ($comparison == "LIKE") {
            $target = "%" . $target . "%";
        }
        $allowedTypes = array('SELECT', 'UPDATE');
        if ($this->state > 0 && in_array($this->qryType, $allowedTypes)) {
            if ($target == "__NOW") {
                $target = "NOW()";
                if (!$this->firstWhere) {
                    $this->query .= "WHERE `" . $field . "` " . $comparison . " " . $target . " ";
                } else {
                    $this->query .= "AND `" . $field . "` " . $comparison . " " . $target . " ";
                }
            } else {
                if (!$this->firstWhere) {
                    $this->query .= "WHERE `" . $field . "` " . $comparison . " '" . $target . "' ";
                } else {
                    $this->query .= "AND `" . $field . "` " . $comparison . " '" . $target . "' ";
                }
            }
        }
        $this->transition();
        $this->firstWhere = true;
        return $this;
    }

    /**
     * Will return 2D array if more than one record, otherwise the single record will be contained in a 1D array
     * Array indexes correspond to MySQL columns
     * @return type
     */
    function get() {
        $ret = null;
        $query_result = $this->db->query($this->query);
        while ($row = $query_result->fetch_assoc()) {
            $ret[] = $row;
        }
        $query_result->free();
        return $ret;
    }

    /**
     * Returns the num rows
     * @return numRows int
     */
    function numRows() {
        $query_result = $this->db->query($this->query);
        return $query_result->num_rows;
    }

    /**
     * Do records exist with the current query?
     * @return type
     */
    function recordsExist() {
        $query_result = $this->db->query($this->query);
        return $query_result->num_rows > 0;
    }

    /**
     * Runs the stored query
     * @return type
     */
    function exec() {
        $query = $this->db->query($this->query);
        return $query;
    }

    /**
     * Returns the last error recorded
     * @return type
     */
    function lastError() {
        return $this->db->error;
    }

    /**
     * Trims the whitespace buffering from the query
     * @return String $this->query
     */
    function retrieve() {
        return trim($this->query);
    }

}