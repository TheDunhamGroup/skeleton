<?
//Email record
function formDump(){
	ob_start();
	echo '<h2>Form</h2><table cellpadding="4" cellspacing="1" border="1" width="600"><thead><tr><th>Field</th><th>Value</th></tr></thead><tbody>';
	foreach($_POST as $field=>$value){
		echo '<tr><td>'.$field.'</td><td>'.$value.'</td></tr>';
	}
	echo '</tbody></table>';
	$content = ob_get_contents();	
	ob_end_clean();		
	return $content;
};
function serverDump(){
	ob_start();
	echo '<h2>Server</h2><table cellpadding="4" cellspacing="1" border="1" width="600"><thead><tr><th>Field</th><th>Value</th></tr></thead><tbody>';
	foreach($_SERVER as $field=>$value){
		echo '<tr><td>'.$field.'</td><td>'.$value.'</td></tr>';
	}
	echo '</tbody></table>';
	$content = ob_get_contents();	
	ob_end_clean();		
	return $content;
};

//Error reporting
function customError($errno,$errstr,$errfile,$errline){
	global $siteName;
	global $siteDomain;
	if($errno == 256):
		$errorType = 'Fatal';
	elseif($errno == 2 || $errno == 512):
		$errorType = 'Warning';
	elseif($errno == 8 || $errno == 1024):
		$errorType = 'Notice';
	else:
		$errorType = 'Unknown';
	endif;
	$errorMsg = "($errorType) $errstr \n on line $errline in file $errfile";
	if($_SERVER['REMOTE_ADDR'] == "72.38.185.226") echo $errorMsg;
	//Send email report
	$mailTo = "schuylerj@thedunhamgroup.com";
	$mailFrom = "error@$siteDomain";
	$mailCc = "";
	$mailBcc = "";
	$mailSubject = "Error on $siteName website";
	$mailText = "";
	$mailHtml = $errorMsg;
	$mailHtml .= formDump();
	$mailHtml .= serverDump();
	phpMailer($mailTo,$mailFrom,$mailCc,$mailBcc,$mailSubject,$mailText,$mailHtml);
}
//set error handler
set_error_handler("customError");
?>