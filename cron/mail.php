<?
	function _MailCP($text, $charset)
	{
		return '=?'.$charset.'?B?'.base64_encode($text).'?=';
	}
	
	function _MailAddress($address, $charset)
	{
		
		$result = '';
		$c = 0;
		for ($i = 0; $i < sizeof($address); $i++)
		{
			$address[$i][0] = trim($address[$i][0]);
			if ($address[$i][0] != '')
			{
				
				if ($c != 0)
				{
					$result = $result.', ';
				}
				if ($address[$i][1] == '')
				{
					$result = $result.$address[$i][0]; 						
				}else{
					$result = $result._MailCP($address[$i][1], $charset).'<'.$address[$i][0].'>'; 
				}
				Inc($c);
			}
		}
		return $result;		
	}
	
	function SendMail($to, $from, $replyto, $subject, $message, $file, $root_location, $charset = 'Windows-1251')
	{
		
		$rn = Chr(13).Chr(10);
		$bc = '--';
		
		$boundary = strtoupper(md5(uniqid(rand(), true))); 
		
		$header = 'MIME-Version: 1.0'; 
		
		$header = $header.$rn.'Content-Type: multipart/mixed; boundary="'.$boundary.'"'; 
		
		$temp_to = _MailAddress($to, $charset);
		if ($temp_to == '')
		{
			
			return false;
		}
//		$header = $header.$rn.'To: '.$temp; 			
		$temp = _MailAddress($from, $charset);
		if ($temp == '')
		{
			return false;
		}		
		$header = $header.$rn.'From: '.$temp;
		if (is_array($replyto))
		{
			$header = $header.$rn.'Reply-To: '._MailAddress($replyto, $charset); 
		}
//		if ($subject != '')
//		{
//			$header = $header.$rn.'Subject: '._MailCP($subject, $charset); 
//		}
		$temp_subj = _MailCP($subject, $charset);

		$multipart = $bc.$boundary; 
		$multipart = $multipart.$rn.'Content-Type: text/html; charset='.$charset;
		$multipart = $multipart.$rn.'Content-Transfer-Encoding: base64';
		$multipart = $multipart.$rn;
		$multipart = $multipart.$rn.chunk_split(base64_encode($message));
		
		if (is_array($file))
		{
			
			for ($i = 0; $i < sizeof($file); $i++)
			{
				
				$file[$i][0] = $root_location.$file[$i][0];
				
				if (file_exists($file[$i][0]) and $file[$i][1] != '')
				{
					$fhwnd = fopen($file[$i][0], "r"); 
					if ($fhwnd) 
					{
						$stream = $bc.$boundary; 
						$stream = $stream.$rn.'Content-Type: application/octet-stream';  
						$stream = $stream.$rn.'Content-Transfer-Encoding: base64'; 
						$stream = $stream.$rn.'Content-Disposition: attachment; filename="'._MailCP($file[$i][1], $charset).'"'; 
						$stream = $stream.$rn;
						$stream = $stream.$rn.chunk_split(base64_encode(fread($fhwnd, filesize($file[$i][0]))));
						$multipart = $multipart.$rn.$stream;
					
						fclose($fhwnd); 
					}
				}
			}
		}		
		
		return mail($temp_to, $temp_subj, $multipart, $header);
	}
?>