<?
	function GetPhoneOperator($prefix, $number, &$operator, $showtype = true)
	{
		$result = false;
		$operator = '';
		
		if (strlen($prefix) < 3 or !is_numeric($prefix))
		{
			return false;
		}
		
		if (is_numeric($number))
		{
			$db_query = mysql_query
			('
				SELECT 
					DPOName,
					DPORegionName
				FROM
					DirectoryPhoneOperator
				WHERE
					DPOPrefix = "'.$prefix.'" AND
					DPONumberMin <= "0.'.$number.'" AND 
					DPONumberMax >= "0.'.$number.'"
				ORDER BY
					DPOID DESC
			');
			while ($row = mysql_fetch_array($db_query))
			{
				$result = true;
				
				if ($showtype)
				{
					$type = ($prefix[0] == 9) ? 'Сотовый' : 'Городской';
					$type = ' | '.$type;
				}else{
					$type = '';
				}
				$operator = $row['DPOName'].' ('.$row['DPORegionName'].')'.$type;				
				break;
			}				
		}			
		if (!$result)
		{
			$db_query = mysql_query
			('
				SELECT
					COUNT(DPOID) AS count
				FROM
					DirectoryPhoneOperator
				WHERE
					DPOPrefix = "'.$prefix.'" AND
					DPOPrefix >= "'.$min.'" 
			');		
			if (mysql_num_rows($db_query) == 1)
			{
				$row = mysql_fetch_array($db_query);				
				if ($row['count'] > 0)
				{
					$result = true;
				}
			}		
		}

		return $result;
	}
	
	function SetDirectoryWorkPost($text, $type)
	{
		if ($text == '')
		{
			return false;
		}
		$text = PlaceFormat($text);
		$db_query = mysql_query
		('
			SELECT 
				DWPID,
				DWPRating
			FROM 
				DirectoryWorkPost 
			WHERE 
				LOWER(DWPName) = "'.strtolower($text).'" AND
				DWPType = "'.$type.'"
		');
		if (mysql_num_rows($db_query) != 0)
		{
			$row = mysql_fetch_array($db_query);
			$rating = $row['DWPRating'];
			Inc($rating);
			$db_query = mysql_query
			('
				UPDATE 
					DirectoryWorkPost
				SET
					DWPRating = "'.$rating.'",
					DWPName = "'.$text.'",
					DWPType = "'.$type.'"
				WHERE
					DWPID = "'.$row['DWPID'].'"
			');
		}else{
			$db_query = mysql_query
			('
				INSERT INTO 
					DirectoryWorkPost
					(
						DWPName,
						DWPType
					)
				VALUES 
					(
					"'.$text.'",
					"'.$type.'"
					)
			');
		}		
	}	
	
	function SetNotification($uid, $text, $detail, $datetime)
	{
		$db_query = mysql_query
		('
			INSERT INTO 
				Notification
				(
					UID,
					NText,
					NDetail,
					NDateTime
				)
			VALUES 
				(
				"'.$uid.'",
				"'.$text.'",
				"'.GetCheckLengthString($detail, 8192).'",
				"'.$datetime.'"
				)
		');

		return ($db_query) ? true : false;
	}	
?>