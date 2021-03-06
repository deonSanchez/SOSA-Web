<?php
/**
 * Session control
 *
 * @category   Class
 * @package    com.SOSA-Web.classes.Session
 * @author     Mitchell M. <mm11096@georgiasouthern.edu>
 * @version    Release: 2.0.0
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

	public function validEmail($email) {
		if (filter_var($email, FILTER_VALIDATE_EMAIL))
			return true;
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
	function getParticipantIdent($input) {
		$qry = $this->qb->start();
		$qry->select("identifier")->from("results")->where("result_id", "=", $input);
		$result = $qry->get();
		return isset($result[0]['identifier']) ? $result[0]['identifier'] : -1;
	}
	/**
	 * Returns the UID based on email/sid input
	 * Determines input type no specification required
	 * @author Mitchell M.
	 * @param type $input
	 * @return type
	 * @version 1.2.0
	 */
	function getExperimentID($input) {
		$qry = $this->qb->start();
		$qry->select("experiment_id")->from("results")->where("result_id", "=", $input);
		$result = $qry->get();
		return isset($result[0]['experiment_id']) ? $result[0]['experiment_id'] : -1;
	}
	
	
	/**
	 * Returns the UID based on email/sid input
	 * Determines input type no specification required
	 * @author Mitchell M.
	 * @param type $input
	 * @return type
	 * @version 1.2.0
	 */
	function getExperimentEmail($input) {
		$id = $this->getExperimentID($input);
		$qry = $this->qb->start();
		$qry->select("admin")->from("experiment")->where("experiment_id", "=", $id);
		$result = $qry->get();
		return isset($result[0]['admin']) ? $result[0]['admin'] : -1;
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
	public function updateStimulus($stimulus_id,$label,$peg_r,$peg_g,$peg_b,$label_r,$label_g, $label_b) {
		$qry = $this->mysqli->prepare("UPDATE `stimulus` SET `label` = ?, `peg_r` = ?, `peg_g` = ?, `peg_b` = ?, `label_r` = ?, `label_g` = ?, `label_b` = ? WHERE `stimulus_id` = ?");
		$qry->bind_param("siiiiiii", $label,$peg_r,$peg_g,$peg_b,$label_r,$label_g,$label_b,$stimulus_id);
		$qry->execute();
		$qry->close();
		return true;
	}

	/**
	 * 
	 * Edits a board based on board name
	 * @param unknown_type $board_name
	 * @param int $lock_tilt
	 * @param int $lock_rotate
	 * @param int $lock_zoom
	 * @param int $cover_board
	 * @param string $board_color
	 * @param string $background_color
	 * @param string $cover_color
	 * @param string $image
	 * @param int $camerax
	 * @param int $cameray
	 * @param int $cameraz
	 * @author Mitchell M.
	 * @version 0.5.0
	 */
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
	    								SET `lock_tilt` = ?, `lock_rotate` = ?, `lock_zoom` = ?,
	    								`cover_board` = ?, `board_color` = ?, `background_color` = ?, `cover_color` = ?,
	    								 `camerax` = ?,`cameray` = ?,`cameraz` = ? WHERE `idboard` = ?");
		$qry->bind_param("iiiisssdddi", $lock_tilt, $lock_rotate, $lock_zoom, $cover_board, $board_color, $background_color, $cover_color, $camerax,$cameray,$cameraz, $board_id);
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
		$stmt = $this->mysqli->prepare("SELECT `admin`, `stimset_id`, `title`, `idboard`,`experiment_id`, `grid` FROM `experiment` WHERE `access_key` = ?");
		$stmt->bind_param("s",$access);
		$stmt->bind_result($admin, $stimset_id, $title,$idboard,$experiment_id,$grid);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows >= 1) {
			while ($stmt->fetch()) {
				$results[] = array('admin'=>$admin, 'stimset_id' => $stimset_id, 'title' => $title, 'idboard' => $idboard, 'experiment_id' => $experiment_id, 'grid' => $grid);
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
	
	/**
	 * Gets the stimulus set ID from a stimulus_set title
	 * @author Mitchell Murphy <mm11096@georgiasouthern.edu>
	 * @return type $setid
	 * @version 1.0.0
	 *
	 */
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
		$stmt = $this->mysqli->prepare("SELECT `board_name`, `idboard`,`board_color`,`cover_color`,`background_color`,`camerax`,`cameray`,`cameraz`,`cover_board`,`lock_tilt`, `lock_rotate`, `lock_zoom`,`image` FROM `board`");
		$stmt->bind_result($board,$id,$board_color,
				$cover_color,$background_color,$camerax,$cameray,
				$cameraz,$cover_board,$lock_tilt,$lock_rotate,$lock_zoom, $image);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows >= 1) {
			while ($stmt->fetch()) {
				$results[] = array('board_name' => $board, 'idboard' => $id,'board_color' => $board_color,
				 'cover_color' => $cover_color,'background_color' => $background_color, 'camerax' => $camerax, 'cameray' => $cameray,
				 'cameraz' => $cameraz,`cover_board` => $cover_board,`lock_tilt` => $lock_tilt, `lock_rotate` => $lock_rotate, `lock_zoom` => $lock_zoom, 'image' => $image);
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
				return "Can't find any children stimulus!";
		} else {
			echo "Can't find a set with this ID!";
		}
		return false;
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
	function experimentExists($stim,$board) {
		$qry = $this->qb->start();
		$qry->select("experiment_id");
		$qry->from("board")->where("idboard", "=", $board)->where("stimset_id", "=", $stim);
		$result = $qry->get();
		return isset($result[0]['idboard']);
	}
	
	/**
	 * Builds and saves experiment based on input $data
	 * @author Mitchell M.
	 * @return crated experiment
	 * @version 0.5.0
	 */
	public function createExperiment($admin, $idboard,$stimset_id,$grid,$title,$showbg,$showlabels,$preview) {
		$showlabels = 1;
		$preview = "null";
		if(!$this->boardExists($idboard)) {
			return "Board doesn't exist!";
		}

		if(!$this->validStimulusSet($stimset_id)){
			return "Not a valid stimulus set!";
		}
		$access = $this->generateRandID(15);
		$mysqli = $this->mysqli->prepare("INSERT INTO `experiment` (`title`,`stimset_id`,`idboard`,`show_background`,`show_labels`,`preview_img`,`access_key`,`grid`,`admin`) VALUES (?,?,?,?,?,?,?,?,?)");
		$mysqli->bind_param("siiiissis", $title,$stimset_id,$idboard,$showbg,$showlabels,$preview,$access,$grid,$admin);
		$mysqli->execute();
		$mysqli->close();
		$this->sendAccess($access);
		return $access;
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
		$mysqli->bind_param("siiiiiii",$label,$peg_r,$peg_g,$peg_b,$label_r,$label_g,$label_b,$setid);
		$mysqli->execute();
		echo $this->mysqli->error;
		$mysqli->close();
		return true;
	}
	
	/**
	 * Builds and saves stimuli set based on input $version, $relative_size, and $window_size
	 * @author Mitchell M.
	 * @return $set id
	 * @version 0.5.0
	 */
	public function createStimulusSet($title, $version) {
		$version = 1;

		if(strlen($title) < 1) {
			return false;
		}

		$stmt = $this->mysqli->prepare("SELECT * FROM `stimulus_set` WHERE `title` = ?");
		$stmt->bind_param("s", $title);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			return "You cannot create two stimulus sets with the same name!";
		}
		$mysqli = $this->mysqli->prepare("INSERT INTO `stimulus_set` (`title`, `version`) VALUES (?,?)");
		$mysqli->bind_param("si", $title,$version);
		$mysqli->execute();
		$mysqli->close();
		return true;
	}
	
	/**
	 * Function that creates an entry in the database to represent a new board that can be loaded
	 * @author Dan Blocker <db04839@georgiasouthern.edu>
	 * @author Mitchell Murphy <mm11096@georgiasouthern.edu>
	 * @return type $boolean
	 * @version 1.0.0
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
	

	
	/**
	 * Function that creates an entry in the database to represent a new board that can be loaded
	 * @author Dan Blocker <db04839@georgiasouthern.edu>
	 * @author Mitchell Murphy <mm11096@georgiasouthern.edu>
	 * @return type $boolean
	 * @version 1.0.0
	 *
	 */
	public function createResults($uniq, $results, $experiment_id){
		/*
		 * 0 timestamp
		 * 1 stimulus id
		 * 2 stimulus name
		 * 3 positionx 
		 * 4 positiony
		 * 5 action
		 */
		
		$res_total = count($results);
		$array_rows = count($results[0]);
		$resultID = $this->createParentResult($experiment_id,$uniq);
		
		for($i = 0; $i < $res_total; $i++) {
			if(!$this->createResultRow($results[$i],$resultID))
				return "Failed to create result row!";
		}
		
		$this->mailResults($resultID,$experiment_id);
		return true;
	}
	
	public function mailResults($resultID,$experiment_id) {
		$logs = $this->getResults($resultID);
		$log_columns = array_keys($logs[1][0]);
		$participant = $logs[0]['identifier'];
		$email = $this->getExperimentEmail($resultID);
		$this->sendCSV($participant, $log_columns,$logs[1], 
			"Experiment results for participant identified as {$participant} // Result ID : {$resultID}", 
			$email, "SOSA RESULT: Test#: {$experiment_id} || Result#: {$resultID}", "noreply@sosaproject.com");
		return true;
	}
	
	function createCSV($columns,$rows) {
		if (!$file = fopen('php://temp', 'w+')) return FALSE;
		// save the column headers
		fputcsv($file, $columns);
		// save each row of the data
		foreach ($rows as $row)
		{
			fputcsv($file, $row);
		}
		rewind($file);
		return stream_get_contents($file);
	}
	
	function sendCSV($participant, $columns,$rows, $body, $to, $subject, $from) {
	
	    // This will provide plenty adequate entropy
	    $multipartSep = '-----'.md5(time()).'-----';
	
	    // Arrays are much more readable
	    $headers = array(
	        "From: $from",
	        "Reply-To: $from",
	        "Content-Type: multipart/mixed; boundary={$multipartSep}"
	    );
	
	    // Make the attachment
	    $attachment = chunk_split(base64_encode($this->createCSV($columns,$rows))); 
	
	    // Make the body of the message
	    $body = "--$multipartSep\r\n"
	        . "Content-Type: text/plain; charset=ISO-8859-1; format=flowed\r\n"
	        . "Content-Transfer-Encoding: 7bit\r\n"
	        . "\r\n"
	        . "$body\r\n"
	        . "--$multipartSep\r\n"
	        . "Content-Type: text/csv\r\n"
	        . "Content-Transfer-Encoding: base64\r\n"
	        . "Content-Disposition: attachment; filename=\"Test results\"" . date("F-j-Y") . ".csv"
	        . "\r\n\r\n"
	        . "$attachment\r\n"
	        . "--$multipartSep--";
	
	    // Send the email, return the result
	    return @mail($to, $subject, $body, implode("\r\n", $headers)); 
	}
	
	
	function array_pop_n(array $arr, $n) {
	    return array_splice($arr, 0, -$n);
	}
	
	/**
	 * Emails the access code link to the administrator who created the test, for distribution to participants
	 * Enter description here ...
	 * @param $access
	 */
	function sendAccess($access) {
		$url =  "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		$url = dirname(dirname($url));
		$experiment = $this->loadExperiment($access);
		$experiment = $experiment[0];
		$from = "noreply@sosaproject.com";
		$to = $experiment['admin'];
		$body = "<b>Experiment created successfully</b><p>You can access this experiment at : ". $url . "/SOSA.html?token=".$access . "</p>";
	    // This will provide plenty adequate entropy
	    $multipartSep = '-----'.md5(time()).'-----';
	
	    // Arrays are much more readable
	    $headers = array(
	        "From: $from",
	        "Reply-To: $from",
	        "Content-type: text/html; charset=iso-8859-1",
	    	"MIME-Version: 1.0"
	    );
	

	
	    // Make the body of the message
	    $body = "--$multipartSep\r\n"
	        . "Content-Type: text/plain; charset=ISO-8859-1; format=flowed\r\n"
	        . "Content-Transfer-Encoding: 7bit\r\n"
	        . "\r\n"
	        . "$body\r\n"
	        . "--$multipartSep\r\n";
	
	    // Send the email, return the result
	    return @mail($to, "SOSA EXPERIMENT: Experiment#" . $experiment['experiment_id']." created! Access link enclosed", $body, implode("\r\n", $headers)); 
	}
	
	public function createParentResult($experiment_id, $identifier) {
		$qry = $this->qb->start();
		$qry->insert_into("results", array('experiment_id' => $experiment_id, 'identifier' => $identifier));
		if ($qry->exec()) {
		
		$stmt = $this->mysqli->query("SELECT `result_id` FROM `results` ORDER BY `result_id` DESC LIMIT 1");
		if ($stmt->num_rows == 1) {
			while ($row = $stmt->fetch_assoc()) {
				$result = $row['result_id'];
			}
		}
//			$qry2 = $this->qb->start();
//			$qry2->select("result_id");
//			$qry2->from("results")->where("identifier", "=", $identifier)->where("experiment_id", "=", $experiment_id);
//			$result = $qry2->get();
//			$result = isset($result[0]['result_id']) ? $result[0]['result_id'] : -1;
			return $result;
		}
		return false;
	}
	
	public function createResultRow($row, $resultid) {
		$timestamp = $row[0];
		$stimulus_id = $row[1];
		$stimulus_name = $row[2];
		$to_x = $row[3];
		$to_y = $row[4];
		$action = $row[5];
		
		$qry = $this->qb->start();
		$data=array('results_id' => $resultid, 'timestamp' => $timestamp, 'stimulus_id' => $stimulus_id, 'stimulus_name' => $stimulus_name, 'to_x' => $to_x, 'to_y' => $to_y, 'action' => $action);
		if(isset($from_x) || isset($from_y)) {
			$data['from_x']=$from_x;
			$data['from_y']=$from_y;
		}
		$qry->insert_into("result_log", $data);
		if ($qry->exec()) {
			return true;
		}
		return false;
	}
	
	public function getResults($resultid) {
		$results = null;
		$identifier = $this->getParticipantIdent($resultid);
		$email = $this->getExperimentEmail($resultid);
		$stmt = $this->mysqli->prepare("SELECT `stimulus_name`, `timestamp`, `action`,`from_x`, `from_y`,`to_x`, `to_y` FROM `result_log` WHERE `results_id` = ?");
		$stmt->bind_param("i",$resultid);
		$stmt->bind_result($stimulus, $timestamp,$action,$from_x,$from_y,$to_x,$to_y);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows >= 1) {
			while ($stmt->fetch()) {
				$results[] = array('stimulus_name' => $stimulus, 'timestamp' => $timestamp, 'action' => $action, 'from_x' => $from_x, 'from_y' => $from_y, 'to_x' => $to_x, 'to_y' => $to_y);
			}
		}
		$results = array(array('identifier' => $identifier, 'email' => $email), $results);
		return $results;
	}

	public function uploadIMG($FILES) {
		$parts = explode(".", $_FILES['file']['name']);
		$path = dirname(__FILE__) . "/../../board_images/". $this->generateRandID(8) .".". $parts[1];
		
		return array(move_uploaded_file($_FILES['file']['tmp_name'], $path), $path);
	}
	
	/**
	 * Function that creates updates the board image that is saved for any specific board based on board_name
	 * @author Mitchell Murphy <mm11096@georgiasouthern.edu>
	 * @return type $boolean
	 * @version 1.0.0
	 *
	 */
	public function saveBoardImage($board_name, $path) {
		$parts = explode(".",$path);
		$filetype=$parts[1];
		$path = realpath($path);
		$board_id = $this->getBoardID($board_name);
		if($result = $this->mysqli->query("UPDATE `board` SET `image` = '{$path}' WHERE `idboard` = '{$board_id}'")){
			return array(true,$path);
		}
		else{
			return array(false,$this->mysqli->error);
		}
	}
}
?>