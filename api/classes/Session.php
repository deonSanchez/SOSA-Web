<?php
/**
 * Session control
 *
 * @category   Class
 * @package    com.SOSA-Web.classes.Session
 * @author     Mitchell M. <mm11096@georgiasouthern.edu>
 * @version    Release: 1.5.0
 * @since      Class available since Release 1.0.0
 */
require_once __DIR__ . '/../config.php';
class Session {
	private static $self_instance;
	public $sid;
	private $mysqli, $qb;

	/**
	 * Constructs the class, setting the mysqli variable to the active connection
	 * @param MySQLi DB Instance $dbc
	 * @author Mitchell M.
	 * @version 1.0.0
	 */
	public function __construct($dbc) {
		$this->mysqli = $dbc;
		$this->qb = QueryBuilder::getInstance();
		$this->sid = isset($_SESSION['sid']) ? $_SESSION['sid'] : null;
		if ($this->sid != null) {
			//Sets the current loggedIn status and validates any session in the browser
			$this->validate($this->sid, time());
		}
	}

	/**
	 * Static singleton instance is set only once, retrieved if already set
	 * @author Mitchell M.
	 * @param type $dbc
	 * @return type
	 * @version 1.0.0
	 */
	public static function getInstance($dbc) {
		if (!self::$self_instance) {
			self::$self_instance = new Session($dbc);
		}
		return self::$self_instance;
	}
	/**
	 * Pulls the mysqli accessor
	 * @return type Mysqli.
	 */
	function getDBC() {
		return $this->mysqli;
	}

	/**
	 * Pulls the logged-in user's session ID
	 * @return type string $sid
	 */
	function getSID() {
		return $this->sid;
	}

	/**
	 * Redirects the the specified location, if headers already sent and can't do with PHP it will do it with javascript.
	 * @param string $location to redirect to
	 * @author Mitchell M.
	 * @version 1.0.0
	 */
	function redirect($location) {
		if (!headers_sent())
		header('Location: ' . $location);
		else {
			echo '<script type="text/javascript">';
			echo 'window.location.href="' . $location . '";';
			echo '</script>';
			echo '<noscript>';
			echo '<meta http-equiv="refresh" content="0;url=' . $location . '" />';
			echo '</noscript>';
		}
		die();
	}

	/**
	 * Generates a random string based on the length provided
	 * @param int $length to use
	 * @author Mitchell M.
	 * @version 1.0.0
	 */
	function generateRandID($length) {
		$randstr = "";
		for ($i = 0; $i < $length; $i++) {
			$randnum = mt_rand(0, 61);
			if ($randnum < 10) {
				$randstr .= chr($randnum + 48);
			} elseif ($randnum < 36) {
				$randstr .= chr($randnum + 55);
			} else {
				$randstr .= chr($randnum + 61);
			}
		}
		return $randstr;
	}

	/**
	 * Checks input to see if it matches md5 patterns
	 * @param $md5 string
	 */
	function isValidMd5($md5 ='')
	{
		return preg_match('/^[a-f0-9]{32}$/', $md5);
	}

