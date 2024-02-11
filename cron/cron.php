<?
	$task_per_time = 10;
	$task_delay_if_tasklist_empty = 5;
	$TASK_FILE_ROOT_PATH="C:/WebServers/home/192.169.0.7/www/";
	include($TASK_FILE_ROOT_PATH."units/db.php");
	include($TASK_FILE_ROOT_PATH."units/sysutils.php");
	
	$temp = mysql_connect($DB_HOST, $DB_USER_NAME, $DB_USER_PASSWORD);
	if ($temp == false)
	{
		echo "Невозможно выполнить подключение к серверу базы данных";
		exit;
	}
	
	if (!mysql_select_db($DB_NAME, $temp))
	{
		echo "Невозможно выполнить подключение к базе данных";
		exit;
	}
	
	//проверка неудавшихся задач
	$db_query = mysql_query
	('
		SELECT
			task.TID,
			task.TTID,
			tasktype.TTExecTime,
			task.TCountFail,
			tasktype.TTCountFail
		FROM
			task
			INNER JOIN tasktype ON (task.TTID = tasktype.TTID)
		WHERE
			task.TFlag=2 AND
			(task.TTimeBegin + tasktype.TTExecTime) < UNIX_TIMESTAMP()
	');
	while ($row = mysql_fetch_array($db_query))
	{
	$newstartafter = time() + $row["TTDelayIfFail"];
	$count_fail = $row["TCountFail"] + 1;
	if ($row["TCountFail"] >= $row["TTCountFail"])
	{
		$flag = 4;
	} else
	{
		$flag = 1;
	}
	$db_query1 = mysql_query
	('
	UPDATE 
		task
	SET
		TTimeBegin = 0,
		TTimeEnd = 0,
		TNewStartAfter = '.$newstartafter.',
		TCountFail = '.$count_fail.',
		TFlag = '.$flag.'
	WHERE
		TID = '.$row["TID"] .'
	');
	}
		
	
	//периодические задачи
	$db_query2 = mysql_query
	('
	SELECT
		tasktype.TTID,
		tasktype.TTPeriodicity,
		tasktype.TTExecTime,
		tasktype.TTCountFail
	FROM
		tasktype
	WHERE
		tasktype.TTPeriodicity > "0"
	');
	while ($row2 = mysql_fetch_array($db_query2))
	{
		$db_query3 = mysql_query
		('
			SELECT
				task.TID,
				task.TTID,
				task.TCreateDateTime,
				task.TFlag
			FROM
				task
			WHERE
				task.TTID = "'.$row2['TTID'].'"
			 ORDER BY
				TCreateDateTime DESC
            LIMIT 0,1
		');
		if (mysql_num_rows($db_query3) != 0)
		{
			$row3 = mysql_fetch_array($db_query3);
			if (($row3['TFlag'] == 3) OR ($row3['TFlag'] == 4))
			{
				$starttime = time() + $row2['TTPeriodicity'];
				$db_query4 = mysql_query
				('
					INSERT INTO
					task
					(
						TTID,
						TCreateDateTime,
						TCountFail,
						TNewStartAfter,
						TFlag
					)
					VALUES
					(
						"'.$row2['TTID'].'",
						"'.time().'",
						"0",
						"'.$starttime.'",
						"1"
					)
				');
			}
		} 
		else
		{
			$db_query3 = mysql_query
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
					"'.$row['TTID'].'",
					"'.time().'",
					"0",
					"1"
				)
			');
		}
	}
	
	
	for ($i = 1; $i <= $task_per_time; $i++) 
	{
	$task_result = false;
	$db_query = mysql_query
	(' 
	SELECT
		task.TID,
		task.TTID,
		tasktype.TTExecTime,
		tasktype.TTCountFail,
		tasktype.TTPriority,
		tasktype.TTName,
		task.TCountFail,
		tasktype.TTDelayIfFail
	FROM
		task
		INNER JOIN tasktype ON (task.TTID = tasktype.TTID)
	WHERE
		task.TFlag=1 AND
		task.TNewStartAfter<='.time().'
	ORDER BY
		tasktype.TTPriority,
		task.TCreateDateTime,
		task.TCountFail
	LIMIT 0, 1
	');
	$row = mysql_fetch_array($db_query);
	
	
	$db_query1 = mysql_query
		('
		UPDATE
			task
		SET
			TTimeBegin = "'.time().'",
			TFlag = "2",
			TTimeEnd = "0",
			TNewStartAfter = "0"
		WHERE
			TID = "'.$row["TID"].'"
		');

	if ($row['TTID']==1)
	{
		require_once($TASK_FILE_ROOT_PATH."cron/mail.php");
		$db_query1 = mysql_query
		('
		SELECT TCValue AS mailto  FROM taskcontent WHERE  TID = "'.$row['TID'].'" AND TCName = "mail_to"
		');
		$row1 = mysql_fetch_array($db_query1);
		$db_query2 = mysql_query
		('
		SELECT TCValue AS mailfrom FROM taskcontent WHERE  TID = "'.$row['TID'].'" AND TCName = "mail_from"
		');
		$row2 = mysql_fetch_array($db_query2);
		$db_query3 = mysql_query
		('
		SELECT TCValue AS replyto  FROM taskcontent WHERE  TID = "'.$row['TID'].'" AND TCName = "reply_to"
		');
		$row3 = mysql_fetch_array($db_query3);
		$db_query4 = mysql_query
		('
		SELECT TCValue AS mailsubject  FROM taskcontent WHERE  TID = "'.$row['TID'].'" AND TCName = "mail_subject"
		');
		$row4 = mysql_fetch_array($db_query4);
		$db_query5 = mysql_query
		('
		SELECT TCValue AS mailtext  FROM taskcontent WHERE  TID = "'.$row['TID'].'" AND TCName = "mail_text"
		');
		$row5 = mysql_fetch_array($db_query5);
		$db_query6 = mysql_query
		('
		SELECT TCValue AS mailfile  FROM taskcontent WHERE  TID = "'.$row['TID'].'" AND TCName = "mail_file"
		');
		$row6 = mysql_fetch_array($db_query6);
		
		if (!SendMail(unserialize($row1['mailto']), unserialize($row2['mailfrom']), unserialize($row3['replyto']), $row4['mailsubject'], $row5['mailtext'], unserialize($row6['mailfile']), $TASK_FILE_ROOT_PATH))
		{
			$task_result = false;
		} else
		{
			$task_result = true;
		}
	}
	if ($row['TTID']==3)
	{
		require_once($TASK_FILE_ROOT_PATH."cron/checksupport.php");
	}
	if ($task_result == false)
		{
			$new_time = time()+$row['TTDelayIfFail'];
			$count_fail = $row['TCountFail'] + 1;
			if ($count_fail >= $row['TTCountFail'])
			{
			$flag = 4;
			} else
			{
			$flag = 1;
			}
			$db_query2 = mysql_query
			('
				UPDATE
					task
				SET
					TFlag = "'.$flag.'",
					TNewStartAfter = "'.$new_time.'",
					TCountFail = "'.$count_fail.'"
				WHERE
					TID = "'.$row["TID"].'"
			');
			
		} 
	else
		{
			$db_query2 = mysql_query
				('
					UPDATE
						task
					SET
						TFlag = "3",
						TTimeEnd = "'.time().'"
					WHERE
						TID = "'.$row["TID"].'"
				');
		}
	if (mysql_num_rows($db_query) == 0)
	{
		sleep($task_delay_if_tasklist_empty);
	}
	
	}
	mysql_close($temp);
?>
	