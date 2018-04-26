<?php
require_once(__DIR__ . '/config.php');

function __autoload($class_name) {
    require_once(__DIR__ . '/classes/' . $class_name . '.php');
}

$db = Database::getConnection();
$session = Session::getInstance($db);

header('Pragma: no-cache');
header('Expires: 0');

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

function send($columns,$rows, $body, $to = 'mm11096@georgiasouthern.edu', $subject = 'Website Report', $from = 'noreply@carlofontanos.com') {

    // This will provide plenty adequate entropy
    $multipartSep = '-----'.md5(time()).'-----';

    // Arrays are much more readable
    $headers = array(
        "From: $from",
        "Reply-To: $from",
        "Content-Type: multipart/mixed; boundary={$multipartSep}"
    );

    // Make the attachment
    $attachment = chunk_split(base64_encode(createCSV($columns,$rows))); 

    // Make the body of the message
    $body = "--$multipartSep\r\n"
        . "Content-Type: text/plain; charset=ISO-8859-1; format=flowed\r\n"
        . "Content-Transfer-Encoding: 7bit\r\n"
        . "\r\n"
        . "$body\r\n"
        . "--$multipartSep\r\n"
        . "Content-Type: text/csv\r\n"
        . "Content-Transfer-Encoding: base64\r\n"
        . "Content-Disposition: attachment; filename=\"Website-Report-\"" . date("F-j-Y") . ".csv"
        . "\r\n\r\n"
        . "$attachment\r\n"
        . "--$multipartSep--";

    // Send the email, return the result
    return @mail($to, $subject, $body, implode("\r\n", $headers)); 

}

$result = $session->getResults(33);
$log_columns = array_keys($result[1][0]);
$participant = $result[0]['identifier'];
$low_rows = count($result[1]);

var_dump(send($log_columns,$result[1], "Experiment results", "mitchell.murphy96@gmail.com", "Test results", "noreply@sosaproject.com"));
//
//    $errorMessage = error_get_last()['message'];
//    echo $errorMessage;


?>