	/**
	 * Validates if a session cookie is valid, and clears it if not
	 * @author Mitchell M.
	 * @param string $sid
	 * @param int $currentTime unixtimestamp
	 * @return boolean
	 * @version 1.0.0
	 */
	function validate($sid, $currentTime) {
		$sid = htmlentities(mysqli_real_escape_string($this->mysqli, $sid));
		$stmt = $this->mysqli->prepare("SELECT timestamp, userid FROM `sessions` WHERE `sid` = ?");
		$stmt->bind_param("s", $sid);
		$stmt->bind_result($timestamp, $uid);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows >= 1) {
			while ($stmt->fetch()) {
				//stored timestamp is the logintime + allowed session length
				//checking to see if we have passed that time
				if ($currentTime > $timestamp) {
					$this->clear($sid);
					return false;
				} else {
					return true;
				}
			}
		} else {
			if (isset($_SESSION['sid'])) {
				$this->clear($sid);
			}
		}
		$stmt->close();
	}

	/**
	 * Manages sessions and prevents more than one session per user
	 * @param int $userid
	 * @author Mitchell M.
	 * @version 1.0.0
	 */
	public function handleSID($userid) {
		//Does a session already exist for this userID?
		if ($this->exists($userid)) {
			//Session exists, clear it...
			if (!$this->clearByUID($userid)) {
				//Couldnt clear the session, return a json element containing the error
				return json_encode("Couldn't clear SID when creating new session.");
			}
		}
		//Creates the session with the specific userid
		if ($this->buildSID($userid)) {
			return true;
		}
		return false;
	}

	/**
	 * Does a session exist for the UserID passed
	 * @author Mitchell M.
	 * @param int $userid
	 * @return boolean
	 * @version 1.0.0
	 */
	function exists($userid) {
		$qry = $this->qb->start();
		$qry->select("*")->from("sessions")->where("userid", "=", $userid);
		if ($qry->recordsExist()) {
			return true;
		}
		return false;
	}

	/**
	 * Creates a session entry into the database and on the client machine
	 * @author Mitchell M.
	 * @param int $userid
	 * @return boolean
	 * @version 1.0.0
	 */
	function buildSID($userid) {
		$sid = $this->generateRandID(16);
		$timestamp = $this->buildExpireTime();
		$qry = $this->qb->start();
		$qry->insert_into("sessions", array('userid' => $userid, 'sid' => $sid, 'timestamp' => $timestamp));
		if ($qry->exec()) {
			$_SESSION['sid'] = $sid;
			return true;
		}
		return false;
	}

	/**
	 * Builds the session management system's current expiration timestamp
	 * @author Mitchell M.
	 * @return type
	 * @version 1.0.0
	 */
	function buildExpireTime() {
		return time() + 60 * SESSION_LENGTH;
	}

	/**
	 * Clear session based on UserID
	 * @author Mitchell M.
	 * @param type $userid
	 * @return boolean
	 * @version 1.0.0
	 */
	function clearByUID($userid) {
		if ($this->mysqli->query("DELETE FROM sessions WHERE userid='{$userid}'")) {
			return true;
		} else {
			return $this->mysqli->error;
		}
		unset($_SESSION['sid']);
	}

	/**
	 * Clear session based on SID
	 * @author Mitchell M.
	 * @param type $sid
	 * @version 1.0.0
	 */
	function clear($sid) {
		$sid = mysqli_real_escape_string($this->mysqli, $sid);
		$this->mysqli->query("DELETE FROM sessions WHERE sid='{$sid}'");
		unset($_SESSION['sid']);
	}

	/**
	 * END SESSION MANAGEMENT FUNCTIONS
	 * BEGIN USER MANAGEMENT FUNCTIONS
	 */

	/**
	 * Registers the user into the database
	 * @param string $username
	 * @param string $password
	 * @param string $passwordconf
	 * @author Mitchell M.
	 * @version 1.0.0
	 */
	public function register($username, $password, $passwordconf) {
		$pass = md5($password);
		$passconf = md5($passwordconf);
		if (!$username) {
			$errors[] = "Username is not defined!";
		}
		if (!$pass) {
			$errors[] = "Password is not defined!";
		}
		if (!$passconf) {
			$errors[] = "Password confirmation is not defined!";
		}
		if ($passconf != $pass) {
			$errors[] = "The two passwords you entered do not match!";
		}
		if ($username) {
			$stmt = $this->mysqli->prepare("SELECT * FROM `users` WHERE `username`= ?");
				
			$stmt->bind_param("s", $username);
			echo $this->mysqli->error;
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$errors[] = "The username you supplied is already in use of another user!";
			}
			$stmt->close();
		}
		if (!isset($errors)) {
			//register the account
			$mysqli = $this->mysqli->prepare("INSERT INTO `users` (`username`, `password`) VALUES (?,?)");
			$mysqli->bind_param("ss", $username, $pass);
			$mysqli->execute();
			$mysqli->close();
			return true;
		} else {
			return json_encode($errors);
		}
	}

	/**
	 * Sets a users session in the database and sets their client side session
	 * @param string $username
	 * @param string $pass
	 * @author Mitchell M.
	 * @version 1.0.0
	 */
	function login($username, $pass) {
		$response = "Attempting login protocol";
		//Does the user exist?
		if ($this->userExists($username, $pass)) {
			$response = "Authentication valid, attempting to get UID and create session.";
			//User exists, get their userID for session creation
			$userid = $this->getUID($username);
			if ($this->handleSID($userid)) {
				return true;
			}
		} else {
			$response = false;
		}
		return json_encode($response);
	}

	/**
	 * Validates that the login details are valid
	 * @param string $username
	 * @param string $password
	 * @author Mitchell M.
	 * @version 1.0.0
	 */
	function userExists($username, $password) {
		$username = htmlspecialchars(mysqli_real_escape_string($this->mysqli, $username));
		$password = md5($password);
		$stmt = $this->mysqli->prepare("SELECT * FROM `users` WHERE `username` = ? AND `password` = ?");
		$stmt->bind_param("ss", $username, $password);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Returns the UID based on email/sid input
	 * Determines input type no specification required
	 * @author Mitchell M.
	 * @param type $input
	 * @return type
	 * @version 1.2.0
	 */
	function getUID($input) {
		$qry = $this->qb->start();
		$qry->select("userid");

		if ($this->isValidMd5($input)) {
			$qry->from("sessions")
			->where("sid", "=", $input);
			$result = $qry->get();
		} else {
				
			$qry->from("users")
			->where("username", "=", $input);
			$result = $qry->get();
		}
		return isset($result[0]['userid']) ? $result[0]['userid'] : -1;
	}


	/**
	 * Returns the UID based on email/sid input
	 * Determines input type no specification required
	 * @author Mitchell M.
	 * @param type $input
	 * @return type
	 * @version 1.2.0
	 */
	function getBoardID($input) {
		$qry = $this->qb->start();
		$qry->select("idboard");
		$qry->from("board")->where("board_name", "=", $input);
		$result = $qry->get();
		return isset($result[0]['idboard']) ? $result[0]['idboard'] : -1;
	}

	/**
	 * Returns if the board exists
	 * Determines input type no specification required
	 * @author Mitchell M.
	 * @param type $input id
	 * @return type
	 * @version 1.2.0
	 */
	function boardExists($input) {
		$qry = $this->qb->start();
		$qry->select("idboard");
		$qry->from("board")->where("idboard", "=", $input);
		$result = $qry->get();
		return isset($result[0]['idboard']);
	}

	/**
	 * Returns if the board exists
	 * Determines input type no specification required
	 * @author Mitchell M.
	 * @param type $input id
	 * @return type
	 * @version 1.2.0
	 */
	function validStimulusSet($input) {
		$qry = $this->qb->start();
		$qry->select("stimset_id");
		$qry->from("stimulus_set")->where("stimset_id", "=", $input);
		$result = $qry->get();
		if(isset($result[0]['stimset_id'])){
			$qry2 = $this->qb->start();
			$qry2->select("*");
			$qry2->from("stimulus")->where("stimset_id", "=", $input);
			$result2 = $qry2->get();
			if(count($result2) > 0)
			return true;
			else
			echo "Can't find set!";
		} else {
			echo "Can't find set!";
		}
		return false;
	}

	/**
	 * Is a user logged in?
	 * @author Mitchell M.
	 * @return type
	 * @version 1.0.0
	 */
	function isLoggedIn() {
		return isset($_SESSION['sid']);
	}



	/**
	 * Builds and saves experiment based on input $data
	 * @author Mitchell M.
	 * @return crated experiment
	 * @version 0.5.0
	 */
	public function createExperiment($idboard,$stimset_id,$title,$showbg,$showlabels,$preview) {
		$showbg = 1;
		$showlabels = 1;
		$preview = "null";
		if(!$this->boardExists($idboard)) {
			return "Board doesn't exist!";
		}

		if(!$this->validStimulusSet($stimset_id)){
			return "Not a valid stimulus set!";
		}
		$access = $this->generateRandID(15);
		$mysqli = $this->mysqli->prepare("INSERT INTO `experiment` (`title`,`stimset_id`,`idboard`,`show_background`,`show_labels`,`preview_img`,`access_key`) VALUES (?,?,?,?,?,?,?)");
		$mysqli->bind_param("siiiiss", $title,$stimset_id,$idboard,$showbg,$showlabels,$preview,$access);
		$mysqli->execute();
		$mysqli->close();
		return "Experiment created! Access ID = {$access}";
	}

	/**
	 * Deletes experiment based on input id $data
	 * @author Mitchell M.
	 * @version 0.5.0
	 */
	public function deleteExperiment($experiment_id) {
		$experiment_id = intval($experiment_id);
		if ($this->mysqli->query("DELETE FROM `experiment` WHERE `experiment_id`='{$experiment_id}'")) {
			return true;
		} else {
			return $this->mysqli->error;
		}
	}

	/**
	 * Deletes experiment based on input id $data
	 * @author Mitchell M.
	 * @version 0.5.0
	 */
	public function deleteBoard($board) {
		if ($this->mysqli->query("DELETE FROM `board` WHERE `idboard`='{$board}'")) {
			return true;
		} else {
			return $this->mysqli->error;
		}
	}

	/**
	 * Deletes stimuli based on input id $data
	 * @author Mitchell M.
	 * @version 0.5.0
	 */
	public function deleteStimulus($stimulus_id) {
		$stimulus_id = intval($stimulus_id);
		if ($this->mysqli->query("DELETE FROM `stimulus` WHERE `stimulus_id`='{$stimulus_id}'")) {
			return true;
		} else {
			return $this->mysqli->error;
		}
	}

	/**
	 * Deletes stimuli based on input id $data
	 * @author Mitchell M.
	 * @version 0.5.0
	 */
	public function deleteStimulusSet($stimset_id) {
		$stimset_id = intval($stimset_id);
		if ($this->mysqli->query("DELETE FROM `stimulus_set` WHERE `stimset_id`='{$stimset_id}'")) {
			return true;
		} else {
			return $this->mysqli->error;
		}
	}

	/**
	 * Updates stimuli based on input $stimulus_id and $label,$label_color,$peg_color
	 * @author Mitchell M.
	 * @return created stimulus
	 * @version 0.5.0
	 */
	public function updateStimulus($stimulus_id,$label,$peg_r,$peg_g,$peg_b) {
		$qry = $this->mysqli->prepare("UPDATE `stimulus` SET `label` = ?, `peg_r` = ?, `peg_g` = ?, `peg_b` = ? WHERE `stimulus_id` = ?");
		$qry->bind_param("siiii", $label,$peg_r,$peg_g,$peg_b,$stimulus_id);
		$qry->execute();
		$qry->close();
	}

	public function editBoard($board_name, $lock_tilt, $lock_rotate, $lock_zoom, $cover_board, $board_color, $background_color, $cover_color, $image,$camerax,$cameray,$cameraz){
		$image = "null";
		if($board_name == ""){
			return "You did not specify a board name!";
		}

		$stmt = $this->mysqli->prepare("SELECT * FROM `board` WHERE `board_name` = ?");
		$stmt->bind_param("s", $board_name);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows < 1) {
			return "Board doesn't exist!";
		}

		$board_id = $this->getBoardID($board_name);

		$qry = $this->mysqli->prepare("UPDATE `board`
	    								SET `board_name` = ?,`lock_tilt` = ?, `lock_rotate` = ?, `lock_zoom` = ?,
	    								`cover_board` = ?, `board_color` = ?, `background_color` = ?, `cover_color` = ?,
	    								 `camerax` = ?,`cameray` = ?,`cameraz` = ? WHERE `idboard` = ?");
		$qry->bind_param("siiiisssdddi",$board_name, $lock_tilt, $lock_rotate, $lock_zoom, $cover_board, $board_color, $background_color, $cover_color, $camerax,$cameray,$cameraz, $board_id);
		$qry->execute();
		$qry->close();
		return true;
	}

	/**
	 * Return an array of all available saved experiment details
	 * @author Mitchell M.
	 * @return array of stimuli
	 * @version 0.5.0
	 */
	public function loadExperiments() {
		$results = null;
		$stmt = $this->mysqli->query("SELECT * FROM `experiment`");
		if ($stmt->num_rows >= 1) {
			while ($row = $stmt->fetch_assoc()) {
				$results[] = $row;
			}
		}
		return $results;
	}

	/**
	 * Return an array of all available saved experiment details
	 * @author Mitchell M.
	 * @return array of stimuli
	 * @version 0.5.0
	 */
	public function loadExperiment($access) {
		$results = null;
		$stmt = $this->mysqli->prepare("SELECT `stimset_id`, `title`, `idboard` FROM `experiment` WHERE `access_key` = ?");
		$stmt->bind_param("s",$access);
		$stmt->bind_result($stimset_id, $title,$idboard);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows >= 1) {
			while ($stmt->fetch()) {
				$results[] = array('stimset_id' => $stimset_id, 'title' => $title, 'idboard' => $idboard);
			}
		}
		return $results;
	}

	/**
	 * Return an array containing the information regarding the stimulus set provided
	 * @author Mitchell M.
	 * @return array of stimuli
	 * @version 0.5.0
	 */
	public function loadStimulus($stimulus_id) {
		$stimulus_id = intval($stimulus_id);
		$qry = $this->qb->start();
		$qry->select("*")->from("stimulus")->where("stimulus_id", "=", $stimulus_id);
		$results = $qry->get();
		return $results;
	}

	/**
	 * Return an array of all available saved stimuli sets
	 * @author Mitchell M.
	 * @return array of stimuli
	 * @version 0.5.0
	 */
	public function loadStimSets() {
		$results = null;
		$stmt = $this->mysqli->prepare("SELECT `stimset_id`, `title` FROM `stimulus_set`");
		$stmt->bind_result($stimset_id,$title);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows >= 1) {
			while ($stmt->fetch()) {
				$results[] = array('stimset_id' => $stimset_id, 'title' => $title);
			}
		}
		return $results;
	}

	/**
	 * Return an array containing the information regarding the stimulus set provided
	 * @author Mitchell M.
	 * @return array of stimuli
	 * @version 0.5.0
	 */
	public function loadStimSet($stimset_id) {
		$results = null;
		$stimset_id = intval($stimset_id);
		$stmt = $this->mysqli->prepare("SELECT `stimulus_id`, `label`,`peg_r`,`peg_g`,`peg_b`,`label_r`,`label_g`,`label_b` FROM `stimulus` WHERE `stimset_id` = ?");
		$stmt->bind_param("i",$stimset_id);
		$stmt->bind_result($stimulus_id, $label,$peg_r,$peg_g,$peg_b,$label_r,$label_g,$label_b);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows >= 1) {
			while ($stmt->fetch()) {
				$results[] = array('stimulus_id' => $stimulus_id,'label' => $label, 'peg_r' => $peg_r,'peg_g' => $peg_g,'peg_b' => $peg_b, 'label_r' => $label_r,'label_g' => $label_g,'label_b' => $label_b);
			}
		}
		return $results;
	}
	
	public function lookupSetID($title) {
		$qry = $this->qb->start();
		$qry->select("stimset_id");
		$qry->from("stimulus_set")->where("title", "=", $title);
		$result = $qry->get();
		return isset($result[0]['stimset_id']) ? $result[0]['stimset_id'] : -1;
	}

	/**
	 * Return an array of all available saved stimuli sets
	 * @author Mitchell M.
	 * @return array of stimuli
	 * @version 0.5.0
	 */
	public function loadBoards() {
		$results = null;
		$stmt = $this->mysqli->prepare("SELECT `board_name`, `idboard` FROM `board`");
		$stmt->bind_result($board,$id);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows >= 1) {
			while ($stmt->fetch()) {
				$results[] = array('board_name' => $board, 'idboard' => $id);
			}
		}
		return $results;
	}

	/**
	 * Return an array of all available saved experiment details
	 * @author Mitchell M.
	 * @return array of stimuli
	 * @version 0.5.0
	 */
	public function loadBoard($board_id) {
		$results = array();
		if ($result = $this->mysqli->query("SELECT * FROM `board` WHERE `idboard` = {$board_id}")) {
		    while($row = $result->fetch_assoc()) {
		            $results[] = $row;
		    }
		}
		return $results;
	}
	
	/**
	 * Builds and saves stimuli based on input $data
	 * @author Mitchell M.
	 * @param $setid required
	 * @version 0.5.0
	 */
	public function createStimulus($label,$peg_r,$peg_g,$peg_b,$label_r,$label_g,$label_b,$set_title) {
		$setid = $this->lookupSetID($set_title);
		$temp = -1;
		$mysqli = $this->mysqli->prepare("INSERT INTO `stimulus` (`label`,`peg_r`,`peg_g`,`peg_b`,`label_r`,`label_g`,`label_b`, `stimset_id`) VALUES (?,?,?,?,?,?,?,?)");
		$mysqli->bind_param("siiiiiii",$label,$peg_r,$peg_g,$peg_b,$temp,$temp,$temp,$setid);
		$mysqli->execute();
		$mysqli->close();
		return true;
	}
	
	/**
	 * Builds and saves stimuli set based on input $version, $relative_size, and $window_size
	 * @author Mitchell M.
	 * @return $set id
	 * @version 0.5.0
	 */
	public function createStimulusSet($title, $version,$relative_size,$window_size) {
		$version = 1;
		$relative_size = 1;
		$window_size = 1;

		if(strlen($title) < 1) {
			return false;
		}

		$stmt = $this->mysqli->prepare("SELECT * FROM `stimulus_set` WHERE `title` = ?");
		$stmt->bind_param("s", $title);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			return false;
		}
		$mysqli = $this->mysqli->prepare("INSERT INTO `stimulus_set` (`title`, `version`,`relative_size`,`window_size`) VALUES (?,?,?,?)");
		$mysqli->bind_param("siii", $title,$version,$relative_size,$window_size);
		$mysqli->execute();
		$mysqli->close();
		return true;
	}
	
	/**
	 * BEGIN BOARD FUNCTIONS
	 * loading will be based on $board_name
	 * @author Dan Blocker <db04839@georgiasouthern.edu>
	 * @return
	 * @version 0.0.1
	 *
	 */
	public function saveBoard($board_name, $lock_tilt, $lock_rotate, $lock_zoom, $cover_board, $board_color, $background_color, $cover_color, $image,$camerax,$cameray,$cameraz){
		$image = "null";
		if($board_name == ""){
			return "You did not specify a board name!";
		}

		$stmt = $this->mysqli->prepare("SELECT * FROM `board` WHERE `board_name` = ?");
		$stmt->bind_param("s", $board_name);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			return "You cannot have two boards with the same name!";
		}

		$qry = $this->mysqli->prepare("INSERT INTO `board`
	    (`board_name`,`lock_tilt`, `lock_rotate`, `lock_zoom`, `cover_board`, `board_color`, `background_color`, `cover_color`, `image`, `camerax`,`cameray`,`cameraz`) 
	    VALUES 
	    (?,?,?,?,?,?,?,?,?,?,?,?)");
		$qry->bind_param("siiiissssddd",$board_name, $lock_tilt, $lock_rotate, $lock_zoom, $cover_board, $board_color, $background_color, $cover_color, $image,$camerax,$cameray,$cameraz);
		$qry->execute();
		$qry->close();
		return true;
	}

	public function saveBoardImage($board_name, $path) {
		$board_id = $this->getBoardID($board_name);
		if($result = $this->mysqli->query("UPDATE `board` SET `image` = '{$path}' WHERE `idboard` = '{$board_id}'")){
			echo "UPDATE `board` SET `image` = '{$path}' WHERE `idboard` = '{$board_id}'";
			return true;
		}
		else
		return $this->mysqli->error;
	}
}
?>