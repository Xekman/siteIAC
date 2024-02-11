<?
	$UBI_GENDER_MALE = 0;
	$UBI_GENDER_FEMALE = 1;
	$UBI_GENDER_NEUTER = 2;

	$UBI_TYPE_RUBLE = 0;
	$UBI_TYPE_KOPEK = 1;
	$UBI_TYPE_DOLLAR = 2;
	$UBI_TYPE_CENT = 3;
	$UBI_TYPE_EURO = 4;
	$UBI_TYPE_YEAR = 5;
	$UBI_TYPE_MONTH = 6;
	$UBI_TYPE_DAY = 7;
	$UBI_TYPE_HOUR = 8;
	$UBI_TYPE_MINUTE = 9;
	$UBI_TYPE_SECOND = 10;
	$UBI_TYPE_MILLISECOND = 11;
	$UBI_TYPE_LIST = 12;
	$UBI_TYPE_COPY = 13;
	$UBI_TYPE_EXEMPLAR = 14;
	$UBI_TYPE_WEEK = 15;

	function NumberPartReader($numberpart, $gender)
	{
		$MLI_NameOfHundred = array ('', '���', '������', '������', '���������', '�������', '��������', '�������', '���������', '���������');
		$MLI_NameOfTenD = array ('', '�����������', '����������', '����������', '������������', '����������', '�����������', '����������', '������������', '������������');
		$MLI_NameOfTen = array ('', '������', '��������', '��������', '�����', '���������', '����������', '���������', '�����������', '���������');
		$MLI_NameOfOneM = array ('', '����', '���', '���', '������', '����', '�����', '����', '������', '������');
		$MLI_NameOfOneF = array ('', '����', '���', '���', '������', '����', '�����', '����', '������', '������');
		$MLI_NameOfOneN = array ('', '����', '���', '���', '������', '����', '�����', '����', '������', '������');

		$result[0] = $MLI_NameOfHundred[$numberpart[0]];
	 
		if ($numberpart[1] == 1 and $numberpart[2] > 0) 
		{
			$result[0] = trim($result[0].' '.$MLI_NameOfTenD[$numberpart[2]]);
			$result[1] = 0;
		}else{
			$result[0] = trim($result[0].' '.$MLI_NameOfTen[$numberpart[1]]);
			if ($gender == 0)
			{
				$result[0] = trim($result[0].' '.$MLI_NameOfOneM[$numberpart[2]]);
			}
			if ($gender == 1)
			{
				$result[0] = trim($result[0].' '.$MLI_NameOfOneF[$numberpart[2]]);
			}
			if ($gender == 2)
			{
				$result[0] = trim($result[0].' '.$MLI_NameOfOneN[$numberpart[2]]);
			}
			$result[1] = $numberpart[2];
		}
		trim($result[0]);
		return $result;
	}

	function ExtractNumberFullReader($value, $index = 0)
	{
		return $value[$index];
	}
	
	function NumberFullReader($value, $gender, $mode)
	{
		$MLI_NameOfMinus = '�����';
		$MLI_NameOfNull = '����';
		$MLI_NameOfTrillion = array ('���������', '�������', '��������', '��������', '��������', '���������', '���������', '���������', '���������', '���������');
		$MLI_NameOfThousand = array ('�����', '������', '������', '������', '������', '�����', '�����', '�����', '�����', '�����');
		$MLI_NameOfMillion = array ('��������', '������', '�������', '�������', '�������', '��������', '��������', '��������', '��������', '��������');
		$MLI_NameOfBillion = array ('����������', '��������', '���������', '���������', '���������', '����������', '����������', '����������', '����������', '����������');

		$result[0] = '';

		if ($value < 0)
		{
			$value = -$value;
			$prefix = $MLI_NameOfMinus;
		}else{
			$prefix = '';
		}

		if ($value == 0)
		{ 
			$result[0] = $MLI_NameOfNull;
			$result[1] = 0;
			return $result;
		}
		$s = $value;
		$c = strlen($s);
		for ($i = 4; $i >= 0; $i--)
		{

			for ($j = 2; $j >= 0; $j--)
			{
				if ($c > 0)
				{
					$numberfull[$i][$j] = $s{$c - 1};
					$c = $c - 1;
				}else{
					$numberfull[$i][$j] = '0';
				}
			}
		}
		$s = NumberPartReader($numberfull[0], 0);
		$result[1] = $s[1];
		if (strlen($s[0]) != 0)
		{
			$s[0] = $s[0].' '.$MLI_NameOfTrillion[$result[1]];
			$result[0] = trim($result[0].' '.$s[0]);
		}
		$s = NumberPartReader($numberfull[1], 0);
		$result[1] = $s[1];
		if (strlen($s[0]) != 0)
		{
			$s[0] = $s[0].' '.$MLI_NameOfBillion[$result[1]];
			$result[0] = trim($result[0].' '.$s[0]);
		}
		$s = NumberPartReader($numberfull[2], 0);
		$result[1] = $s[1];
		if (strlen($s[0]) != 0)
		{
			$s[0] = $s[0].' '.$MLI_NameOfMillion[$result[1]];
			$result[0] = trim($result[0].' '.$s[0]);
		}
		$s = NumberPartReader($numberfull[3], 1);
		$result[1] = $s[1];
		if (strlen($s[0]) != 0)
		{
			$s[0] = $s[0].' '.$MLI_NameOfThousand[$result[1]];
			$result[0] = trim($result[0].' '.$s[0]);
		}
		$s = NumberPartReader($numberfull[4], $gender);
		$result[1] = $s[1];
		$result[0] = trim($result[0].' '.$s[0]);
		$result[0] = trim($prefix.' '.$result[0]);
		return $result;
	}

	function NumberReader($value, $type, $onlipostfix = false)
	{
		$NameOfRuble = array ('������', '�����', '�����', '�����', '�����', '������', '������', '������', '������', '������');
		$NameOfKopek = array ('������', '�������', '�������', '�������', '�������', '������', '������', '������', '������', '������');
		$NameOfDollar = array ('��������', '������', '�������', '�������', '�������', '��������', '��������', '��������', '��������', '��������');
		$NameOfCent = array ('������', '����', '�����', '�����', '�����', '������', '������', '������', '������', '������');
		$NameOfEuro = array ('����', '����', '����', '����', '����', '����', '����', '����', '����', '����');
		$NameOfYear = array ('���', '���', '����', '����', '����', '���', '���', '���', '���', '���');
		$NameOfMonth = array ('�������', '�����', '������', '������', '������', '�������', '�������', '�������', '�������', '�������');
		$NameOfDay = array ('����', '����', '���', '���', '���', '����', '����', '����', '����', '����');
		$NameOfHour = array ('�����', '���', '����', '����', '����', '�����', '�����', '�����', '�����', '�����');
		$NameOfMinute = array ('�����', '������', '������', '������', '������', '�����', '�����', '�����', '�����', '�����');
		$NameOfSecond = array ('������', '�������', '�������', '�������', '�������', '������', '������', '������', '������', '������');
		$NameOfMillisecond = array ('����������', '�����������', '�����������', '�����������', '�����������', '����������', '����������', '����������', '����������', '����������');
		$NameOfList = array ('������', '����', '�����', '�����', '�����', '������', '������', '������', '������', '������');
		$NameOfCopy = array ('�����', '�����', '�����', '�����', '�����', '�����', '�����', '�����', '�����', '�����');
		$NameOfExemplar = array ('�����������', '���������', '����������', '����������', '����������', '�����������', '�����������', '�����������', '�����������', '�����������');
		$NameOfWeek = array ('������', '������', '������', '������', '������', '������', '������', '������', '������', '������');

		$r0[0] = '';
		$r0[1] = 0;
		$r1 = '';

		if ($type == 0)
		{
			$r0 = NumberFullReader($value, 0);
			$r1 = $NameOfRuble[$r0[1]];
		}
		if ($type == 1)
		{
			$r0 = NumberFullReader($value, 1);
			$r1 = $NameOfKopek[$r0[1]];
		}
		if ($type == 2)
		{
			$r0 = NumberFullReader($value, 0);
			$r1 = $NameOfDollar[$r0[1]];
		}
		if ($type == 3)
		{
			$r0 = NumberFullReader($value, 0);
			$r1 = $NameOfCent[$r0[1]];
		}
		if ($type == 4)
		{
			$r0 = NumberFullReader($value, 0);
			$r1 = $NameOfEuro[$r0[1]];
		}
		if ($type == 5)
		{
			$r0 = NumberFullReader($value, 0);
			$r1 = $NameOfYear[$r0[1]];
		}
		if ($type == 6)
		{
			$r0 = NumberFullReader($value, 0);
			$r1 = $NameOfMonth[$r0[1]];
		}
		if ($type == 7)
		{
			$r0 = NumberFullReader($value, 0);
			$r1 = $NameOfDay[$r0[1]];
		}
		if ($type == 8)
		{
			$r0 = NumberFullReader($value, 0);
			$r1 = $NameOfHour[$r0[1]];
		}
		if ($type == 9)
		{
			$r0 = NumberFullReader($value, 1);
			$r1 = $NameOfMinute[$r0[1]];
		}
		if ($type == 10)
		{
			$r0 = NumberFullReader($value, 1);
			$r1 = $NameOfSecond[$r0[1]];
		}
		if ($type == 11)
		{
			$r0 = NumberFullReader($value, 1);
			$r1 = $NameOfMillisecond[$r0[1]];
		}
		if ($type == 12)
		{
			$r0 = NumberFullReader($value, 0);
			$r1 = $NameOfList[$r0[1]];
		}
		if ($type == 13)
		{
			$r0 = NumberFullReader($value, 1);
			$r1 = $NameOfCopy[$r0[1]];
		}
		if ($type == 14)
		{
			$r0 = NumberFullReader($value, 0);
			$r1 = $NameOfExemplar[$r0[1]];
		}
		if ($type == 15)
		{
			$r0 = NumberFullReader($value, 1);
			$r1 = $NameOfWeek[$r0[1]];
		}

		if ($onlipostfix)
		{
			$r0[0] = $value;
		}
		return trim($r0[0].' '.$r1);
	}

	function GetFloatSeparator()
	{
		$temp = 3/2;
		$result = (string)$temp;
		return $result[1];
	}

	function DoubleToFloatStamp($value)
	{
		$result[0] = (integer)floor($value);
		$s = (string)$value;
		$t = GetFloatSeparator();
		$i = strpos($s, $t);
		if ($i != 0)
		{
			$s = substr($s, $i + 1, strlen($s));
			if (strlen($s) == 1) {$s = $s.'0';}
			$result[1] = (integer)$s;
		}
		else
		{
			$result[1] = 0;
		}
		return $result;
	}

	function MoneyReader($value, $type, $numfr = true)
	{
		$floatstamp = DoubleToFloatStamp($value);

		if ($type == 0)
		{
			$result = NumberReader((string)$floatstamp[0], 0).', '.NumberReader((string)$floatstamp[1], 1, $numfr);
		}
		if ($type == 2)
		{
			$result = NumberReader((string)$floatstamp[0], 2).', '.NumberReader((string)$floatstamp[1], 3, $numfr);
		}
		if ($type == 4)
		{
			$result = NumberReader((string)$floatstamp[0], 4).', '.NumberReader((string)$floatstamp[1], 3, $numfr);
		}
		return $result;
	}

	function TimeReader($time, $short = true)
	{
		$time_ = explode(':', $time);
		$h = $time_[0];
		$m = $time_[1];
		$s = $time_[2];

		if ($short)
		{
			$result = NumberReader($h, 8).' '.NumberReader($m, 9);
		}else{
			$result = NumberReader($h, 8).' '.NumberReader($m, 9).' '.NumberReader($s, 10);
		}
		return $result;
	}

	function DayReader($value)
	{
		return NumberReader($value, 7);
	}

	function DateReader($date) 
	{
		$NameOfMonth = array('','������', '�������', '�����', '������', '���', '����', '����', '�������', '��������', '�������', '������', '�������');
		$NamesOfThousand0 = array('','���������', '�������������');
		$NamesOfThousand = array('','���� ������', '��� ������');
		$NamesOfHandreds0 = array('','������', '����������','����������', '�������������', '����������', '�����������', '����������', '������������', '������������');
		$NamesOfHandreds = array('','���', '������', '������', '���������', '�������', '��������', '�������', '���������', '���������');
		$NamesOfTens00 = array('','�������', '���������', '���������');
		$NamesOfTens0 = array('','��������', '����������', '����������', '����������', '������������', '�������������', '������������', '��������������', '�����������');
		$NamesOfTens = array('','','��������', '��������', '�����', '���������', '����������', '���������', '�����������', '���������');
		$NamesOfFistTen0 = array('','�����������', '�����������', '�����������', '�������������', '�����������', '������������', '�����������', '��������������', '�������������');
		$NamesOfFistTen = array('','�������������', '������������', '������������', '��������������', '������������', '�������������', '������������', '���������������', '��������������');
		$NamesOfUnits0 = array('','������', '������', '������', '���������', '�����', '������', '�������', '�������', '�������');
		$NamesOfUnits = array('','�������', '�������', '��������', '����������', '������', '�������', '��������', '��������', '��������');

		$day=(int)date('d', strtotime(date($date)));
		$month=(int)date('m', strtotime(date($date)));
		$year=(int)date('Y', strtotime(date($date)));

		$tn = floor($day / 10);
		$un = $day % 10;

		if($un == 0) 
		{
			$result = $NamesOfTens00[$tn];
		}else{
			if ($tn == 0) 
			{
				$result = $NamesOfUnits0[$un];
			}else{
				if($tn == 1) 
				{
					$result = $NamesOfFistTen0[$un];
				}else{
					$result = $NamesOfTens[$tn].' '.$NamesOfUnits0[$un];
				}
			}
		}
		
		$result = $result.' '.$NameOfMonth[$month];

		$th = floor($year / 1000);
		$hn = floor($year % 1000 / 100);
		$tn = floor($year % 1000 % 100 / 10);
		$un = $year % 1000 % 100 % 10;
		if (($hn == 0) and ($tn == 0) and ($un == 0)) 
		{
			return $result.' '.$NamesOfThousand0[$th].' ����';
		}else{
			$result.= ' '.$NamesOfThousand[$th];
		}
		if ($hn > 0) 
		{
			if (($tn == 0) and ($un == 0)) 
			{
				return $result.' '.$NamesOfHandreds0[$hn].' ����';
			}else{
				$result = $result.' '.$NamesOfHandreds[$hn];
			}
		}
		if ($un == 0) 
		{
			$result = $result.' '.$NamesOfTens0[$tn];
		}else{
			if ($tn == 0)
			{
				$result = $result.' '.$NamesOfUnits[$un];
			}else{
				if ($tn == 1) 
				{
					$result= $result.' '.$NamesOfFistTen[$un];
				}else{
					$result = $result.' '.$NamesOfTens[$tn].' '.$NamesOfUnits[$un];
				}		
			}
		}
		return $result.' ����';
	}

	function DateReaderShort($date) 
	{
		$NameOfMonth = array('', '������', '�������', '�����', '������', '���', '����', '����', '�������', '��������', '�������', '������', '�������');

		$day = (int)date('d', strtotime(date($date)));
		$month = (int)date('m', strtotime(date($date)));
		$year = (int)date('Y', strtotime(date($date)));

		return $day.' '.$NameOfMonth[$month].' '.$year.' �.';
	}

	function WeekDayReader($date) 
	{
		$NameOfWeekDay= array('�����������', '�����������', '�������', '�����', '�������', '�������', '�������');
	 
		$temp = (int)date('w', strtotime(date($date)));

		return $NameOfWeekDay[$temp];
	}

	function WeekDayReaderShort($date) 
	{
		$NameOfWeekDay= array('��', '��', '��', '��', '��', '��', '��');
	 
		$temp = (int)date('w', strtotime(date($date)));

		return $NameOfWeekDay[$temp];
	}
	
	function MonthReader($date) 
	{
		$NameOfMonth = array('', '������', '�������', '����', '������', '���', '����', '����', '������', '��������', '�������', '������', '�������');
		if (is_date($date))
		{
			$temp = (int)date('m', strtotime($date));
		}else{
			$temp = (int)$date;
		}
		return $NameOfMonth[$temp];
	}
	
	function MonthYearReader($date) 
	{
		$temp = explode('.', $date);
		if (sizeof($temp) == 2)
		{
			$month = (int)$temp[0];
			$year = (int)$temp[1];
		}else{
			$month =(int)$temp[1];
			$year =(int)$temp[2];
		}
		return MonthReader($month).' '.$year.' �.';
	}
?>