<?
// error reporting
error_reporting(E_ALL);
// set development IP addresses
$devIp = array("72.38.185.226","135.23.39.3");
ini_set('display_errors', in_array($_SERVER['REMOTE_ADDR'],$devIp)?1:0);

//default timezone
date_default_timezone_set('America/Toronto');

//Content type
header("Content-Type: text/html; charset=UTF-8");


//Site Info
//define('siteName',"Enter a site name");
//define('siteAuthor',"A site author, usually the client name");
//define('siteCopyright',"2014-".date('Y')." ".siteAuthor);
//define('siteUrl',"http://yoursitedomain.com/");
//define('siteUrlSecure',"https://yoursecuresitedomain.ca/");
//define('siteIndex',siteUrl.'sitemap.php'); // Make sure your sitemap.php is current before uncommenting
//define('siteFavicon','//yoursite.com/images/favicon.png');
//define('defaultEmail','Common client email');

//social links
//define("facebookUrl",'https://www.facebook.com/TheDunhamGroup');
//define("twitterUrl",'https://twitter.com/TheDunhamGroup');
//define("gplusUrl",'https://plus.google.com/1234567890');
//define("linkedinUrl",''); //commented out cause of no value, in emailer we check if defined
//define("youtubeUrl",'http://www.youtube.com/TheDunhamGroup');

define('logFile',$_SERVER['DOCUMENT_ROOT'].'/assets/logs/errorLog.php');

// Start our session
session_start();


//Default meta - though every page should have its own description
$metaTags['keywords'] = '';
$metaTags['title'] = '';
$metaTags['description'] = '';

//Database connection
	require $_SERVER['DOCUMENT_ROOT'].'/plugins/ZebraDatabase/Zebra_Database.php';
	$db = new Zebra_Database();
	// turn debugging on
	$db->debugger_ip = $devIp;
	//should turn off on production as it can get resource intensive
	$db->debug = true;
	$db->connect('localhost', 'user', 'password', 'database');
	$db->set_charset( 'utf8', 'utf8_general_ci' ); // Prevent weird characters from happening with French entries in database

//PHPMailer
	require $_SERVER['DOCUMENT_ROOT'].'/plugins/PHPMailer/phpmailer_required_functions.php';

set_error_handler("customError"); // Send errors to custom errorReporting.php handler

?>