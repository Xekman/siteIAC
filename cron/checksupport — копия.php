<?
require_once($TASK_FILE_ROOT_PATH."units/const.php");
require_once($TASK_FILE_ROOT_PATH."units/sysutils.php");
require_once($TASK_FILE_ROOT_PATH."units/imap.php");

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
		BFlag = "1"
');

while ($row_branch = mysql_fetch_array($db_query_branch))
{
	$task_result = false;
	$bid = $row_branch['BID'];;
	$emailuser = $row_branch['BSupportEmail'];
	$emailpass=$row_branch['BSupportEmailPass'];
	$max_uid=0;
	if ($row_branch['BSupportEmailLastMessage'] != "")
	{
		$max_uid = $row_branch['BSupportEmailLastMessage'];
	} else
	{
		$max_uid = 0;
	}
	if (!$connect = imap_open('{mail.iac.cdep.ru:143/imap/novalidate-cert}INBOX',$emailuser, $emailpass))
	{
		$task_result = false;
		goto gt_exit;
	} 
	$mails = imap_search($connect, 'ALL UNDELETED ', SE_UID);
	$c = 0;
	$mail_number_string = "";
	$mail_number_string_new = "";
	foreach ($mails as $mail_uid)
	{
		if ($mail_uid > $max_uid)
		{
			if ($c == 0)
			{
				$comma = "";
			} else
			{
				$comma = ",";
			}
			$mail_number_string = $mail_number_string.$comma.$mail_uid;
			$c++;
		}
	}
	$mail_headers = array();
	$mail_headers = imap_fetch_overview($connect, $mail_number_string, FT_UID);
	foreach ($mail_headers as $mail_header)
	{
		$mark = "";
		if (strpos($mail_header->subject,'[~')===0 )
		{
			$mark = '[~';
		}
		if (strpos($mail_header->subject,'[#')===0 )
		{
			$mark = '[#';
		}
		if ($mark != "")
		{
			$isnewsupportmail=true;
			$hot_line_number_code = $mail_header->subject;
			$hot_line_number=rtrim(substr($hot_line_number_code,strpos($hot_line_number_code,$mark)+2,strpos($hot_line_number_code,']')-2-strpos($hot_line_number_code,$mark)));
			if (!is_numeric($hot_line_number))
			{$hot_line_number=rtrim(substr($hot_line_number,strpos($hot_line_number,$mark)+2,strpos($hot_line_number,']')-2-strpos($hot_line_number,$mark)));
			}
			if (is_numeric($hot_line_number))
			{
				
				$db_query = mysql_query
				('
					SELECT
						RHotLineNumber 
					FROM
						request 
					WHERE 
						RHotLineNumber="'.$hot_line_number.'" AND
						RDateTime >= "'.date('Y').'-01-01 00:00:00" AND
						RDateTime <= "'.date('Y').'-12-31 23:59:59"	AND				
						RFlag <> "0"
				');
				
				if (mysql_num_rows($db_query) >= 1)
				{
					$isnewsupportmail=false;
				} else
				{
					$structure = imap_fetchstructure($connect, $mail_header->uid, FT_UID);
					$boundary = '';
					if ($structure->ifparameters)
					{
						foreach ($structure->parameters as $param)
						{
							if (strtolower($param->attribute) == 'boundary')
							$boundary = $param->value;
						}
					}
					$parts = array();
					getParts($structure, $parts);
					if ($structure->type == 1)
					{
						$parts = array();
						getParts($structure, $parts);
						$mail['body'] = imap_fetchbody($connect, $mail_header->uid, '1', FT_UID);
						$mail['body'] = imap_utf8((getPlain($mail['body'], $boundary)));
						$mail['body'] = iconv('utf-8', 'Windows-1251', $mail['body']);
						$i = 0;
						foreach ($parts as $part)
						{
							if ($part['type'] > 1)
							{
								$file = imap_fetchbody($connect, $mail_header->uid, $i+1, FT_UID);
								$mail['files'][] = array('content'  => base64_decode($file),
								'filename' => $part['params'][0]['val'],
								'size'     => $part['bytes']);
							}
							$i++;
						}
					}
					else
					{
						$mail['body'] = imap_body($connect, $mail_header->uid, FT_UID);
						$mail['body'] = imap_utf8((getPlain($mail['body'], $boundary)));
						$mail['body'] = iconv('utf-8', 'Windows-1251', $mail['body']);
					}
					
					$request_number = "";
					if (strpos($mail['body'],"[iac-analytics]"))
					{
						$temp = explode('[iac-analytics]',$mail['body']);
						$request_number = $temp[1];
					}
					if ($request_number != "")
					{
						$db_query = mysql_query
						('
							UPDATE 
								request
							SET
								RHotLineNumber = "'.$hot_line_number.'"
							WHERE
							RNumber = "'.$request_number.'"
						');
					}
					if ($request_number == "")
					{
						$mail['body']=str_replace('<hr style="margin-bottom: 6px; height: 1px; BORDER: none; color: #cfcfcf; background-color: #cfcfcf;">','________________________________',$mail['body']);
						$mail['body']=strip_tags($mail['body']);
						$bodyblocks=explode('________________________________',$mail['body']);
						$cbb=count($bodyblocks);
						$requesttext=rtrim(substr($bodyblocks[$cbb-2],0,strpos($bodyblocks[$cbb-2],'Детали Запроса')));
						$RDateTime=rtrim(substr($bodyblocks[$cbb-3],strpos($bodyblocks[$cbb-3],'Запись от: ')+strlen('Запись от: ')));
						$RDateTime = date_create($RDateTime)->format('Y-m-d H:i:s');
						$contact=explode(' ',rtrim(substr($bodyblocks[0],0,strpos($bodyblocks[0],'(')-1)));
						$vnkode=rtrim(substr($bodyblocks[0],strpos($bodyblocks[0],'(')+1,strpos($bodyblocks[0],')')-1-strpos($bodyblocks[0],'(')));
						$division_code=$vnkode;
						$db_query = mysql_query
						('
							SELECT
								DID,
								DPhone,
								DEmail,
								DCodeNumber
							FROM
								division
								INNER JOIN divisioncodetype ON (division.DCTID = divisioncodetype.DCTID)
							WHERE
								TRIM(CONCAT_WS("", division.DCodeRegion, divisioncodetype.DCTName, division.DCodeNumber)) LIKE "'.$vnkode.'%" AND
								division.DFlag = "1"
							ORDER BY
								DCodeNumber
							LIMIT 1
						');
						//все остальное делать, только если нашелся объект
						if (($row = mysql_fetch_array($db_query)) and ($vnkode != ""))
						{
							$DID=$row['DID'];
							$RContactPhone=$row['DPhone'];
							$DEmail=$row['DEmail'];
						
						//формирование номеров и инкрементов
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
								
							$request_number = 'О'.$REQUEST_NUMBER_SEPARATOR.$request_inc.$REQUEST_NUMBER_SEPARATOR.$division_code.$REQUEST_NUMBER_SEPARATOR.substr(date('y'), -1);
							
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
									"'.$hot_line_number.'",
									"3",					
									"2",
									"'.$request_inc.'",
									"'.$request_inc2.'",
									
									"'.$_SESSION[$SESSION_UID_NAME].'",
									"'.$RDateTime.'",
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
							//архивация прикрепленных файлов
							if (count($mail['files'])>0)
							{
								$zip = new ZipArchive();
								$zip_name = $TASK_FILE_ROOT_PATH.'files/request/'.$idfilename.".zip"; // имя файла
								$zip->open($zip_name, ZIPARCHIVE::CREATE);
								foreach ($mail['files'] as $filename) 
								{
									$zip->addFromString($filename['filename'],$filename['content']);
								}
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
							//удаление прикрепленных файлов
							foreach ($mailfiles as $filename) unlink($filename);
						}
					}
				}
			}
		}
	if ($max_uid!=$mail_uid)
	{
		$db_query = mysql_query('UPDATE branch set BSupportEmailLastMessage='.$mail_uid.' where BID="'.$bid.'"');
	}
	}
	imap_close($connect);
	$task_result = true;
	gt_exit:
	
}
?>