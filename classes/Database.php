<?php
/**
 * Database access
 *
 * @category   Class
 * @author     Mitchell M.
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Database {
	private static $mysqli;
	public static function getConnection() {
		if (!self::$mysqli)
		self::$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die('Failed to connect to MySQL Database');
		return self::$mysqli;
	}
}
?>