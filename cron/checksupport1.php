<?

require_once($TASK_FILE_ROOT_PATH."units/pop3.php");
require_once($TASK_FILE_ROOT_PATH."units/const.php");
$task_result = true;
$db_query_branch = mysql_query
('
	SELECT
		BID,
		BUseSupportEmail,
		BSupportEmail,
		BSupportEmailPass,
		BSupportEmailLastMessage
	FROM
		branch
	WHERE
		BUseSupportEmail = "1" AND
		BID = "3" AND
		BFlag = "1"
');

while ($row_branch = mysql_fetch_array($db_query_branch))
{
	$task_result = false;
	$maxuidl=0;
	if ($row_branch['BSupportEmailLastMessage'] != "")
	{
		$maxuidl = $row_branch['BSupportEmailLastMessage'];
	} else
	{
		$maxuidl = 0;
	}
	$bid = $row_branch['BID'];;
	$emailuser = $row_branch['BSupportEmail'];
	$emailpass=$row_branch['BSupportEmailPass'];
	
	//������������ � POP3 � ������������
	if (!$pop_conn = fsockopen("mail.iac.cdep.ru", 110,$errno, $errstr, 10)) 
	{
		$task_result = false;
		goto gt_exit;
	}
	$code=fgets($pop_conn,1024);
	fputs($pop_conn,"USER ".$emailuser."\r\n");
	$code= fgets($pop_conn,1024);
	fputs($pop_conn,"PASS ".$emailpass."\r\n");
	$code= fgets($pop_conn,1024);
	//�������� ������ � �������������� ���� ����� � ��������� �� �� ������
	fputs($pop_conn,"UIDL\r\n");
	$mailuidl=explode(";",str_replace(" ",",",str_replace ("\r\n",";",get_data($pop_conn))));
	$mailcount=count($mailuidl);
	unset($mailuidl[0]);
	unset($mailuidl[$mailcount-1]);
	unset($mailuidl[$mailcount-2]);
	$mailuidl=array_values($mailuidl);
	$mailcount=count($mailuidl);
	foreach ($mailuidl as $mailstr)
	$mail[]=explode(",",$mailstr);

	//����� ������� ��� ������� ��������, �� ������ ������ ������������
	$newuidl=$maxuidl;
	foreach ($mail as $mailarr)
	{
		if ($mailarr[1]<$maxuidl) continue;//���� ������ ��� ����������� �� ����� ������, �� ����� ��� ��������� �� ����
		else if ($mailarr[1]>$newuidl) $newuidl=$mailarr[1];//��� ���������� ���������� �� ����������� �������
		$isnewsupportmail=false;//������� ����, ��� ������ ��������� � ���
		//����������� � ��������� ��������� �����
		fputs($pop_conn,"TOP ".$mailarr[0]." 1\r\n");
		$text= get_data($pop_conn);
		$struct=fetch_structure($text);
		$mass_header=decode_header($struct['header']);
		//� ��������� basesubject � subject�������� ������������ � �������������� ���� ������
		$mass_header["basesubject"]=$mass_header["subject"];
		$mass_header["subject"] = decode_mime_string($mass_header["subject"]);

		if (strpos($mass_header["basesubject"],'[~')===0) 
			{
			//�������� �� �������
			$isnewsupportmail=true;
			$RHotLineNumber=rtrim(substr($mass_header["basesubject"],strpos($mass_header["basesubject"],'[~')+2,strpos($mass_header["basesubject"],']')-2-strpos($mass_header["basesubject"],'[~')));
			if (!is_numeric($RHotLineNumber)) $RHotLineNumber=rtrim(substr($mass_header["subject"],strpos($mass_header["subject"],'[~')+2,strpos($mass_header["subject"],']')-2-strpos($mass_header["subject"],'[~')));
			if (!is_numeric($RHotLineNumber)) continue;
			//���������, ���� �� ������ � ����� ������� ��� � ����
			
			$db_query = mysql_query
			('
				SELECT
					RHotLineNumber 
				FROM
					request 
				WHERE 
					RHotLineNumber="'.$RHotLineNumber.'" AND
					RDateTime >= "'.date('Y').'-01-01 00:00:00" AND
					RDateTime <= "'.date('Y').'-12-31 23:59:59"	AND				
					RFlag <> "0"
			');
			
			if (mysql_num_rows($db_query)) $isnewsupportmail=false;
			}
		
		//���� �������� �� ������� � �� ������ �� ������ - �������� ���������
		if ($isnewsupportmail) 
		{
			fputs($pop_conn,"RETR ".$mailarr[0]."\r\n");
			$text= get_data($pop_conn);
			$struct=fetch_structure($text);
			$mass_header=decode_header($struct['header']);
			$mass_header["subject"] = decode_mime_string($mass_header["subject"]);
			$type = $ctype = $mass_header['content-type'];
			$ctype = split(";",$ctype);
			$types = split("/",$ctype[0]);
			$maintype = trim(strtolower($types[0])); // text ��� multipart
			$subtype = trim(strtolower($types[1]));
			
			if($maintype=="text") $body = compile_body($struct['body'],$mass_header["content-transfer-encoding"],$mass_header["content-type"]);
			elseif($maintype=="multipart"){
				// �������� �����-����������� ������ ������
				$boundary=get_boundary($mass_header['content-type']);
				// �� ������ ����� ����������� ��������� ������ �� �����
				
				$part = split_parts($boundary,$struct['body']);
				$body = '';
				$mailfiles=array();
				// ������ ������������ ������ ����� ������
				for($i=0;$i<count($part);$i++) 
				{
					// ��������� ������� ����� �� ���� � ���������
					$email = fetch_structure($part[$i]);
					$header = $email["header"];
					$body2 = $email["body"];

					// ��������� ��������� �� ������
					$headers = decode_header($header);
					$ctype = $headers["content-type"];
					$cid = $headers["content-id"];
					$Actype = split(";",$headers["content-type"]);
					$types = split("/",$Actype[0]);
					$rctype = strtolower($Actype[0]);

					// ������ ���������, �������� �� ��� ����� ������������� ������, ����� ����� ������������
					$is_download = (ereg("name=",$headers["content-disposition"].$headers["content-type"]) || $headers["content-id"] != "" || $rctype == "message/rfc822");

					// ������ ������ ���� ���� �����, ���� ��� ������� �����
					if($rctype == "text/plain" && !$is_download)
						$body.= compile_body($body2,$headers["content-transfer-encoding"],$headers["content-type"]);

					// ���� ��� html
					elseif($rctype == "text/html" && !$is_download) 
						$body.= compile_body($body2,$headers["content-transfer-encoding"],$headers["content-type"]);

					elseif($is_download) {
					// ��� ����� ����� ��������� �� ���������� Content-Type ��� Content-Disposition
					$cdisp = $headers["content-disposition"];
					$ctype = $headers["content-type"];
					$ctype2 = explode(";",$ctype);
					$ctype2 = $ctype2[0];
					$Atype = split("/",$ctype);
					$Acdisp = split(";",$cdisp);
					$fname = $Acdisp[1];
					if(ereg("filename=(.*)",$fname,$regs))
					$filename = $regs[1];
					if($filename == "" && ereg("name=(.*)",$ctype,$regs))
					$filename = $regs[1];
					$filename=str_replace('"','',$filename);
					$filename=str_replace('\'','',$filename);
					$filename = trim(decode_mime_string($filename));
					$filename=mb_convert_encoding($filename,'CP1251',"auto");
					// ������ ������ ���� � ����������.
					$fileu = compile_body($body2,$headers["content-transfer-encoding"],$ctype);
					// ���������� ����� ������ � ���������� $fileu � ������ ����� ������ ���������� ����� � ������� ��� �������� ��������� �� �����
					$ft=fopen($filename,"wb");
					fwrite($ft,$fileu);
					fclose($ft);
					//��������� ���� � ���������� ��� ���������� ���������
					$mailfiles[]=$filename;
					}
				}
			}
			//�� �����-�� �������� ��������� ������ �� ������ ��������� ���� ���������
			if (!strpos($body,"������ ��: ")) $body=mb_convert_encoding($body,'CP1251',"auto");
			//�������� ���������� ����������� �� �������� ������� ����� ��������� �����
			$body=str_replace('<hr style="margin-bottom: 6px; height: 1px; BORDER: none; color: #cfcfcf; background-color: #cfcfcf;">','________________________________',$body);
			//������� ��� ��������� ����
			$body=strip_tags($body);
			$request_number = "";
			if (strpos($body,"[iac-analytics]"))
			{
				$temp = explode('[iac-analytics]',$body);
				$request_number = $temp[1];
			}
			if (($request_number) == "")
			{
				//����������� �� ������ �������� ������
				$bodyblocks=explode('________________________________',$body);
				$cbb=count($bodyblocks);
				$requesttext=rtrim(substr($bodyblocks[$cbb-2],0,strpos($bodyblocks[$cbb-2],'������ �������')));
				$RDateTime=rtrim(substr($bodyblocks[$cbb-3],strpos($bodyblocks[$cbb-3],'������ ��: ')+strlen('������ ��: ')));
				//$RSubType=rtrim(substr($bodyblocks[$cbb-1],strpos($bodyblocks[$cbb-1],'�����: ')+7,strpos($bodyblocks[$cbb-1],'���: ')-7-strpos($bodyblocks[$cbb-1],'�����: ')));
				$contact=explode(' ',rtrim(substr($bodyblocks[0],0,strpos($bodyblocks[0],'(')-1)));
				$vnkode=rtrim(substr($bodyblocks[0],strpos($bodyblocks[0],'(')+1,strpos($bodyblocks[0],')')-1-strpos($bodyblocks[0],'(')));
				$division_code=$vnkode;
				
				$db_query = mysql_query
				('
					SELECT
						DID,
						DPhone,
						DEmail
					FROM
						division
						INNER JOIN divisioncodetype ON (division.DCTID = divisioncodetype.DCTID)
					WHERE
					TRIM(CONCAT_WS("", division.DCodeRegion, divisioncodetype.DCTName, division.DCodeNumber))="'.$vnkode.'" AND
					division.DFlag = "1"
				');
				
				//��� ��������� ������, ������ ���� ������� ������
				if ($row = mysql_fetch_array($db_query)){
					$DID=$row['DID'];
					$RContactPhone=$row['DPhone'];
					$DEmail=$row['DEmail'];
				
				//������������ ������� � �����������
				$db_query = mysql_query
					('
						SELECT DISTINCT
							MAX(request.RInc) AS max
						FROM
							request
							INNER JOIN division ON (request.DID = division.DID)
						WHERE
							request.RFlag <> "0" AND
							division.BID = "'.$bid.'" AND
							request.RDateTime >= "'.date('Y').'-01-01 00:00:00" AND
							request.RDateTime <= "'.date('Y').'-12-31 23:59:59"
					');
					
					$request_inc = 0;			
					if (mysql_num_rows($db_query) == 1)
					{
						$row = mysql_fetch_array($db_query);
						$request_inc = $row['max'];
					}		

					Inc($request_inc);
						
					$request_number = '�'.$REQUEST_NUMBER_SEPARATOR.$request_inc.$REQUEST_NUMBER_SEPARATOR.$division_code.$REQUEST_NUMBER_SEPARATOR.substr(date('y'), -1);
					
					$db_query = mysql_query
						('
							SELECT DISTINCT
								MAX(RIncDivision) AS max
							FROM
								request
							WHERE
								RFlag <> "0" AND
								DID = "'.$DID.'" AND
								RDateTime >= "'.date('Y').'-01-01 00:00:00" AND
								RDateTime <= "'.date('Y').'-12-31 23:59:59"
						');
						
						$request_inc2 = 0;		
						if (mysql_num_rows($db_query) == 1)
						{
							$row = mysql_fetch_array($db_query);
							$request_inc2 = $row['max'];
						}				

						Inc($request_inc2);
				
				$insertql='
						INSERT INTO 
							request
							(
								RNumber,
								DID,
								RMessageA,
								RContactSurname,
								RContactName,
								RContactPatronymic,
								RContactPhone,
								RContactEmail,
								RHotLineNumber,
								RSID,	
								RRTID,
								RInc,
								RIncDivision,
								UID,
								RDateTime,
								RIsDocument
							)
						VALUES 
							(
							"'.$request_number.'",
							"'.$DID.'",
							"'.$requesttext.'",
							"'.trim($contact[0]).'",
							"'.trim($contact[1]).'",
							"'.trim($contact[2]).'",
							"'.$RContactPhone.'",
							"'.$DEmail.'",
							"'.$RHotLineNumber.'",
							"3",					
							"2",
							"'.$request_inc.'",
							"'.$request_inc2.'",
							
							"'.$_SESSION[$SESSION_UID_NAME].'",
							"'.DateTime::createFromFormat('d-m-Y H:i:s', $RDateTime)->format('Y-m-d H:i:s').'",
							"1"
							)
					';
				
				$db_query = mysql_query
						($insertql);
						
				$idfilename=mysql_insert_id();
						
				$sql_insert_id = mysql_insert_id();			

					$db_query = mysql_query
					('
						SELECT DISTINCT
							UID
						FROM
							userofdivision
						WHERE
							DID = "'.$DID.'"
					');			
					while ($row = mysql_fetch_array($db_query))
					{
						$db_query2 = mysql_query
						('
							INSERT INTO 
								requestofuser
								(
									RID,
									UID
								)
							VALUES 
								(
								"'.$sql_insert_id.'",
								"'.$row['UID'].'"				
								)
						');			
					}		
				
				//��������� ������������� ������
				if (count($mailfiles)>0) {
					$zip = new ZipArchive();
					$zip_name = $TASK_FILE_ROOT_PATH.'files/request/'.$idfilename.".zip"; // ��� �����
					$zip->open($zip_name, ZIPARCHIVE::CREATE);
					foreach ($mailfiles as $filename) $zip->addFile($filename);
					$zip->close();
					if(file_exists($zip_name)) 
						$db_query = mysql_query
							('
								UPDATE 
									request
								SET
									RExpansion = ".zip"
								WHERE
							RID = "'.$idfilename.'"
							');
				}
				//�������� ������������� ������
				foreach ($mailfiles as $filename) unlink($filename);
				}
			} else
			{
				$db_query = mysql_query
				('
					UPDATE 
						request
					SET
						RHotLineNumber = "'.$RHotLineNumber.'"
					WHERE
					RNumber = "'.$request_number.'"
				');
			}
		}
	//���������� ���������� � ���� �� ������������ �����
	if ($newuidl!=$maxuidl)
	{
		$db_query = mysql_query('UPDATE branch set BSupportEmailLastMessage='.$newuidl.' where BID="'.$bid.'"');
	}
	}
	fclose($pop_conn);
	$task_result = true;
	gt_exit:
}
?>