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
	
	//подключаемся к POP3 и авторизуемся
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
	//получаем номера и идентификаторы всех писем и разбираем их на массив
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

	//вывод делался для отладки запросов, на данный момент необязателен
	$newuidl=$maxuidl;
	foreach ($mail as $mailarr)
	{
		if ($mailarr[1]<$maxuidl) continue;//если письмо уже проверялось по нашим данным, то снова его проверять не надо
		else if ($mailarr[1]>$newuidl) $newuidl=$mailarr[1];//для обновления информации по проверенным письмам
		$isnewsupportmail=false;//признак того, что письмо относится к СТП
		//вытаскиваем и разбираем заголовки писем
		fputs($pop_conn,"TOP ".$mailarr[0]." 1\r\n");
		$text= get_data($pop_conn);
		$struct=fetch_structure($text);
		$mass_header=decode_header($struct['header']);
		//в элементах basesubject и subjectхранится кодированная и декодированная тема письма
		$mass_header["basesubject"]=$mass_header["subject"];
		$mass_header["subject"] = decode_mime_string($mass_header["subject"]);

		if (strpos($mass_header["basesubject"],'[~')===0) 
			{
			//подходит по формату
			$isnewsupportmail=true;
			$RHotLineNumber=rtrim(substr($mass_header["basesubject"],strpos($mass_header["basesubject"],'[~')+2,strpos($mass_header["basesubject"],']')-2-strpos($mass_header["basesubject"],'[~')));
			if (!is_numeric($RHotLineNumber)) $RHotLineNumber=rtrim(substr($mass_header["subject"],strpos($mass_header["subject"],'[~')+2,strpos($mass_header["subject"],']')-2-strpos($mass_header["subject"],'[~')));
			if (!is_numeric($RHotLineNumber)) continue;
			//проверить, есть ли заявка с таким номером уже в базе
			
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
		
		//если подходит по формату и не повтор по номеру - начинаем обработку
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
			$maintype = trim(strtolower($types[0])); // text или multipart
			$subtype = trim(strtolower($types[1]));
			
			if($maintype=="text") $body = compile_body($struct['body'],$mass_header["content-transfer-encoding"],$mass_header["content-type"]);
			elseif($maintype=="multipart"){
				// получаем метку-разделитель частей письма
				$boundary=get_boundary($mass_header['content-type']);
				// на основе этого разделителя разбиваем письмо на части
				
				$part = split_parts($boundary,$struct['body']);
				$body = '';
				$mailfiles=array();
				// теперь обрабатываем каждую часть письма
				for($i=0;$i<count($part);$i++) 
				{
					// разбиваем текущую часть на тело и заголовки
					$email = fetch_structure($part[$i]);
					$header = $email["header"];
					$body2 = $email["body"];

					// разбираем заголовки на массив
					$headers = decode_header($header);
					$ctype = $headers["content-type"];
					$cid = $headers["content-id"];
					$Actype = split(";",$headers["content-type"]);
					$types = split("/",$Actype[0]);
					$rctype = strtolower($Actype[0]);

					// теперь проверяем, является ли эта часть прикрепленным файлом, чтобы потом архивировать
					$is_download = (ereg("name=",$headers["content-disposition"].$headers["content-type"]) || $headers["content-id"] != "" || $rctype == "message/rfc822");

					// теперь читаем само тело части, если это обычный текст
					if($rctype == "text/plain" && !$is_download)
						$body.= compile_body($body2,$headers["content-transfer-encoding"],$headers["content-type"]);

					// если это html
					elseif($rctype == "text/html" && !$is_download) 
						$body.= compile_body($body2,$headers["content-transfer-encoding"],$headers["content-type"]);

					elseif($is_download) {
					// Имя файла можно выдернуть из заголовков Content-Type или Content-Disposition
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
					// теперь читаем файл в переменную.
					$fileu = compile_body($body2,$headers["content-transfer-encoding"],$ctype);
					// содержимое файла теперь в переменной $fileu и сейчас можно отдать содержимое файла в браузер или например сохранить на диске
					$ft=fopen($filename,"wb");
					fwrite($ft,$fileu);
					fclose($ft);
					//сохраняем файл и запоминаем для последущей архивации
					$mailfiles[]=$filename;
					}
				}
			}
			//по каким-то причинам некоторые письма не отдают нормально свою кодировку
			if (!strpos($body,"Запись от: ")) $body=mb_convert_encoding($body,'CP1251',"auto");
			//заменяем внутренний разделитель по которому удобнее всего разбирать текст
			$body=str_replace('<hr style="margin-bottom: 6px; height: 1px; BORDER: none; color: #cfcfcf; background-color: #cfcfcf;">','________________________________',$body);
			//удаляем все остальные теги
			$body=strip_tags($body);
			$request_number = "";
			if (strpos($body,"[iac-analytics]"))
			{
				$temp = explode('[iac-analytics]',$body);
				$request_number = $temp[1];
			}
			if (($request_number) == "")
			{
				//вытаскиваем из текста свойства заявки
				$bodyblocks=explode('________________________________',$body);
				$cbb=count($bodyblocks);
				$requesttext=rtrim(substr($bodyblocks[$cbb-2],0,strpos($bodyblocks[$cbb-2],'Детали Запроса')));
				$RDateTime=rtrim(substr($bodyblocks[$cbb-3],strpos($bodyblocks[$cbb-3],'Запись от: ')+strlen('Запись от: ')));
				//$RSubType=rtrim(substr($bodyblocks[$cbb-1],strpos($bodyblocks[$cbb-1],'Отдел: ')+7,strpos($bodyblocks[$cbb-1],'Тип: ')-7-strpos($bodyblocks[$cbb-1],'Отдел: ')));
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
				
				//все остальное делать, только если нашелся объект
				if ($row = mysql_fetch_array($db_query)){
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
				
				//архивация прикрепленных файлов
				if (count($mailfiles)>0) {
					$zip = new ZipArchive();
					$zip_name = $TASK_FILE_ROOT_PATH.'files/request/'.$idfilename.".zip"; // имя файла
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
				//удаление прикрепленных файлов
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
	//обновление информации в базе об обработанной почте
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