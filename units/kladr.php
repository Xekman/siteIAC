<?
	$SESSION_KLADR_NAME = 'kladr';
	
	$SESSION_KLADR_REGION_NAME = 'region';
	$SESSION_KLADR_AREA_NAME = 'area';
	$SESSION_KLADR_CITY_NAME = 'city';
	$SESSION_KLADR_TOWN_NAME = 'town';
	$SESSION_KLADR_STREET_NAME = 'street';
	
	$KLADR_DATA_NAME_INDEX = 0;
	$KLADR_DATA_KEY_INDEX = 1;
	$KLADR_DATA_ZIP_INDEX = 2;
	$KLADR_DATA_SQL_INDEX = 3;
	
	$KLADR_DATA_KEYZIP_KEY_INDEX = 0;
	$KLADR_DATA_KEYZIP_ZIP_INDEX = 1;
	
	function GetKLADRRegionName($name, $type)
	{
		if (strtolower($type) == 'город')
		{
			$type = 'г.';
		}
		$temp = strtolower(substr($name, -3));
		if ($temp != 'кая' and $temp != 'кий')
		{
			return $type.' '.$name;
		}else{
			return $name.' '.strtolower($type);
		}	
	}
	
	function CreateKLADRTypeFullName($level, &$name)
	{	
		$name = array();	
		$db_query = mysql_query
		('
			SELECT
				DATName,
				DATFullName
			FROM 
				DirectoryAddressType
			WHERE 
				DATLevel = "'.$level.'"
		');	
		while ($row = mysql_fetch_array($db_query))
		{
			$name[strtolower($row['DATName'])] = $row['DATFullName'];
		}
	}

	function CreateKLADRTypeDisplayName($level, &$name)
	{	
		$name = array();	
		$db_query = mysql_query
		('
			SELECT
				DATName,
				DATDisplayName
			FROM 
				DirectoryAddressType
			WHERE 
				DATLevel = "'.$level.'"
		');	
		while ($row = mysql_fetch_array($db_query))
		{
			$name[strtolower($row['DATName'])] = $row['DATDisplayName'];
		}	
	}
	
	function GetKLADRSearchName($value, $level = 0)
	{
		$value = strtolower(trim(str_replace('  ', ' ', str_replace('.', ' ', trim($value)))));
		if ($level > 0)
		{	
			$db_query = mysql_query
			('
				SELECT
					DATName,
					DATFullName
				FROM 
					DirectoryAddressType
				WHERE 
					DATLevel = "'.$level.'"
			');	
			while ($row = mysql_fetch_array($db_query))
			{
				$value = str_replace(strtolower($row['DATFullName']), strtolower($row['DATName']), $value);				
			}
		}
		return str_replace(' ', '', str_replace('.', '', trim($value)));
	}
	
	function SetKLADRRegion()
	{
		global $CHARSET_NAME_UTF8;
		global $SYSTEM_CHARSET;
		
		global $KLADR_DATA_NAME_INDEX;
		global $KLADR_DATA_ZIP_INDEX;
		global $KLADR_DATA_SQL_INDEX;
	
		global $SESSION_KLADR_NAME;
		global $SESSION_KLADR_REGION_NAME;
		global $SESSION_KLADR_AREA_NAME;
		global $SESSION_KLADR_CITY_NAME;
		global $SESSION_KLADR_TOWN_NAME;
		global $SESSION_KLADR_STREET_NAME;
		
		$value = trim(iconv($CHARSET_NAME_UTF8, $SYSTEM_CHARSET, $_GET['region']));	

		if (isset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_REGION_NAME][$KLADR_DATA_NAME_INDEX]) and $_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_REGION_NAME][$KLADR_DATA_NAME_INDEX] == $value)
		{
			return;
		}

		unset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_REGION_NAME]);
		unset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_AREA_NAME]);
		unset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_CITY_NAME]);
		unset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_TOWN_NAME]);
		unset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_STREET_NAME]);
		
		$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_REGION_NAME][$KLADR_DATA_NAME_INDEX] = $value;
		
		$value = GetKLADRSearchName($value, 1);
		$db_query = mysql_query
		('
			SELECT
				DARKey,
				DARZip
			FROM 
				DirectoryAddressRegion
			WHERE 
				(
				LOWER(REPLACE(REPLACE(CONCAT_WS("", DARTypeName, DARName), ".", ""), " ", "")) = "'.$value.'" OR
				LOWER(REPLACE(REPLACE(CONCAT_WS("", DARName, DARTypeName), ".", ""), " ", "")) = "'.$value.'"				
				)
		');	
		$zip = '';
		$sql = '';
		while ($row = mysql_fetch_array($db_query))
		{
			if ($row['DARZip'] != '')
			{
				$zip = $row['DARZip'];
			}
			if ($sql != '')
			{
				$sql = $sql.' OR ';
			}			
			$sql = $sql.'DARKey = "'.$row['DARKey'].'"';
		}
		if ($sql == '')
		{
			$sql = 'DARKey = "0"';
		}			

		$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_REGION_NAME][$KLADR_DATA_ZIP_INDEX] = $zip;
		$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_REGION_NAME][$KLADR_DATA_SQL_INDEX] = '('.$sql.')';	
	}	
	
	function SetKLADRArea()
	{
		global $CHARSET_NAME_UTF8;
		global $SYSTEM_CHARSET;
		
		global $KLADR_DATA_NAME_INDEX;
		global $KLADR_DATA_ZIP_INDEX;
		global $KLADR_DATA_SQL_INDEX;
	
		global $SESSION_KLADR_NAME;
		global $SESSION_KLADR_REGION_NAME;
		global $SESSION_KLADR_AREA_NAME;
		global $SESSION_KLADR_CITY_NAME;
		global $SESSION_KLADR_TOWN_NAME;
		global $SESSION_KLADR_STREET_NAME;
		
		$value = trim(iconv($CHARSET_NAME_UTF8, $SYSTEM_CHARSET, $_GET['area']));	
		
		if (isset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_AREA_NAME][$KLADR_DATA_NAME_INDEX]) and $_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_AREA_NAME][$KLADR_DATA_NAME_INDEX] == $value)
		{
			return;
		}

		unset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_AREA_NAME]);
		unset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_CITY_NAME]);
		unset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_TOWN_NAME]);
		unset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_STREET_NAME]);		
		
		$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_AREA_NAME][$KLADR_DATA_NAME_INDEX] = $value;
		
		$value = GetKLADRSearchName($value, 2);
		$db_query = mysql_query
		('
			SELECT
				DAAKey,
				DAAZip
			FROM 
				DirectoryAddressArea
			WHERE 
				'.$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_REGION_NAME][$KLADR_DATA_SQL_INDEX].' AND
				(
				LOWER(REPLACE(REPLACE(CONCAT_WS("", DAATypeName, DAAName), ".", ""), " ", "")) = "'.$value.'" OR
				LOWER(REPLACE(REPLACE(CONCAT_WS("", DAAName, DAATypeName), ".", ""), " ", "")) = "'.$value.'"				
				)
		');	
		$zip = '';
		$sql = '';
		while ($row = mysql_fetch_array($db_query))
		{
			if ($row['DAAZip'] != '')
			{
				$zip = $row['DAAZip'];
			}
			if ($sql != '')
			{
				$sql = $sql.' OR ';
			}			
			$sql = $sql.'DAAKey = "'.$row['DAAKey'].'"';
		}
		if ($sql == '')
		{
			$sql = 'DAAKey = "0"';
		}		
		
		$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_AREA_NAME][$KLADR_DATA_ZIP_INDEX] = $zip;
		$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_AREA_NAME][$KLADR_DATA_SQL_INDEX] = '('.$sql.')';	
	}		
	
	function SetKLADRCity()
	{
		global $CHARSET_NAME_UTF8;
		global $SYSTEM_CHARSET;
		
		global $KLADR_DATA_NAME_INDEX;
		global $KLADR_DATA_ZIP_INDEX;
		global $KLADR_DATA_SQL_INDEX;
	
		global $SESSION_KLADR_NAME;
		global $SESSION_KLADR_REGION_NAME;
		global $SESSION_KLADR_AREA_NAME;
		global $SESSION_KLADR_CITY_NAME;
		global $SESSION_KLADR_TOWN_NAME;
		global $SESSION_KLADR_STREET_NAME;
		
		$value = trim(iconv($CHARSET_NAME_UTF8, $SYSTEM_CHARSET, $_GET['city']));	
		
		if (isset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_CITY_NAME][$KLADR_DATA_NAME_INDEX]) and $_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_CITY_NAME][$KLADR_DATA_NAME_INDEX] == $value)
		{
			return;
		}

		unset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_CITY_NAME]);
		unset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_TOWN_NAME]);
		unset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_STREET_NAME]);		
		
		$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_CITY_NAME][$KLADR_DATA_NAME_INDEX] = $value;
		
		$value = GetKLADRSearchName($value);
		$db_query = mysql_query
		('
			SELECT
				DACKey,
				DACZip
			FROM 
				DirectoryAddressCity
			WHERE 
				'.$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_REGION_NAME][$KLADR_DATA_SQL_INDEX].' AND
				'.$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_AREA_NAME][$KLADR_DATA_SQL_INDEX].' AND
				(
				LOWER(REPLACE(REPLACE(CONCAT_WS("", DACTypeName, DACName), ".", ""), " ", "")) = "'.$value.'" OR
				LOWER(REPLACE(REPLACE(CONCAT_WS("", DACName, DACTypeName), ".", ""), " ", "")) = "'.$value.'"				
				)		
		');	
		$zip = '';
		$sql = '';
		while ($row = mysql_fetch_array($db_query))
		{
			if ($row['DACZip'] != '')
			{
				$zip = $row['DACZip'];
			}
			if ($sql != '')
			{
				$sql = $sql.' OR ';
			}			
			$sql = $sql.'DACKey = "'.$row['DACKey'].'"';
		}
		if ($sql == '')
		{
			$sql = 'DACKey = "0"';
		}		

		$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_CITY_NAME][$KLADR_DATA_ZIP_INDEX] = $zip;
		$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_CITY_NAME][$KLADR_DATA_SQL_INDEX] = '('.$sql.')';
	}	
	
	function SetKLADRTown()
	{
		global $CHARSET_NAME_UTF8;
		global $SYSTEM_CHARSET;
		
		global $KLADR_DATA_NAME_INDEX;
		global $KLADR_DATA_ZIP_INDEX;
		global $KLADR_DATA_SQL_INDEX;
	
		global $SESSION_KLADR_NAME;
		global $SESSION_KLADR_REGION_NAME;
		global $SESSION_KLADR_AREA_NAME;
		global $SESSION_KLADR_CITY_NAME;
		global $SESSION_KLADR_TOWN_NAME;
		global $SESSION_KLADR_STREET_NAME;
		
		$value = trim(iconv($CHARSET_NAME_UTF8, $SYSTEM_CHARSET, $_GET['town']));	

		if (isset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_TOWN_NAME][$KLADR_DATA_NAME_INDEX]) and $_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_TOWN_NAME][$KLADR_DATA_NAME_INDEX] == $value)
		{
			return;
		}

		unset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_TOWN_NAME]);
		unset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_STREET_NAME]);		
		
		$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_TOWN_NAME][$KLADR_DATA_NAME_INDEX] = $value;
		
		$value = GetKLADRSearchName($value);
		$db_query = mysql_query
		('
			SELECT
				DATKey,
				DATZip
			FROM 
				DirectoryAddressTown
			WHERE 
				'.$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_REGION_NAME][$KLADR_DATA_SQL_INDEX].' AND
				'.$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_AREA_NAME][$KLADR_DATA_SQL_INDEX].' AND
				'.$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_CITY_NAME][$KLADR_DATA_SQL_INDEX].' AND
				(
				LOWER(REPLACE(REPLACE(CONCAT_WS("", DATTypeName, DATName), ".", ""), " ", "")) = "'.$value.'" OR
				LOWER(REPLACE(REPLACE(CONCAT_WS("", DATName, DATTypeName), ".", ""), " ", "")) = "'.$value.'"				
				)			
		');	
		$zip = '';
		$sql = '';
		while ($row = mysql_fetch_array($db_query))
		{
			if ($row['DATZip'] != '')
			{
				$zip = $row['DATZip'];
			}
			if ($sql != '')
			{
				$sql = $sql.' OR ';
			}			
			$sql = $sql.'DATKey = "'.$row['DATKey'].'"';
		}
		if ($sql == '')
		{
			$sql = 'DATKey = "0"';
		}		
		
		$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_TOWN_NAME][$KLADR_DATA_ZIP_INDEX] = $zip;
		$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_TOWN_NAME][$KLADR_DATA_SQL_INDEX] = '('.$sql.')';	
	}	
	
	function SetKLADRStreet()
	{
		global $CHARSET_NAME_UTF8;
		global $SYSTEM_CHARSET;
		
		global $KLADR_DATA_NAME_INDEX;
		global $KLADR_DATA_ZIP_INDEX;
		global $KLADR_DATA_SQL_INDEX;
	
		global $SESSION_KLADR_NAME;
		global $SESSION_KLADR_REGION_NAME;
		global $SESSION_KLADR_AREA_NAME;
		global $SESSION_KLADR_CITY_NAME;
		global $SESSION_KLADR_TOWN_NAME;
		global $SESSION_KLADR_STREET_NAME;
		
		$value = trim(iconv($CHARSET_NAME_UTF8, $SYSTEM_CHARSET, $_GET['street']));	

		if (isset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_STREET_NAME][$KLADR_DATA_NAME_INDEX]) and $_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_STREET_NAME][$KLADR_DATA_NAME_INDEX] == $value)
		{
			return;
		}

		unset($_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_STREET_NAME]);		
		
		$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_STREET_NAME][$KLADR_DATA_NAME_INDEX] = $value;
		
		$value = GetKLADRSearchName($value);
		$db_query = mysql_query
		('
			SELECT
				DASKey,
				DASZip
			FROM 
				DirectoryAddressStreet
			WHERE 
				'.$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_REGION_NAME][$KLADR_DATA_SQL_INDEX].' AND
				'.$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_AREA_NAME][$KLADR_DATA_SQL_INDEX].' AND
				'.$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_CITY_NAME][$KLADR_DATA_SQL_INDEX].' AND
				'.$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_TOWN_NAME][$KLADR_DATA_SQL_INDEX].' AND
				(
				LOWER(REPLACE(REPLACE(CONCAT_WS("", DASTypeName, DASName), ".", ""), " ", "")) = "'.$value.'" OR
				LOWER(REPLACE(REPLACE(CONCAT_WS("", DASName, DASTypeName), ".", ""), " ", "")) = "'.$value.'"				
				)					
		');	
		$zip = '';
		$sql = '';
		while ($row = mysql_fetch_array($db_query))
		{
			if ($row['DASZip'] != '')
			{
				$zip = $row['DASZip'];
			}			
			if ($sql != '')
			{
				$sql = $sql.' OR ';
			}			
			$sql = $sql.'DASKey = "'.$row['DASKey'].'"';
		}
		if ($sql == '')
		{
			$sql = 'DASKey = "0"';
		}		

		$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_STREET_NAME][$KLADR_DATA_ZIP_INDEX] = $zip;
		$_SESSION[$SESSION_KLADR_NAME][$SESSION_KLADR_STREET_NAME][$KLADR_DATA_SQL_INDEX] = '('.$sql.')';
	}		
?>