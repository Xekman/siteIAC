<?
	if (!isset($_SESSION[$SESSION_UID_NAME])){goto gt_exit;}

	$RQ_INDEX_NAME = $RQ_EQUIPMENT_INDEX_NAME;

	$rq_index = GetGETIndex($RQ_INDEX_NAME);

	$UNIT_ROOT_NAME = $rq_command;
	
	$PO_DATA = NewPostObject(10);

	$poi = 0;
	$PO_DATA[$poi]->SetName($UNIT_ROOT_NAME.'_id');
	$poi = 1;
	$PO_DATA[$poi]->SetName($UNIT_ROOT_NAME.'_name_id');
	$poi = 2;
	$PO_DATA[$poi]->SetName($UNIT_ROOT_NAME.'_manufacturer_id');	
	$poi = 3;
	$PO_DATA[$poi]->SetName($UNIT_ROOT_NAME.'_model');
	$PO_DATA[$poi]->SetMinMax(1, 64);
	$poi = 4;
	$PO_DATA[$poi]->SetName($UNIT_ROOT_NAME.'_group_id');
	$poi = 5;
	$PO_DATA[$poi]->SetName($UNIT_ROOT_NAME.'_subgroup_id');	
	$poi = 6;
	$PO_DATA[$poi]->SetName($UNIT_ROOT_NAME.'_note');
	$PO_DATA[$poi]->SetMinMax(0, 1024);	
	$poi = 7;
	$PO_DATA[$poi]->SetName($UNIT_ROOT_NAME.'_name');	
	$PO_DATA[$poi]->SetMinMax(1, 64);
	$poi = 8;
	$PO_DATA[$poi]->SetName($UNIT_ROOT_NAME.'_manufacturer');		
	$PO_DATA[$poi]->SetMinMax(1, 64);
	$poi = 9;
	$PO_DATA[$poi]->SetName($UNIT_ROOT_NAME.'_caption');
	
	$PO_FORM = NewPostObject(4);
	$poi = 0;
	$PO_FORM[$poi]->SetName($UNIT_ROOT_NAME.'_find');
	$poi = 1;
	$PO_FORM[$poi]->SetName($UNIT_ROOT_NAME.'_filter_group');
	$poi = 2;
	$PO_FORM[$poi]->SetName($UNIT_ROOT_NAME.'_filter_subgroup');
	$poi = 3;
	$PO_FORM[$poi]->SetName($UNIT_ROOT_NAME.'_filter_manufacturer');
	
	$UNIT_LIST_NAME = $UNIT_ROOT_NAME.$UNIT_LIST_SUFFIX;
	
	$access = $ACCESS_NO;
	if (!isset($OP_USER_ACCESS_H[$rq_command]))
	{
		if ($_SESSION[$SESSION_BACKURL_NAME] != '')
		{
			$access = $ACCESS_LOW;
		}
	}else{
		$access = $ACCESS_FULL;
	}

	if ($access == $ACCESS_NO)
	{
		$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TEXT] = $MLI_MESSAGE_NOTORDER;
		$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_FLAG] = $MESSAGE_FLAG_VALUE_WARNING;
		$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TYPE] = $MESSAGE_TYPE_VALUE_OK;
		$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_LINK_B] = $url_c;
		$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_SHOW] = true;
		echo PublicJavaScriptHref($url_m);
		goto gt_exit;
	}

	ForwardMappingPostObject($PO_DATA);
	ForwardMappingPostObject($PO_FORM);

	$poi = 1;
	if (is_numeric($PO_FORM[$poi]->GetValue()))
	{
		$_SESSION[$SESSION_SET_NAME][$PO_FORM[$poi]->GetName()] = $PO_FORM[$poi]->GetValue();
	}
	$poi = 2;
	if (is_numeric($PO_FORM[$poi]->GetValue()))
	{
		$_SESSION[$SESSION_SET_NAME][$PO_FORM[$poi]->GetName()] = $PO_FORM[$poi]->GetValue();
	}
	$poi = 3;
	if (is_numeric($PO_FORM[$poi]->GetValue()))
	{
		$_SESSION[$SESSION_SET_NAME][$PO_FORM[$poi]->GetName()] = $PO_FORM[$poi]->GetValue();
	}
	
	if ($rq_action == $RQ_ACTION_VALUE_SELECT)
	{
		if (!$rq_index)
		{
			AddTextHintObject($HO_HINT, $MLI_HINT_ITEM_INCORRECT);
			include($UNIT_LIST_NAME);
			goto gt_exit;
		}
		$db_query = mysql_query
		('
			SELECT 
				EID 
			FROM 
				equipment 
			WHERE 
				EID = "'.$rq_index.'" AND
				EFlag > "0"
		');
		if (mysql_num_rows($db_query) != 1)
		{
			AddTextHintObject($HO_HINT, $MLI_HINT_ITEM_NOTFOUND);
			include($UNIT_LIST_NAME);
			goto gt_exit;
		}
		echo PublicJavaScriptHref(SetURL($_SESSION[$SESSION_BACKURL_NAME], $_SESSION[$SESSION_BACKINDEX_NAME], $rq_index));
		goto gt_exit;
	}

	if ($access == $ACCESS_LOW)
	{
		include($UNIT_LIST_NAME);
		goto gt_exit;
	}

	if ($access != $ACCESS_FULL)
	{
		$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TEXT] = $MLI_MESSAGE_NOTORDER;
		$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_FLAG] = $MESSAGE_FLAG_VALUE_WARNING;
		$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TYPE] = $MESSAGE_TYPE_VALUE_OK;
		$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_LINK_B] = $url_c;
		$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_SHOW] = true;
		echo PublicJavaScriptHref($url_m);
		goto gt_exit;
	}

	if ($rq_action == $RQ_ACTION_VALUE_CREATE and GetBit($OP_USER_ACCESS_H[$rq_command], $BACH_CREATE))
	{
		$UNIT_FORM_NAME = $UNIT_ROOT_NAME.$UNIT_FORM_CREATE_SUFFIX;
		
		if ($_POST[$POST_FLAG_NAME] == $POST_FLAG_VALUE_COMPLETE)
		{
			$poi = 1;
			if (!is_numeric($PO_DATA[$poi]->GetValue()))
			{
				AddTextHintObject($HO_HINT, 'Некорректно задано название');
				$PO_DATA[$poi]->SetCheck(false);
			}else{
				if ($PO_DATA[$poi]->GetValue() == -1 and GetBit($OP_USER_ACCESS_L[$rq_command], $BACL_EQUIPMENT_CREATE_EQUIPMENTNAME)) 
				{
					$poi = 7;
					if (!CheckStringLengthPostObject($PO_DATA, $poi))
					{
						AddTextHintObject($HO_HINT, 'Некорректно задано название');
						$PO_DATA[$poi]->SetCheck(false);
					}				
				}else{				
					$db_query = mysql_query
					('
						SELECT 
							ENID
						FROM 
							equipmentname 
						WHERE 
							ENID = "'.$PO_DATA[$poi]->GetValue().'" AND
							ENFlag > "0"
					');
					if (mysql_num_rows($db_query) != 1)
					{
						AddTextHintObject($HO_HINT, 'Выбранное название не найдено');
						$PO_DATA[$poi]->SetCheck(false);
					}
				}
			}		
			$poi = 2;
			if (!is_numeric($PO_DATA[$poi]->GetValue()))
			{
				AddTextHintObject($HO_HINT, 'Некорректно задан производитель');
				$PO_DATA[$poi]->SetCheck(false);
			}else{
				if ($PO_DATA[$poi]->GetValue() == -1 and GetBit($OP_USER_ACCESS_L[$rq_command], $BACL_EQUIPMENT_CREATE_EQUIPMENTMANUFACTURER)) 
				{
					$poi = 8;
					if (!CheckStringLengthPostObject($PO_DATA, $poi))
					{
						AddTextHintObject($HO_HINT, 'Некорректно задан производитель');
						$PO_DATA[$poi]->SetCheck(false);
					}					
				}else{				
					$db_query = mysql_query
					('
						SELECT 
							EMID
						FROM 
							equipmentmanufacturer 
						WHERE 
							EMID = "'.$PO_DATA[$poi]->GetValue().'" AND
							EMFlag > "0"
					');
					if (mysql_num_rows($db_query) != 1)
					{
						AddTextHintObject($HO_HINT, 'Выбранный производитель не найден');
						$PO_DATA[$poi]->SetCheck(false);
					}
				}
			}				
			$poi = 3;
			if (!CheckStringLengthPostObject($PO_DATA, $poi))
			{
				AddTextHintObject($HO_HINT, 'Некорректно задана модель');
				$PO_DATA[$poi]->SetCheck(false);
			}
			$poi = 4;
			if (!is_numeric($PO_DATA[$poi]->GetValue()))
			{
				AddTextHintObject($HO_HINT, 'Некорректно задана группа');
				$PO_DATA[$poi]->SetCheck(false);
			}else{		
				$db_query = mysql_query
				('
					SELECT 
						EGID
					FROM 
						equipmentgroup 
					WHERE 
						EGID = "'.$PO_DATA[$poi]->GetValue().'" AND
						EGFlag > "0"
				');
				if (mysql_num_rows($db_query) != 1)
				{
					AddTextHintObject($HO_HINT, 'Выбранная группа не найдена');
					$PO_DATA[$poi]->SetCheck(false);
				}
			}		
			$poi = 5;
			if (!is_numeric($PO_DATA[$poi]->GetValue()))
			{
				AddTextHintObject($HO_HINT, 'Некорректно задана подгруппа');
				$PO_DATA[$poi]->SetCheck(false);
			}else{		
				$db_query = mysql_query
				('
					SELECT 
						EGSGID
					FROM 
						equipmentgroupsubgroup 
					WHERE 
						EGSGID = "'.$PO_DATA[$poi]->GetValue().'" AND
						EGID = "'.$PO_DATA[4]->GetValue().'" AND
						EGSGFlag > "0"
				');
				if (mysql_num_rows($db_query) != 1)
				{
					AddTextHintObject($HO_HINT, 'Выбранная подгруппа не найдена');
					$PO_DATA[$poi]->SetCheck(false);
				}
			}			
			$poi = 6;
			if (!CheckStringLengthPostObject($PO_DATA, $poi))
			{
				AddTextHintObject($HO_HINT, 'Некорректно задано примечание');
				$PO_DATA[$poi]->SetCheck(false);
			}
			
			if (!CheckPostObject($PO_DATA))
			{
				include($UNIT_FORM_NAME);
				goto gt_exit;
			}

			$poi = 1;
			if ($PO_DATA[$poi]->GetValue() == -1 and GetBit($OP_USER_ACCESS_L[$rq_command], $BACL_EQUIPMENT_CREATE_EQUIPMENTNAME)) 
			{
				$db_query = mysql_query
				('
					SELECT 
						ENID 
					FROM 
						equipmentname 
					WHERE 
						LOWER(ENName) = "'.strtolower($PO_DATA[7]->GetValue()).'" AND
						ENFlag > "0"
				');
				if (mysql_num_rows($db_query) != 0)
				{
					$row = mysql_fetch_array($db_query);
					$sql_insert_id = $row['ENID'];
				}else{
					$db_query = mysql_query
					('
						INSERT INTO 
							equipmentname
							(
								ENName
							)
						VALUES 
							(
							"'.$PO_DATA[7]->GetValue().'"
							)
					');
					if (!$db_query)
					{
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TEXT] = $MLI_MESSAGE_SQL_EXECUTE_ERROR;
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_FLAG] = $MESSAGE_FLAG_VALUE_ERROR;
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TYPE] = $MESSAGE_TYPE_VALUE_OK;
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_LINK_B] = $url_b;
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_SHOW] = true;
						echo PublicJavaScriptHref($url_m);
						goto gt_exit;					
					}
					$sql_insert_id = mysql_insert_id();
				}
				$PO_DATA[$poi]->SetValue($sql_insert_id);
			}
			
			$poi = 2;
			if ($PO_DATA[$poi]->GetValue() == -1 and GetBit($OP_USER_ACCESS_L[$rq_command], $BACL_EQUIPMENT_CREATE_EQUIPMENTMANUFACTURER)) 
			{
				$db_query = mysql_query
				('
					SELECT 
						EMID 
					FROM 
						equipmentmanufacturer 
					WHERE 
						LOWER(EMName) = "'.strtolower($PO_DATA[8]->GetValue()).'" AND
						EMFlag > "0"
				');
				if (mysql_num_rows($db_query) != 0)
				{
					$row = mysql_fetch_array($db_query);
					$sql_insert_id = $row['EMID'];
				}else{
					$db_query = mysql_query
					('
						INSERT INTO 
							equipmentmanufacturer
							(
								EMName
							)
						VALUES 
							(
							"'.$PO_DATA[8]->GetValue().'"
							)
					');
					if (!$db_query)
					{
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TEXT] = $MLI_MESSAGE_SQL_EXECUTE_ERROR;
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_FLAG] = $MESSAGE_FLAG_VALUE_ERROR;
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TYPE] = $MESSAGE_TYPE_VALUE_OK;
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_LINK_B] = $url_b;
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_SHOW] = true;
						echo PublicJavaScriptHref($url_m);
						goto gt_exit;					
					}
					$sql_insert_id = mysql_insert_id();
				}
				$PO_DATA[$poi]->SetValue($sql_insert_id);
			}			
			
			$db_query = mysql_query
			('
				SELECT 
					EID
				FROM 
					equipment 
				WHERE 
					ENID = "'.$PO_DATA[1]->GetValue().'" AND
					EMID = "'.$PO_DATA[2]->GetValue().'" AND
					LOWER(EModel) = "'.strtolower($PO_DATA[3]->GetValue()).'" AND
					EGID = "'.$PO_DATA[4]->GetValue().'" AND
					EFlag > "0"
			');
			if (mysql_num_rows($db_query) != 0)
			{
				AddTextHintObject($HO_HINT, $MLI_HINT_ITEM_EXISTS);
				include($UNIT_FORM_NAME);
				goto gt_exit;
			}

			$db_query = mysql_query
			('
				INSERT INTO 
					equipment
					(
						ENID,
						EMID,
						EModel,
						EGID,
						EGSGID,
						ENote
					)
				VALUES 
					(
					"'.$PO_DATA[1]->GetValue().'",
					"'.$PO_DATA[2]->GetValue().'",
					"'.$PO_DATA[3]->GetValue().'",
					"'.$PO_DATA[4]->GetValue().'",
					"'.$PO_DATA[5]->GetValue().'",
					"'.$PO_DATA[6]->GetValue().'"
					)
			');
			if (!$db_query)
			{
				$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TEXT] = $MLI_MESSAGE_SQL_EXECUTE_ERROR;
				$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_FLAG] = $MESSAGE_FLAG_VALUE_ERROR;
				$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TYPE] = $MESSAGE_TYPE_VALUE_OK;
				$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_LINK_B] = $url_b;
				$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_SHOW] = true;
				echo PublicJavaScriptHref($url_m);
				goto gt_exit;
			}

			$sql_insert_id = mysql_insert_id();

			if ($_SESSION[$SESSION_BACKURL_NAME] != '')
			{
				$url = SetURL($url_b, $RQ_INDEX_NAME, $sql_insert_id);
				$url = SetURL($url, $RQ_ACTION_NAME, $RQ_ACTION_VALUE_SELECT);
				echo PublicJavaScriptHref($url);
			}else{
				echo PublicJavaScriptHref(SetURL($url_b, $RQ_INDEX_NAME, $sql_insert_id));
			}
			goto gt_exit;
		}

		include($UNIT_FORM_NAME);
		goto gt_exit;
	}

	if ($rq_action == $RQ_ACTION_VALUE_CHANGE and GetBit($OP_USER_ACCESS_H[$rq_command], $BACH_CHANGE))
	{
		$UNIT_FORM_NAME = $UNIT_ROOT_NAME.$UNIT_FORM_CHANGE_SUFFIX;
		
		if (!$rq_index)
		{
			AddTextHintObject($HO_HINT, $MLI_HINT_ITEM_INCORRECT);
			include($UNIT_LIST_NAME);
			goto gt_exit;
		}
		$db_query = mysql_query
		('
			SELECT 
				EID
			FROM 
				equipment 
			WHERE 
				EID = "'.$rq_index.'" AND
				EFlag > "0"
		');
		if (mysql_num_rows($db_query) != 1)
		{
			AddTextHintObject($HO_HINT, $MLI_HINT_ITEM_NOTFOUND);
			include($UNIT_LIST_NAME);
			goto gt_exit;
		}

		if ($_POST[$POST_FLAG_NAME] == $POST_FLAG_VALUE_COMPLETE)
		{
			$poi = 1;
			if (!is_numeric($PO_DATA[$poi]->GetValue()))
			{
				AddTextHintObject($HO_HINT, 'Некорректно задано название');
				$PO_DATA[$poi]->SetCheck(false);
			}else{
				if ($PO_DATA[$poi]->GetValue() == -1 and GetBit($OP_USER_ACCESS_L[$rq_command], $BACL_EQUIPMENT_CREATE_EQUIPMENTNAME)) 
				{
					$poi = 7;
					if (!CheckStringLengthPostObject($PO_DATA, $poi))
					{
						AddTextHintObject($HO_HINT, 'Некорректно задано название');
						$PO_DATA[$poi]->SetCheck(false);
					}				
				}else{				
					$db_query = mysql_query
					('
						SELECT 
							ENID
						FROM 
							equipmentname 
						WHERE 
							ENID = "'.$PO_DATA[$poi]->GetValue().'" AND
							ENFlag > "0"
					');
					if (mysql_num_rows($db_query) != 1)
					{
						AddTextHintObject($HO_HINT, 'Выбранное название не найдено');
						$PO_DATA[$poi]->SetCheck(false);
					}
				}
			}		
			$poi = 2;
			if (!is_numeric($PO_DATA[$poi]->GetValue()))
			{
				AddTextHintObject($HO_HINT, 'Некорректно задан производитель');
				$PO_DATA[$poi]->SetCheck(false);
			}else{
				if ($PO_DATA[$poi]->GetValue() == -1 and GetBit($OP_USER_ACCESS_L[$rq_command], $BACL_EQUIPMENT_CREATE_EQUIPMENTMANUFACTURER)) 
				{
					$poi = 8;
					if (!CheckStringLengthPostObject($PO_DATA, $poi))
					{
						AddTextHintObject($HO_HINT, 'Некорректно задан производитель');
						$PO_DATA[$poi]->SetCheck(false);
					}					
				}else{				
					$db_query = mysql_query
					('
						SELECT 
							EMID
						FROM 
							equipmentmanufacturer 
						WHERE 
							EMID = "'.$PO_DATA[$poi]->GetValue().'" AND
							EMFlag > "0"
					');
					if (mysql_num_rows($db_query) != 1)
					{
						AddTextHintObject($HO_HINT, 'Выбранный производитель не найден');
						$PO_DATA[$poi]->SetCheck(false);
					}
				}
			}				
			$poi = 3;
			if (!CheckStringLengthPostObject($PO_DATA, $poi))
			{
				AddTextHintObject($HO_HINT, 'Некорректно задана модель');
				$PO_DATA[$poi]->SetCheck(false);
			}
			$poi = 4;
			if (!is_numeric($PO_DATA[$poi]->GetValue()))
			{
				AddTextHintObject($HO_HINT, 'Некорректно задана группа');
				$PO_DATA[$poi]->SetCheck(false);
			}else{		
				$db_query = mysql_query
				('
					SELECT 
						EGID
					FROM 
						equipmentgroup 
					WHERE 
						EGID = "'.$PO_DATA[$poi]->GetValue().'" AND
						EGFlag > "0"
				');
				if (mysql_num_rows($db_query) != 1)
				{
					AddTextHintObject($HO_HINT, 'Выбранная группа не найдена');
					$PO_DATA[$poi]->SetCheck(false);
				}
			}		
			$poi = 5;
			if (!is_numeric($PO_DATA[$poi]->GetValue()))
			{
				AddTextHintObject($HO_HINT, 'Некорректно задана подгруппа');
				$PO_DATA[$poi]->SetCheck(false);
			}else{		
				$db_query = mysql_query
				('
					SELECT 
						EGSGID
					FROM 
						equipmentgroupsubgroup 
					WHERE 
						EGSGID = "'.$PO_DATA[$poi]->GetValue().'" AND
						EGID = "'.$PO_DATA[4]->GetValue().'" AND
						EGSGFlag > "0"
				');
				if (mysql_num_rows($db_query) != 1)
				{
					AddTextHintObject($HO_HINT, 'Выбранная подгруппа не найдена');
					$PO_DATA[$poi]->SetCheck(false);
				}
			}			
			$poi = 6;
			if (!CheckStringLengthPostObject($PO_DATA, $poi))
			{
				AddTextHintObject($HO_HINT, 'Некорректно задано примечание');
				$PO_DATA[$poi]->SetCheck(false);
			}
			
			if (!CheckPostObject($PO_DATA))
			{
				include($UNIT_FORM_NAME);
				goto gt_exit;
			}

			$poi = 1;
			if ($PO_DATA[$poi]->GetValue() == -1 and GetBit($OP_USER_ACCESS_L[$rq_command], $BACL_EQUIPMENT_CREATE_EQUIPMENTNAME)) 
			{
				$db_query = mysql_query
				('
					SELECT 
						ENID 
					FROM 
						equipmentname 
					WHERE 
						LOWER(ENName) = "'.strtolower($PO_DATA[7]->GetValue()).'" AND
						ENFlag > "0"
				');
				if (mysql_num_rows($db_query) != 0)
				{
					$row = mysql_fetch_array($db_query);
					$sql_insert_id = $row['ENID'];
				}else{
					$db_query = mysql_query
					('
						INSERT INTO 
							equipmentname
							(
								ENName
							)
						VALUES 
							(
							"'.$PO_DATA[7]->GetValue().'"
							)
					');
					if (!$db_query)
					{
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TEXT] = $MLI_MESSAGE_SQL_EXECUTE_ERROR;
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_FLAG] = $MESSAGE_FLAG_VALUE_ERROR;
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TYPE] = $MESSAGE_TYPE_VALUE_OK;
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_LINK_B] = $url_b;
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_SHOW] = true;
						echo PublicJavaScriptHref($url_m);
						goto gt_exit;					
					}
					$sql_insert_id = mysql_insert_id();
				}
				$PO_DATA[$poi]->SetValue($sql_insert_id);
			}
			
			$poi = 2;
			if ($PO_DATA[$poi]->GetValue() == -1 and GetBit($OP_USER_ACCESS_L[$rq_command], $BACL_EQUIPMENT_CREATE_EQUIPMENTMANUFACTURER)) 
			{
				$db_query = mysql_query
				('
					SELECT 
						EMID 
					FROM 
						equipmentmanufacturer 
					WHERE 
						LOWER(EMName) = "'.strtolower($PO_DATA[8]->GetValue()).'" AND
						EMFlag > "0"
				');
				if (mysql_num_rows($db_query) != 0)
				{
					$row = mysql_fetch_array($db_query);
					$sql_insert_id = $row['EMID'];
				}else{
					$db_query = mysql_query
					('
						INSERT INTO 
							equipmentmanufacturer
							(
								EMName
							)
						VALUES 
							(
							"'.$PO_DATA[8]->GetValue().'"
							)
					');
					if (!$db_query)
					{
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TEXT] = $MLI_MESSAGE_SQL_EXECUTE_ERROR;
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_FLAG] = $MESSAGE_FLAG_VALUE_ERROR;
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TYPE] = $MESSAGE_TYPE_VALUE_OK;
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_LINK_B] = $url_b;
						$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_SHOW] = true;
						echo PublicJavaScriptHref($url_m);
						goto gt_exit;					
					}
					$sql_insert_id = mysql_insert_id();
				}
				$PO_DATA[$poi]->SetValue($sql_insert_id);
			}			
			
			$db_query = mysql_query
			('
				SELECT 
					EID
				FROM 
					equipment 
				WHERE 
					ENID = "'.$PO_DATA[1]->GetValue().'" AND
					EMID = "'.$PO_DATA[2]->GetValue().'" AND
					LOWER(EModel) = "'.strtolower($PO_DATA[3]->GetValue()).'" AND
					EGID = "'.$PO_DATA[4]->GetValue().'" AND
					EID <> "'.$rq_index.'" AND
					EFlag > "0"
			');			
		
			if (mysql_num_rows($db_query) != 0)
			{
				AddTextHintObject($HO_HINT, $MLI_HINT_ITEM_EXISTS);
				include($UNIT_FORM_NAME);
				goto gt_exit;
			}
			
			$db_query = mysql_query
			('
				UPDATE 
					equipment
				SET
					ENID = "'.$PO_DATA[1]->GetValue().'",
					EMID = "'.$PO_DATA[2]->GetValue().'",
					EModel = "'.$PO_DATA[3]->GetValue().'",
					EGID = "'.$PO_DATA[4]->GetValue().'",
					EGSGID = "'.$PO_DATA[5]->GetValue().'",
					ENote = "'.$PO_DATA[6]->GetValue().'"
				WHERE
					EID = "'.$rq_index.'"
			');		
			
			if (!$db_query)
			{
				$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TEXT] = $MLI_MESSAGE_SQL_EXECUTE_ERROR;
				$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_FLAG] = $MESSAGE_FLAG_VALUE_ERROR;
				$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TYPE] = $MESSAGE_TYPE_VALUE_OK;
				$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_LINK_B] = $url_b;
				$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_SHOW] = true;
				echo PublicJavaScriptHref($url_m);
				goto gt_exit;
			}
			
			if ($_SESSION[$SESSION_BACKURL_NAME] != '')
			{
				echo PublicJavaScriptHref(SetURL($url_b, $RQ_ACTION_NAME, $RQ_ACTION_VALUE_SELECT));
			}else{
				echo PublicJavaScriptHref($url_b);
			}
			goto gt_exit;
		}else{
			if ($_POST[$POST_FLAG_NAME] == '')
			{
				$db_query = mysql_query
				('
					SELECT 
						equipment.EID,
						equipment.ENID,
						equipment.EMID,
						equipment.EModel,
						equipment.EGID,
						equipment.EGSGID,
						equipment.ENote,
						TRIM(CONCAT_WS(" ", equipmentname.ENName, equipmentmanufacturer.EMName, equipment.EModel)) AS ENameManufacturerModel
					FROM 
						equipment 
						INNER JOIN equipmentname ON (equipment.ENID = equipmentname.ENID)
						INNER JOIN equipmentmanufacturer ON (equipment.EMID = equipmentmanufacturer.EMID)						
					WHERE 
						equipment.EID = "'.$rq_index.'" AND
						equipment.EFlag > "0"
				');
				if (mysql_num_rows($db_query) == 1)
				{
					$row = mysql_fetch_array($db_query);

					$PO_DATA[1]->SetValue($row['ENID']);
					$PO_DATA[2]->SetValue($row['EMID']);
					$PO_DATA[3]->SetValue($row['EModel']);
					$PO_DATA[4]->SetValue($row['EGID']);
					$PO_DATA[5]->SetValue($row['EGSGID']);
					$PO_DATA[6]->SetValue($row['ENote']);
					$PO_DATA[9]->SetValue($row['ENameManufacturerModel']);
				}
			}
		}

		include($UNIT_FORM_NAME);
		goto gt_exit;
	}

	if ($rq_action == $RQ_ACTION_VALUE_DELETE and GetBit($OP_USER_ACCESS_H[$rq_command], $BACH_DELETE))
	{
		if (!$rq_index)
		{
			AddTextHintObject($HO_HINT, $MLI_HINT_ITEM_INCORRECT);
			include($UNIT_LIST_NAME);
			goto gt_exit;
		}
		$db_query = mysql_query
		('
			SELECT 
				EID
			FROM 
				equipment 
			WHERE 
				EID = "'.$rq_index.'" AND
				EFlag > "0"
		');
		if (mysql_num_rows($db_query) != 1)
		{
			AddTextHintObject($HO_HINT, $MLI_HINT_ITEM_NOTFOUND);
			include($UNIT_LIST_NAME);
			goto gt_exit;
		}

		$db_query = mysql_query
		('
			SELECT 
				EODID
			FROM
				equipmentofdivision
			WHERE
				EID = "'.$rq_index.'" AND
				EODFlag > "0"
		');
		if (mysql_num_rows($db_query) != 0)
		{
			AddTextHintObject($HO_HINT, 'Выбранное оборудование используется в оборудование объекта автоматизации');
			include($UNIT_LIST_NAME);
			goto gt_exit;
		}

		if ($rq_flag != $RQ_FLAG_VALUE_COMPLETE)
		{
			$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TEXT] = $MLI_MESSAGE_QUESTION_ITEM_DELETE;
			$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_FLAG] = $MESSAGE_FLAG_VALUE_QUESTION;
			$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TYPE] = $MESSAGE_TYPE_VALUE_YESNO;
			$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_LINK_B] = $url_b;
			$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_LINK_A] = $url_f;
			$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_SHOW] = true;
			echo PublicJavaScriptHref($url_m);
			goto gt_exit;
		}

		$db_query = mysql_query
		('
			UPDATE 
				equipment
			SET
				EFlag = "0"
			WHERE
				EID = "'.$rq_index.'"
		');
		if (!$db_query)
		{
			$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TEXT] = $MLI_MESSAGE_SQL_EXECUTE_ERROR;
			$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_FLAG] = $MESSAGE_FLAG_VALUE_ERROR;
			$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_TYPE] = $MESSAGE_TYPE_VALUE_OK;
			$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_LINK_B] = $url_b;
			$_SESSION[$SESSION_MESSAGE_NAME][$SESSION_MESSAGE_VALUE_SHOW] = true;
			echo PublicJavaScriptHref($url_m);
			goto gt_exit;
		}

		echo PublicJavaScriptHref($url_b);
		goto gt_exit;
	}

	include($UNIT_LIST_NAME);

	gt_exit:
?>