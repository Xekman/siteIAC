<?
	function Add_task($task_type, $task_content)
	{
		$result = true;
		$db_query = mysql_query
			('
				INSERT INTO
				task
				(
					TTID,
					TCreateDateTime,
					TCountFail,
					TFlag
				)
				VALUES
				(
					"'.$task_type.'",
					"'.time().'",
					"0",
					"1"
				)
			');
			if (!$db_query)
			{
				$result=false;
			}
			$tid = mysql_insert_id();
			foreach($task_content as $key => $value)
				{
					$db_query = mysql_query
					('
						INSERT INTO
						taskcontent
						(
							TID,
							TCName,
							TCValue
						)
						VALUES
						(
							"'.$tid.'",
							"'.$key.'",
							"'.mysql_escape_string($value).'"
						)
					');
					if (!$db_query)
					{
						$result=false;
					}
				}
		return $result;
	}
?>