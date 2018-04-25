<?php
require_once(__DIR__ . '/config.php');

function __autoload($class_name) {
    require_once(__DIR__ . '/classes/' . $class_name . '.php');
}

$db = Database::getConnection();
$session = Session::getInstance($db);

header('Pragma: no-cache');
header('Expires: 0');

function createCSV($data) {
	if (!$file = fopen('php://temp', 'w+')) return FALSE;
	 
	// save the column headers
	fputcsv($file, array('Column 1', 'Column 2', 'Column 3', 'Column 4', 'Column 5'));
	 
	// Sample data. This can be fetched from mysql too
	$data = array(
	array('Data 11', 'Data 12', 'Data 13', 'Data 14', 'Data 15'),
	array('Data 21', 'Data 22', 'Data 23', 'Data 24', 'Data 25'),
	array('Data 31', 'Data 32', 'Data 33', 'Data 34', 'Data 35'),
	array('Data 41', 'Data 42', 'Data 43', 'Data 44', 'Data 45'),
	array('Data 51', 'Data 52', 'Data 53', 'Data 54', 'Data 55')
	);
	 
	// save each row of the data
	foreach ($data as $row)
	{
		fputcsv($file, $row);
	}
	 
	rewind($file);
	return stream_get_contents($file);
}

function send($data, $body, $to = 'mm11096@georgiasouthern.edu', $subject = 'Website Report', $from = 'noreply@carlofontanos.com') {

    // This will provide plenty adequate entropy
    $multipartSep = '-----'.md5(time()).'-----';

    // Arrays are much more readable
    $headers = array(
        "From: $from",
        "Reply-To: $from",
        "Content-Type: multipart/mixed; boundary={$multipartSep}"
    );

    // Make the attachment
    $attachment = chunk_split(base64_encode(createCSV($data))); 

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

var_dump(send(null, "Hello", "mitchell.murphy96@gmail.com", "Website Report", "noreply@carlofontanos.com"));

    $errorMessage = error_get_last()['message'];
    echo $errorMessage;
?>
