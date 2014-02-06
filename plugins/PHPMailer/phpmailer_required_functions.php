<?php
	//if these aren't in the standard library
	
	
	/* PHP Mailer wrapper */
	function phpMailer($to="",$from="",$cc="",$bcc="",$subject="",$textBody="",$htmlBody="",$attach="",$readReceipt=false){
	/*	var_dump($to)."<p>"; var_dump($from)."<p>";	var_dump($cc)."<p>"; var_dump($bcc)."<p>"; var_dump($subject)."<p>"; var_dump($textBody)."<p>";	var_dump($htmlBody)."<p>";		var_dump($readReceipt)."<p>";
		exit();*/

		require_once("class.phpmailer.php");
		$mailer = new PHPMailer(); 
		$mailer->CharSet = 'UTF-8';
		$mailer->IsMail();
		foreach(explode(',',$to) as $val){
			$mailer->AddAddress($val,$val);
		}
		$mailer->SetFrom($from,$from);
		$mailer->AddReplyTo($from,$from);
		if(!empty($cc)):
			foreach(explode(',',$cc) as $val){
				$mailer->AddCC($val,$val);
			}			
		endif;
		if(!empty($bcc)):
			foreach(explode(',',$bcc) as $val){
				$mailer->AddBCC($val,$val);
			}			
		endif;
		if(!empty($attach)):
			foreach($attach as $val){
				// form posted attachments, note that validation for filesize and type should be done beforehand
				if(is_array($val)):
					$mailer->AddAttachment($val['tmp_name'],$val['name']);
				else:
					$mailer->AddAttachment($val, basename($val));
				endif;
			}
		endif;
		
		if($readReceipt)
			$mailer->ConfirmReadingTo = $from;
		$mailer->Subject = $subject;
		$mailer->AltBody = $textBody==""?_html2txt(_stripStartEndStr($htmlBody)):$textBody; 
		$mailer->MsgHTML($htmlBody);
		if(!$mailer->Send())
			return array("state"=>false,"msg"=>"Mailer Error: " . $mailer->ErrorInfo);
		else
			return array("state"=>true,"msg"=>"Message sent");			    
	}	
	
	function _stripStartEndStr($str,$startTag='<style>',$endTag='</style>') {
	  //http://blog.worxware.com/blog1.php/2010/05/31/stripping-html-code-for-altbody-and-phpmailer-fe
	  /* Copyright Andy Prevost */
	  $startTag = strtolower($startTag);
	  $endTag = strtolower($endTag);
	  $lower_contents = strtolower($str);
	  // determine if a $startTag tag exists and process if necessary
	  do { $posStart = strpos($lower_contents,$startTag);
		if ( $posStart !== false ) {
		  $posEndStart = strpos($lower_contents, $endTag);
		  $posEnd = $posEndStart + strlen($endTag) + 1;
		  $posEnd = $posEnd - $posStart;
		  // return stripped out tags and contents
		  $strPart = substr($str, $posStart, $posEnd);
		  $str = str_replace($strPart,'',$str);
		}
	  } while (0);
	  return $str;
	} 
	
	function _html2txt($html) {
		/* Copyright Andy Prevost */
		if (trim($html)=='') { return $html; }
		$text = htmlspecialchars_decode($html);
		$text = str_replace("</table>", "</TABLE>", $text);
		do { if (strpos($text," </TABLE>")) { $text = str_replace(" </TABLE>", "</TABLE>", $text); } else { break; } } while (0);
		do { if (strpos($text,">\n\n")) { $text = str_replace(">\n\n", ">\n", $text); } else { break; } } while (0);
		$text = str_replace(">\n", ">", $text);
		$text = str_replace("</tr>", "</TR>", $text);
		$text = str_replace("</td>", "</TD>", $text);
		$text = str_replace("</th>", "</TH>", $text);
		$text = str_replace("</TD></TR>", "\n", $text);
		$text = str_replace("</TH></TR>", "\n", $text);
		$text = str_replace("</TD>", ": ", $text);
		$text = str_replace("</TH>", ": ", $text);
		$text = str_replace("</TR>", "\n", $text);
		$text = strip_tags($text);
		return $text;
	}
?>