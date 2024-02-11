<?
	function toupper($text) 
	{
		$text = strtr($text, 'àáâãäå¸æçèéêëìíîðïñòóôõö÷øùúüûýþÿ', 'ÀÁÂÃÄÅ¨ÆÇÈÉÊËÌÍÎÐÏÑÒÓÔÕÖ×ØÙÚÜÛÝÞß');
		return strtoupper($text);
	}

	function tolower($text) {
		$text = strtr($text, 'ÀÁÂÃÄÅ¨ÆÇÈÉÊËÌÍÎÐÏÑÒÓÔÕÖ×ØÙÚÜÛÝÞß', 'àáâãäå¸æçèéêëìíîðïñòóôõö÷øùúüûýþÿ');
		return strtolower($text);
	}

	function date_diff_day($date_a, $date_b)
	{
		$d1 = mktime(0, 0, 0, date('m', strtotime($date_a)),date('d', strtotime($date_a)), date('Y', strtotime($date_a)));
		$d2 = mktime(0, 0, 0, date('m', strtotime($date_b)), date('d', strtotime($date_b)), date('Y', strtotime($date_b)));

		return bcdiv(($d2 - $d1), 86400);
	}

	function wordtoupper($name, $onlower = false)
	{
		$result = '';
		if (strlen($name) > 0)
		{
			if ($onlower) {$result = strtolower($name);}else{$result = $name;}
			$result{0} = strtoupper($name{0});
		}
		return $result;
	}

	function protect_syschar($text)
	{
		return str_replace('"', '&quot;', str_replace('\\', '&#92;', $text));
	}

	function unprotect_syschar($text)
	{
		if (is_array($text))
		{
			foreach ($text as $key => $value) 
			{		
				$text[$key] = unprotect_syschar($value);
			}
			return $text; 
		}else{
			return str_replace('&quot;', '"', str_replace('&#92;', '\\', $text));
		}
	}

	function protect_backslash($text)
	{
		return str_replace(Chr(92), '&#92;', $text);
	}		
	
	function ReplaceTo($text, $replace, $note = '')
	{
		if ($text != '')
		{
			return $text.$note;
		}else{
			return $replace;
		}
	}

	function is_floatex($value)
	{
		return is_numeric(str_replace('.', '', $value));
	}
	
	function FloatFormat($value)
	{
		return str_replace(',', '.', $value);
	}
	function FloatReadFormat($value)
	{
		return str_replace('.', ',', $value);
	}

	function DateFormat($date)
	{
		return date('d.m.Y', strtotime($date));
	}

	function DateSqlFormat($date)
	{
		return date('Y-m-d', strtotime($date));
	}

	function TimeSqlFormat($time)
	{
		return date('H:i:s', strtotime($time));
	}

	function TimeFormat($time)
	{
		return TimeSqlFormat($time);
	}

	function TimeShortFormat($time)
	{
		return date('H:i', strtotime($time));
	}	
	
	function DateToDateTimeBegin($date)
	{
		return $date.' 00:00:00';
	}	
	
	function DateToDateTimeEnd($date)
	{
		return $date.' 23:59:59';
	}	
	
	function IncDate($date, $n)
	{
		return date('Y-m-d', strtotime($date.' +'.$n.' days'));
	}

	function DecDate($date, $n)
	{
		return date('Y-m-d', strtotime($date.' -'.$n.' days'));
	}

	function IncTime($time, $second)
	{
		return date("H:i:s", mktime(0, 0, (((date('H', strtotime($time)) * 60 * 60) + (date('i', strtotime($time)) * 60) + date('s', strtotime($time))) + $second))); 
	}

	function DecTime($time, $second)
	{
		return date("H:i:s", mktime(0, 0, (((date('H', strtotime($time)) * 60 * 60) + (date('i', strtotime($time)) * 60) + date('s', strtotime($time))) - $second))); 
	}

	function IncDateMonth($date, $n)
	{
		return date('Y-m-d', strtotime($date.' +'.$n.' months'));
	}

	function DecDateMonth($date, $n)
	{
		return date('Y-m-d', strtotime($date.' -'.$n.' months'));
	}

	function InsertTo($Source, $Value, $Prefix, $Separator)
	{
		if (strlen($Value) != 0)
		{
			if (strlen($Source) != 0)
			{
				return $Source.$Separator.$Prefix.$Value;
			}else{
				return $Prefix.$Value;
			}
		}else{
			return $Source;
		}
	}

	function is_dateex($in) 
	{
		return (boolean) strtotime($in);
	}

	function IsWord($s)
	{
		$temp_length = strlen($s);
		if ($temp_length < 1):
			return false;
			exit;
		endif;
		if ($temp_length > 5):
			return false;
			exit;
		endif;
		$number_data = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");

		$arr = str_split($s);

		for ($i = 0; $i < sizeof($arr); $i++):
			$e = false;
			for ($j = 0; $j < sizeof($number_data); $j++):
				if ($arr[$i] == $number_data[$j]):
					$e = true;
					break;
				endif;
			endfor;
			if (!$e):
				return false;
				break
				exit;
			endif;
		endfor;

		return true;
	}

	function is_date($str) 
	{
        if(preg_match ("/^(0?[1-9]|[12][0-9]|3[01])[\/\.-](0?[1-9]|1[0-2])[\/\.-](19|20)\d{2}$/", $str))
		{
            $arr = explode(".", $str);  
            $yyyy = $arr[2];     
            $mm = $arr[1];           
            $dd = $arr[0];    
            if(is_numeric($yyyy) && is_numeric($mm) && is_numeric($dd)){if(checkdate($mm, $dd, $yyyy)){return true;} else{return FALSE;}}else{return FALSE;}

        }else{
			return false;
        }

    }

	function is_time($str) 
	{
        if(preg_match ("/^([0-1][0-9]|[2][0-3]):([0-5][0-9]):([0-5][0-9])$/", $str))
		{
            return true;
        }else{
			return false;
        }

    }

	function IsLogin($s)
	{
		$sform = 'qwertyuiopasdfghjklzxcvbnm0123456789-_';

		for ($i = 0; $i < strlen($s); $i++)
		{
			$e = false;
			for ($j = 0; $j < strlen($sform); $j++)
			{
				if ($s{$i} == $sform{$j})
				{
					$e = true;
					break;
				}
			}
			if (!$e)
			{
				return false;
				break;
				exit;
			}
		}
		return true;
	}

	function Odd($value)
	{
		if ($value % 2 == 0)
		{
			return false; 
		}else{
			return true;
		}
	}

	function CheckStringLength($text, $min, $max)
	{
		$len = strlen($text);
		if ($len < $min or $len > $max)
		{
			return false;
		}else{
			return true;
		}
	}

	function GetHostURL()
	{
		$temp = ((!empty($_SERVER['HTTPS']) and $_SERVER['HTTPS'] != 'off') or $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http';
		return $temp.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}	

	function SetURL($url, $key, $param)
	{
		$p = strpos($url, '?');
		if ($p == false)
		{
			$urlp = $url;
			$result = '';
		}else{
			$p = $p + 1;
			$urlp = substr($url, $p);
			$result = substr($url, 0, $p);
		}

		$urlkey = explode('&', $urlp);

		$b = false;
		$k = sizeof($urlkey);
		for ($i = 0; $i < $k; $i++)
		{

			$urlparam = explode('=', $urlkey[$i]);
			$c = sizeof($urlparam);
			if ($i != 0)
			{
				$result = $result.'&';
			}
			if ($c == 2 and $urlparam[0] == $key)
			{
				$result = $result.$urlparam[0].'='.$param;
				$b = true;
			}else{
				$result = $result.$urlkey[$i];
			}
		}
		if (!$b)
		{
			if ($p == false)
			{
				$result = $result.'?';
			}
			if ($k > 0)
			{
				$result = $result.'&';
			}
			$result = $result.$key.'='.$param;
		}
		return $result;
	}

	function is_numericaff($value, $notnull = false)
	{
		$result = false;

		if (is_numeric($value))
		{
			if ($notnull)
			{
				if ($value > 0)
				{
					$result = true;
				}
			}else{
				if ($value >= 0)
				{
					$result = true;
				}
			}
		}
		return $result;
	}

	function GetGETIndex($get, $defval = false)
	{
		$result = $_GET[$get];
		if (!is_numericaff($result))
		{
			$result = $defval;
		}else{
			$result = (int)$result;
		}
		return $result;
	}

	function GetGETNumeric($get, $defval = false)
	{
		$result = $_GET[$get];
		if (!is_numeric($result))
		{
			$result = $defval;
		}else{
			$result = (int)$result;
		}
		return $result;
	}

	function GetGETData($get, $defval = '')
	{
		$result = $_GET[$get];
		if ($result != '')
		{
			$result = trim(strtolower($result));
		}
		return $result;
	}

	function is_htmlcolor($value)
	{
		$result = false;
		$value = strtolower(str_replace('#', '', $value));
		$l = strlen($value);
		if ($l == 3 or $l == 6)
		{
			$value = base_convert($value, 16, 10);
			if (is_numeric($value) and $value >= 0)
			{
				$result = true;
			}
		}
		return $result;
	}

	function CorrectHTMLColor($value)
	{
		return strtoupper('#'.str_replace('#', '', $value));
	}

	function array_h(&$array)
	{
		return sizeof($array) - 1;
	}

	function BoolToInt($value, $deftrue = 1, $deffalse = 0)
	{
		if ($value)
		{
			return $deftrue;
		}else{
			return $deffalse;
		}
	}

	function IntToBool($value, $deftrue = 1)
	{
		if ($value == $deftrue)
		{
			return true;
		}else{
			return false;
		}
	}

	function Inc(&$value, $count = 1)
	{
		$value = $value + $count;
	}

	function Dec(&$value, $count = 1)
	{
		$value = $value - $count;
	}

	function GetBit($value, $index, $digit = 38)
	{
		if ($index > $digit)
		{
			return false;
		}
		$data = (string)base_convert($value, 10, 2);
		return IntToBool((int)$data[(strlen($data) - $index - 1)]);
	}

	function SetBit(&$value, $index, $bool, $digit = 38)
	{
		if ($index > $digit)
		{
			return false;
		}
		$data = (string)base_convert($value, 10, 2);
		$l = strlen($data);
		$z = $digit + 1;
		if ($l > $z)
		{
			$data = substr($data, -$z);
		}
		while ($l < $z)
		{
			$data = '0'.$data;
			Inc($l);
		}
		$data[($l - $index - 1)] = (string)BoolToInt($bool);
		$value = base_convert($data, 2, 10);
		return true;
	}
	
	function FetchArrayToArray(&$row, &$data)
	{
		$key = array_keys($row);
		for ($i = 0; $i < sizeof($key); $i++)
		{
			if (Odd($i))
			{
				$data[$key[$i]] = $row[$key[$i]];
			}
		}	
	}	
	
	function QueryResultToArray(&$query, &$datas)
	{
		$c = 0;
		while ($row = mysql_fetch_array($query))
		{
			$key = array_keys($row);
			for ($i = 0; $i < sizeof($key); $i++)
			{
				if (Odd($i))
				{
					$data[$c][$key[$i]] = $row[$key[$i]];
				}
			}
			Inc($c);
		}
		$datas[] = $data;
	}	
	
	function DecodeURIComponent($text, $charset = 'Windows-1251')
	{
		return iconv('UTF-8', $charset, urldecode($text));
	}	
?>