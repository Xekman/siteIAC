<?
	function _ml_setlength(&$a, $size, $value = 0)
	{
		if (!is_array($a))
		{
			$a = array();
		}
		$len = sizeof($a);
		if ($size > $len)
		{
			for ($i = $len; $i < $size; $i++)
			{
				$a[$i] = $value;
			}
		}else{
			for ($i = $size; $i < $len; $i++)
			{
				unset($a[$i]);
			}			
		}
	}
	
	function _ml_str2num($s, &$nInt, &$nFrac, &$nSign)
	{
		$l = strlen($s);

		_ml_setlength($nInt, 0);
		_ml_setlength($nFrac, 0);
		
		if ($l == 0)
		{
			break;
		}
		
		if ($s[0] == '-')
		{
			$s = substr($s, 1, ($l - 1));
			$l = $l - 1;
			$nSign = false;
		}else{
			$nSign = true;
		}
		
		$j = strpos($s, '.');
		
		if ($j != false)
		{
			for ($i = 0; $i < $j; $i++)
			{
				$nInt[$i] = $s[$j - $i - 1];
			}
			for ($i = 0; $i < $l - $j - 1; $i++)
			{
				$nFrac[$i] = $s[$l - $i - 1];
			}
		}else{
			for ($i = 0; $i < $l; $i++)
			{
				$nInt[$i] = $s[$l - $i - 1];			
			}
		}
	}	

	function _ml_num2array(&$nInt, &$nFrac, &$nSign, &$a)
	{
		$result = sizeof($nFrac);
	
		_ml_setlength($a, sizeof($nInt) + $result);
		
		for ($i = 0; $i < sizeof($a); $i++)
		{
			if ($i < $result)
			{
				$a[$i] = $nFrac[$i];
			}else{
				$a[$i] = $nInt[$i - $result];
			}
		}
		return $result;
	}
	
	function _ml_multiplyarray(&$a1, &$a2, &$a)
	{	
		for ($i = sizeof($a2) - 1; $i >= 0; $i--)
		{
			for ($j = sizeof($a1) - 1; $j >= 0; $j--)
			{
				$a[$j + $i] = $a[$j + $i] + ($a2[$i] * $a1[$j]);
			}
		}
		do
		{
			$b = true;
			for ($i = 0; $i < sizeof($a); $i++)
			{
				if ($a[$i] > 9)
				{
					$b = false;
					if (sizeof($a) > $i + 1)
					{
						_ml_setlength($a, sizeof($a) + 1);
					}
					$a[$i + 1] = $a[$i + 1] + 1;
					$a[$i] = $a[$i] - 10;
				}
			}
		} while (!$b);
	}
	
	function _ml_array2num(&$nInt, &$nFrac, &$nSign, &$a, $frac, $sign)
	{	
		_ml_setlength($nFrac, $frac);
		_ml_setlength($nInt, sizeof($a) - $frac);
		for ($i = 0; $i < sizeof($a); $i++)
		{
			if ($i < $frac)
			{
				$nFrac[$i] = $a[$i];
				
			}else{
				$nInt[$i - $frac] = $a[$i];
			}
		}
		$nSign = $sign;
	}
	
	function _ml_disposenum(&$nInt, &$nFrac, &$nSign)
	{
		_ml_setlength($nInt, 0);
		_ml_setlength($nFrac, 0);
	}		
	
	function _ml_num2str(&$nInt, &$nFrac, &$nSign)
	{
		$result = '';
		for ($i = 0; $i < sizeof($nInt); $i++)
		{
			$result = (string)($nInt[$i]).$result;
		}
		if (sizeof($nFrac) != 0)
		{
			for ($i = 0; $i < sizeof($nFrac); $i++)
			{
				$s = (string)($nFrac[$i]).$s;
			}
			
			$result = $result.'.'.$s;
		}
		while (strlen($result) > 1 and $result[0] == '0')
		{
			$result = substr_replace($result, '', 0, 1);	
		}
		if (strpos($result, '.') != false)
		{
			while (strlen($result) > 1 and $result[strlen($result) - 1] == '0')
			{
				$result = substr_replace($result, '', strlen($result) - 1, 1);	
			}
		}
		if (!$nSign)
		{
			$result = '-'.$result;
		}
		_ml_disposenum($nInt, $nFrac, $nSign);
		return $result;
	}
	
	function MathLongPower($first, $second)
	{
		$j = (int)($second);
		if ($j == 0)
		{
			$result = '1';
			return $result;
		}else{
			if ($j == 1)
			{
				$result = $first;
				return $result;
			}
		}
		_ml_str2num($first, $nInt1, $nFrac1, $nSign1);
		$c = _ml_num2array($nInt1, $nFrac1, $nSign1, $a1);
		_ml_setlength($a, 0);
		_ml_setlength($a2, 0);
		$a2 = $a1;
		for ($i = 1; $i < $j; $i++)
		{
			_ml_setlength($a, 0);
			_ml_setlength($a, sizeof($a1) + sizeof($a2) + 1);
			_ml_multiplyarray($a1, $a2, $a);
			_ml_setlength($a2, 0);
			$a2 = $a;
		}
		_ml_setlength($a1, 0);
		_ml_setlength($a2, 0);
		$c = $c * $j;
		if ($nSign1)
		{
			_ml_array2num($nInt1, $nFrac1, $nSign1, $a, $c, true);
		}else{
			if ($j % 2 != 0)
			{
				_ml_array2num($nInt1, $nFrac1, $nSign1, $a, $c, false);
			}else{
				_ml_array2num($nInt1, $nFrac1, $nSign1, $a, $c, true);
			}
		}
		_ml_setlength($a, 0);
		
		return _ml_num2str($nInt1, $nFrac1, $nSign1);
	}
	
	function _ml_multiplynum(&$nInt1, &$nFrac1, &$nSign1, &$nInt2, &$nFrac2, &$nSign2)
	{
		$i = _ml_num2array($nInt1, $nFrac1, $nSign1, $a1) + _ml_num2array($nInt2, $nFrac2, $nSign2, $a2);
		_ml_setlength($a, sizeof($a1) + sizeof($a2) + 1);
		_ml_multiplyarray($a1, $a2, $a);
		_ml_setlength($a1, 0);
		_ml_setlength($a2, 0);
		_ml_array2num($nInt1, $nFrac1, $nSign1, $a, $i, ($nSign1 == $nSign2));
		_ml_disposenum($nInt2, $nFrac2, $nSign2);
		_ml_setlength($a, 0);
	}
	
	function MathLongMUL($first, $second)
	{
		_ml_str2num($first, $nInt1, $nFrac1, $nSign1);
		_ml_str2num($second, $nInt2, $nFrac2, $nSign2);
		_ml_multiplynum($nInt1, $nFrac1, $nSign1, $nInt2, $nFrac2, $nSign2);
		return _ml_num2str($nInt1, $nFrac1, $nSign1);
	}
	
	function _ml_alignnum(&$nInt1, &$nFrac1, &$nSign1, &$nInt2, &$nFrac2, &$nSign2)
	{
		$i1 = sizeof($nInt1);
		$i2 = sizeof($nInt2);
		if ($i1 > $i2)
		{
			_ml_setlength($nInt2, $i1);
		}
		if ($i2 > $i1)
		{
			_ml_setlength($nInt1, $i2);
		}
		$i1 = sizeof($nFrac1);
		$i2 = sizeof($nFrac2);
		
		if ($i1 > $i2)
		{
			_ml_setlength($nFrac2, $i1);
			for ($i = $i1 - 1; $i >= 0; $i--)
			{
				if ($i - ($i1 - $i2) > 0)
				{
					$nFrac2[$i] = $nFrac2[$i - ($i1 - $i2)];
				}else{
					$nFrac2[$i] = 0;
				}
			}
		}
		if ($i2 > $i1)
		{
			_ml_setlength($nFrac1, $i2);
			for ($i = $i2 - 1; $i >= 0; $i--)
			{
				if ($i - ($i2 - $i1) > 0)
				{
					$nFrac1[$i] = $nFrac1[$i - ($i2 - $i1)];
				}else{
					$nFrac1[$i] = 0;
				}
			}
		}
	}
	
	function _ml_subinteger(&$a1, &$a2)
	{
		$result = 0;
		if (sizeof($a1) == 0)
		{
			return $result;
		}
		for ($i = 0; $i < sizeof($a1); $i++)
		{
			$a1[$i] = $a1[$i] - $a2[$i];
		}
		do
		{
			$b = true;
			for ($i = 0; $i < sizeof($a1); $i++)
			{
				if ($a1[$i] < 0)
				{
					$b = false;
					if ($i == sizeof($a1) - 1)
					{
						$result = -1;
						$a1[$i] = $a1[$i] + 10;
						$b = true;
					}else{
						$a1[$i + 1] = $a1[$i + 1] - 1;
						$a1[$i] = $a1[$i] + 10;
					}
				}
			}
		} while (!$b);
		return $result;
	}
	
	function _ml_assignnum(&$nInt1, &$nFrac1, &$nSign1, &$nInt2, &$nFrac2, &$nSign2)
	{
		_ml_setlength($nInt1, sizeof($nInt2));
		for ($i = 0; $i <sizeof($nInt2); $i++)
		{
			$nInt1[$i] = $nInt2[$i];
		}
		_ml_setlength($nFrac1, sizeof($nFrac2));
		for ($i = 0; $i < sizeof($nFrac2); $i++)
		{
			$nFrac1[$i] = $nFrac2[$i];
		}
		$nSign1 = $nSign2;
	}
	
	function _ml_subnum(&$nInt1, &$nFrac1, &$nSign1, &$nInt2, &$nFrac2, &$nSign2)
	{
		_ml_alignnum($nInt1, $nFrac1, $nSign1, $nInt2, $nFrac2, $nSign2);
		$i = _ml_subinteger($nFrac1, $nFrac2);
		$nInt1[0] = $nInt1[0] + $i;
		_ml_disposenum($nInt, $nFrac, $nSign);
		_ml_assignnum($nInt, $nFrac, $nSign, $nInt1, $nFrac1, $nSign1);
		$i = _ml_subinteger($nInt1, $nInt2);
		if ($i < 0)
		{
			_ml_subinteger($nInt2, $nInt);
			_ml_assignnum($nInt1, $nFrac1, $nSign1, $nInt2, $nFrac2, $nSign2);
		}else{
			_ml_disposenum($nInt2, $nFrac2, $nSign2);
		}
	}	
	
	function _ml_suminteger(&$a1, &$a2)
	{
		$result = 0;
		if (sizeof($a1) == 0)
		{
			return $result;
		}
		for ($i = 0; $i < sizeof($a1); $i++)
		{
			$a1[$i] = $a1[$i] + $a2[$i];
		}
		do
		{
			$b = true;
			for ($i = 0; $i < sizeof($a1); $i++)
			{
				if ($a1[$i] > 9)
				{
					$b = false;
					if ($i == sizeof($a1) - 1)
					{
						$result = 1;
						$a1[$i] = $a1[$i] - 10;
						$b = true;
					}else{
						$a1[$i + 1] = $a1[$i + 1] + 1;
						$a1[$i] = $a1[$i] - 10;
					}
				}
			}
		} while (!$b);
		return $result;		
	}
	
	function _ml_sumnum(&$nInt1, &$nFrac1, &$nSign1, &$nInt2, &$nFrac2, &$nSign2)
	{
		_ml_alignnum($nInt1, $nFrac1, $nSign1, $nInt2, $nFrac2, $nSign2);
		$i = _ml_suminteger($nFrac1, $nFrac2);
		$nInt1[0] = $nInt1[0] + $i;
		$i = _ml_suminteger($nInt1, $nInt2);
		if ($i > 0)
		{
			_ml_setlength($nInt1, sizeof($nInt1) + 1);
			$nInt1[sizeof($nInt1) - 1] = $i;
		}
		_ml_disposenum($nInt2, $nFrac2, $nSign2);
	}
	
	function _ml_sumnums(&$nInt1, &$nFrac1, &$nSign1, &$nInt2, &$nFrac2, &$nSign2)
	{
		if ($nSign1 and $nSign2)
		{
			_ml_sumnum($nInt1, $nFrac1, $nSign1, $nInt2, $nFrac2, $nSign2);			
			$nSign1 = true;
		}else{
			if (!$nSign1 and !$nSign2)
			{
				_ml_sumnum($nInt1, $nFrac1, $nSign1, $nInt2, $nFrac2, $nSign2);
				$nSign1 = false;
			}else{
				if (!$nSign1 and $nSign2)
				{
					_ml_subnum($nInt2, $nFrac2, $nSign2, $nInt1, $nFrac1, $nSign1);
					_ml_assignnum($nInt1, $nFrac1, $nSign1, $nInt2, $nFrac2, $nSign2);
				}else{
					_ml_subnum($nInt1, $nFrac1, $nSign1, $nInt2, $nFrac2, $nSign2);
				}
			}
		}
	}
	
	function MathLongSUM($first, $second)
	{
		_ml_str2num($first, $nInt1, $nFrac1, $nSign1);
		_ml_str2num($second, $nInt2, $nFrac2, $nSign2);
		_ml_sumnums($nInt1, $nFrac1, $nSign1, $nInt2, $nFrac2, $nSign2);		
		return _ml_num2str($nInt1, $nFrac1, $nSign1);
	}

	function MathLongSUB($first, $second)
	{
		_ml_str2num($first, $nInt1, $nFrac1, $nSign1);
		_ml_str2num($second, $nInt2, $nFrac2, $nSign2);
		$nSign2 = !$nSign2;
		_ml_sumnums($nInt1, $nFrac1, $nSign1, $nInt2, $nFrac2, $nSign2);
		return _ml_num2str($nInt1, $nFrac1, $nSign1);
	}	
	
	function _ml_dupchr($char, $count)
	{
		$result = '';
		if ($count > 0)
		{ 
			$result = str_pad($result, $count, $char);
		}
		return $result;
	}
	
	function _ml_strcmp($x, $y)
	{
		$i = strlen($x);
		$j = strlen($y);
		if ($i == 0)
		{
			$result = $j;
			return $result;
		}
		if ($j == 0)
		{
			$result = $i;
			return $result;
		}
		if ($x[0] == '-')
		{
			if ($y[0] == '-')
			{			
				$x = substr($x, 1, $i);
				$y = substr($y, 1, $j);		
			}else{
				$result = -1;
				return $result;
			}
		}else{
			if ($y[0] == '-')
			{
				$result = 1;
				return $result;
			}
		}
		$result = $i - $j;
		if ($result == 0)
		{
			$result = strcasecmp($x, $y);
		}
		return $result;
	}
	
	function _ml_strdiv($x, $y)
	{
		$result = '0';
		$r = '0';
		$i = strlen($x);
		$j = strlen($y);
		$s = false;
		$v = false;
		if ($i == 0)
		{
			return $result;
		}
		if ($j == 0 or $y[0] == '0')
		{
			$result = '';
			$r = '';
			return $result;
		}
		if ($x[0] == '-')
		{
			$i = $i - 1;
			$v = true;
			$x = substr($x, 1, $i);
			
			if ($y[0] == '-')
			{
				$j = $j - 1;
				$y = substr($y, 1, $j);		
			}else{
				$s = true;
			}
		}else{
			if ($y[0] == '-')
			{
				$j = $j - 1;
				$y = substr($y, 1, $j);		
				$s = true;
			}
		}
		$i = $i - $j;
		if ($i < 0)
		{
			$r = $x;
			return $result;
		}
		$t2 = _ml_dupchr('0', $i);
		$t1 = $y.$t2;
		$t2 = '1'.$t2;
		$max = strlen($t1);
		while (strlen($t1) >= $j)
		{
			while (_ml_strcmp($x, $t1) >= 0)
			{
				$x = MathLongSUB((string)$x, (string)$t1);
				$result = MathLongSUM((string)$result, (string)$t2);
			}
			$t1 = substr_replace($t1, '', strlen($t1) - 1, 1);	
			$t2 = substr_replace($t2, '', strlen($t2) - 1, 1);	
		}	
		$r = $x;
		if ($s)
		{
			if ($result[0] != '0')
			{
				$result = '-'.$result;	
			}
		}
		if ($v)
		{
			if ($r[0] != '0')
			{
				$r = '-' + $r;
			}
		}
		return $result;
	}
	
	function _ml_mul10($first, $second)
	{
		if (strpos($first, '.') == false)
		{
			$s = '';
			for ($i = 0; $i < $second; $i++)
			{
				$s = $s.'0';
			}
			$result = $first.$s;
		}else{
			$s = '';
			$j = strlen($first) - strpos($first, '.') - 1;

			if (($second - $j) > 0)
			{
				for ($i = 0; $i < $second - $j; $i++)
				{
					$s = $s.'0';
				}
			}
			$first = $first.$s;
			$j = strpos($first, '.');
			$first = str_replace('.', '', $first);
			$z = $j + $second;
			$first = substr($first, 0, $z).'.'.substr($first, $z, strlen($first) - $z);
			while (strlen($first) > 0 and $first[strlen($first) - 1] == '0')
			{
				$first = substr_replace($first, '', strlen($first) - 1, 1);	
			}
			while (strlen($first) > 0 and $first[strlen($first) - 1] == '.')
			{
				$first = substr_replace($first, '', strlen($first) - 1, 1);	
			}
			$result = $first;
		}
		return $result;
	}
	
	function _ml_div10($first, $second)
	{
		$s = '';
		for ($i = 0; $i <= $second; $i++)
		{
			$s = $s.'0';
		}
		$s = $s.$first;

		$z = strlen($s) - $second;
		$s = substr($s, 0, $z).'.'.substr($s, $z, strlen($s) - $z);
		while (strlen($s) > 0 and $s[0] == '0')
		{
			$s = substr_replace($s, '', 0, 1);
		}
		if (strpos($s, '.') != false)
		{
			while (strlen($s) > 0 and $s[strlen($s) - 1] == '0')
			{
				$s = substr_replace($s, '', strlen($s) - 1, 1);
			}
		}
		if (strlen($s) > 0 and $s[strlen($s) - 1] == '.')
		{
			$s = substr_replace($s, '', strlen($s) - 1, 1);
		}		
		return $s;
	}
	
	function MathLongDIV($first, $second, $precision = 0)
	{
		$first = _ml_mul10($first, $precision);
		return _ml_div10(_ml_strdiv($first, $second), $precision);
	}	
?>