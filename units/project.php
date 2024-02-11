<?	
	function PublicJavaScript($text)
	{
		return '<script type="text/javascript">'.$text.'</script>';
	}
	
	function PublicJavaScriptHref($url)
	{
		return PublicJavaScript('setHref(\''.$url.'\')');
	}
	
	function PublicJavaScriptOpenHref($url)
	{
		return PublicJavaScript('openHref(\''.$url.'\')');
	}
	
	function UpdatePageJavaScript($id = 'update', $timeout = 15)
	{
		return PublicJavaScript('
			var updatetime = '.($timeout + 1).'; var updateobject = document.getElementById("'.$id.'");
			function _update()
			{
				if (updatetime > 0) 
				{
					updatetime = updatetime - 1;
				}
				updateobject.innerHTML = \'Ñòðàíèöà îáíîâèòñÿ ÷åðåç <a title="Îáíîâèòü ñòðàíèöó" href=""><b>\' + updatetime + \'</b></a> ñåê.\';
				if (updatetime == 0) 
				{
					document.location.href = "";
				}
				if (updatetime > 0) 
				{
					setTimeout("_update()", 1000);
				}
			}		
			_update();
		');
	}	
	
	function GetDisabled($value = false)
	{
		if ($value != true)
		{
			return 'disabled';
		}
	}

	function GetButtonSeparator(&$show = true)
	{
		if ($show)
		{
			$show = false;
			return '<img src="img/separator_a.png" align="top"> ';
		}
	}
	function GetHint($text, $icon = 4)
	{
		if ($icon == 0) {$temp_icon_prefix = 'information16';}
		if ($icon == 1) {$temp_icon_prefix = 'warning16';}
		if ($icon == 2) {$temp_icon_prefix = 'error16';}
		if ($icon == 3) {$temp_icon_prefix = 'question16';}
		if ($icon == 4) {$temp_icon_prefix = 'information16';}
		$result = '
			<table width="100%" class="hint" >
				<tr>
					<td class="hint_left" width="16" align="left" valign="top">
						<img align="top" src="img/msgbox/'.$temp_icon_prefix.'.png">
					</td>
					<td class="hint_center" valign="middle" align="justify">
						<FONT class="hint">
							'.$text.'
						</FONT>
					</td>
				</tr>
			</table>
		';
		return $result;
	}

	function GetNote($text, $width = 1030)
	{
		if ($text == '')
		{
			return '';
		}
		return '
			<table class="note" width="'.$width.'">
				<tr>
					<td class="note_header"></td>
				</tr>
				<tr>
					<td class="note" width="100%" align="justify">
						<FONT class="note">
						'.$text.'
						</FONT>
					</td>
				</tr>
			</table>
		';
	}
	
	function GetListNote($text, $width = '100%')
	{
		if ($text == '')
		{
			return '';
		}
		return '
			<table width="'.$width.'">
				<tr>
					<td class="list_note_header"></td>
				</tr>
				<tr>
					<td class="list_note" width="100%" align="justify">
						'.$text.'
					</td>
				</tr>
			</table>
		';
	}

	function GetNoteEx($text, $width = 1030, $max = 128)
	{
		$result = '';

		$k = round(($width * $max) / 1030);

		if (strlen($text) > $k)
		{
			$result = GetNote($text, $width);
		}

		return $result;
	}

	function GetOfficeImgFromExpansion($expansion)
	{
		$expansion = strtolower($expansion);
		$result = '';
		if ($expansion == '.xls')
		{
			$result = '<img title="Microsoft Office Excel 2003" src="img/expansion/xls.png">';
		}
		if ($expansion == '.doc')
		{
			$result = '<img title="Microsoft Office Word 2003" src="img/expansion/doc.png">';
		}
		if ($expansion == '.xlsx')
		{
			$result = '<img title="Microsoft Office Excel 2007/2010" src="img/expansion/xlsx.png">';
		}
		if ($expansion == '.docx')
		{
			$result = '<img title="Microsoft Office Word 2007/2010" src="img/expansion/docx.png">';
		}
		if ($expansion == '.txt')
		{
			$result = '<img title="Òåêñòîâûé äîêóìåíò" src="img/expansion/txt.png">';
		}		
		return $result;
	}

	function TextToSmile($text)
	{
		$result = $text;
		
		$result = str_replace(':)', '*SMILE*', $result);
		$result = str_replace(':(', '*SAD*', $result);
		$result = str_replace(';)', '*WINK*', $result);
		$result = str_replace(':P', '*BLUM*', $result);
		$result = str_replace(':D', '*BIG GRIN*', $result);
		$result = str_replace('O_O', '*SHOK*', $result);
		$result = str_replace(':*', '*GIRL KISS*', $result);
		$result = str_replace(':/', '*HMM*', $result);
		$result = str_replace(':[', '*BLUSH*', $result);
		$result = str_replace(':\'(', '*CRAY*', $result);
		$result = str_replace('[:)', '*MUSIC*', $result);
		$result = str_replace(':X', '*BAD*', $result);
		$result = str_replace('%)', '*WACKO*', $result);
		$result = str_replace('*THUMBS UP*', '*GOOD*', $result);
		
		$path = 'img/smile';
		$dhwnd = opendir($path);
		while (($file = readdir($dhwnd)))
		{
			$file_path = $path.'\\'.$file;
			if (file_exists($file_path) and !is_dir($file_path) and strtolower(GetFileExpansion($file)) == '.gif')
			{
				$result = str_replace('*'.strtoupper(substr($file, 0, -4)).'*', '<img class="mymessage_smile" src="'.$path.'/'.$file.'">', $result);
			}
		}
		closedir($dhwnd); 		
		
		return $result;
	}	

	function GetFIORead($name1, $name2, $name3)
	{
		$result = '';
		if ($name2 != '')
		{
			$result = trim($name2);
		}
		if ($name3 != '')
		{
			$result = trim($result.' '.$name3);
		}
		if ($result == '')
		{
			$result = trim($name1);
		}		
		return $result;
	}	
	
	function GetFIOShort($name1, $name2, $name3)
	{
		$result = trim($name1);
		if ($name2 != '')
		{
			$result = trim($result.' '.$name2[0].'.');
		}	
		if ($name3 != '')
		{
			$result = trim($result.' '.$name3[0].'.');
		}	
		return $result;		
	}

	function GetFIOShortBack($name1, $name2, $name3)
	{
		$result = trim($name1);
		if ($name3 != '')
		{
			$result = trim($name3[0].'. '.$result);
		}	
		if ($name2 != '')
		{
			$result = trim($name2[0].'. '.$result);
		}	
		return $result;		
	}

	function GetFullNameWorkPost($fullname, $workpost)
	{
		$result = $fullname;
		if ($workpost != '')
		{
			$result = $result.' ('.strtolower($workpost).')';
		}
		return $result;
	}

	function GetINNKPP($inn, $kpp)
	{
		$result = '';
		if ($inn != '')
		{
			if ($kpp == '')
			{
				$kpp = '-';
			}
			$result = $inn.'/'.$kpp;
		}
		return $result;
	}

	function GetSNILS($snils)
	{
		if (strlen($snils) != 11)
		{
			$result = $snils;
		}else{
			$result = substr($snils, 0, 3).'-'.substr($snils, 3, 3).'-'.substr($snils, 6, 3).' '.substr($snils, -2);
		}
		return $result;
	}
	
	function CreateBarCode35($rid)
	{
		$version = 1;

		if (!is_numeric($rid) or $rid < 0)
		{
			$rid = 0;
		}

		$result = $rid.$version;

		$result = strtoupper(base_convert($result, 10, 35));

		$result = str_replace('A', 'Z', $result);

		while (strlen($result) < 8)
		{
			$result = '0'.$result;
		}

		return $result;
	}
	
	function IsBarCode35($text)
	{
		$result = false;
		$l = strlen($text);
		if ($l == 8)
		{
			$text = strtoupper($text);
			if (preg_match('/^([0-9ÉÖÓÊÅÍÃØÙÇÛÂÀÏÐÎËÄß×ÑÌÈÒÜ]{8,10}|[0-9B-Z]{8,10})$/', $text))
			{
				$result = true;
			}
		}
		return $result;
	}	
	
	function ExtractBarCode35($text)
	{
		$result = false;
		$text = strtr($text, 'ÉÖÓÊÅÍÃØÙÇÛÂÀÏÐÎËÄß×ÑÌÈÒÜ', 'QWERTYUIOPSDFGHJKLZXCVBNM');
		$text = strtolower($text);
		$text = str_replace('z', 'a', $text);
		$text = base_convert($text, 35, 10);
		
		while (strlen($text) < 8)
		{
			$text = '0'.$text;
		}
		$version = (int)(substr($text, -1, 1));

		if ($version == 1)
		{
			$result = (int)(substr($text, 0, strlen($text) - 1));
		}			
		return $result;
	}

	function GetRuleName($name)
	{
		$result = '';
		$temp_name = explode('-', $name);
		for ($i = 0; $i < sizeof($temp_name); $i++)
		{
			$temp_trim_name = trim($temp_name[$i]);
			if ($i == 0)
			{
				$result = wordtoupper($temp_trim_name, true);
			}else{
				$result = $result.'-'.wordtoupper($temp_trim_name, true);
			}
		}
		$temp_name = explode(' ', $result);
		for ($i = 0; $i < sizeof($temp_name); $i++)
		{
			$temp_trim_name = trim($temp_name[$i]);
			if ($i == 0)
			{
				$result = wordtoupper($temp_trim_name);
			}else{
				$result = $result.' '.wordtoupper($temp_trim_name);
			}
		}
		$temp_name = explode('.', $result);
		for ($i = 0; $i < sizeof($temp_name); $i++)
		{
			$temp_trim_name = trim($temp_name[$i]);
			if ($i == 0)
			{
				$result = wordtoupper($temp_trim_name);
			}else{
				$result = $result.'. '.wordtoupper($temp_trim_name);
			}
		}
		return trim($result);
	}

	function GetFullPhone($prefix, $phone)
	{
		$result = '';

		if (strlen($prefix) > 0)
		{
			$result = $result.'+7 ('.$prefix.')';
		}

		$len = strlen($phone);

		if ($len > 0)
		{
			if ($len == 5 or $len == 6 or $len == 7)
			{
				if ($len == 5)
				{
					$phone_a = substr($phone, -5, 1);
				}
				if ($len == 6)
				{
					$phone_a = substr($phone, -6, 2);
				}
				if ($len == 7)
				{
					$phone_a = substr($phone, -7, 3);
				}
				$phone_b = substr($phone, -4, 2);
				$phone_c = substr($phone, -2, 2);

				$phone = $phone_a.'-'.$phone_b.'-'.$phone_c;
			}
			if (strlen($result) > 0)
			{
				$result = $result.' ';
			}
			$result = $result.$phone;
		}

		return $result;
	}

	function ClearSQLValue($value, $quote = '')
	{
		if (strlen($value) == 0)
		{
			return 'null';
		}else{
			return $quote.$value.$quote;
		}
	}

	function IsINN($text)
	{
		if (!is_numeric($text))
		{
			return false;
		}
		if ((int)($text) < 0)
		{
			return false;
		}
		$len = strlen($text);
		if ($len != 10 and $len != 12)
		{
			return false;
		}

		$text = (string)($text);
		if ($len == 10)
		{
			return $text[9] === (string)(((2 * $text[0] + 4 * $text[1] + 10 * $text[2] + 3 * $text[3] + 5 * $text[4] + 9 * $text[5] + 4 * $text[6] + 6 * $text[7] + 8 * $text[8]) % 11) % 10);
		}
		if ($len == 12)
		{
			$num10 = (string)(((7 * $text[0] + 2 * $text[1] + 4 * $text[2] + 10 * $text[3] + 3 * $text[4] + 5 * $text[5] + 9 * $text[6] + 4 * $text[7] + 6 * $text[8] + 8 * $text[9]) % 11) % 10);
			$num11 = (string)(((3 * $text[0] + 7 * $text[1] + 2 * $text[2] + 4 * $text[3] + 10 * $text[4] + 3 * $text[5] + 5 * $text[6] + 9 * $text[7] + 4 * $text[8] + 6 * $text[9] + 8 * $text[10]) % 11) % 10);
			return $text[11] === $num11 and $text[10] === $num10;
		}
	}

	function IsSNILS($text)
	{
		if (!is_numeric($text))
		{
			return false;
		}
		if ((int)($text) < 0)
		{
			return false;
		}
		if (strlen($text) != 11)
		{
			return false;
		}
		$text = (string)($text);
		$end_num = (int)(substr($text, -2, 2));
		$sum = 0;
		for ($i = 1; $i < 10; $i++)
		{
			$sum = $sum + $text[($i - 1)] * (10 - $i);
		}		
		if ((($sum % 101) % 100) == $end_num)
		{
			return true;
		}else{
			return false;
		}
	}

	function IsOKPO($text)
	{
		if (!is_numeric($text))
		{
			return false;
		}
		$len = strlen($text);	
		if ((int)($text) < 0)
		{
			return false;
		}
		if ($len != 8 and $len != 10)
		{
			return false;
		}			
		return true;
	}	
	
	function IsTemproraryCertificate($text)
	{
		if (!is_numeric($text))
		{
			return false;
		}
		if ((int)($text) < 0)
		{
			return false;
		}
		if (strlen($text) != 9)
		{
			return false;
		}
		return true;
	}

	function IsKPP($text)
	{
		if (!is_numeric($text))
		{
			return false;
		}
		if ((int)($text) < 0)
		{
			return false;
		}
		if (strlen($text) != 9)
		{
			return false;
		}
		return true;
	}

	function IsOGRN($text)
	{
		$len = strlen($text);
		if ($len != 13 and $len != 15)
		{
			return false;
		}	
		$k = (string)($len - 2);
		$x = $len - 1;
		$value = substr($text, 0, $x);
		$ml = MathLongSUB($value, MathLongMUL(MathLongDIV($value, $k), $k));
		if ($ml[strlen($ml) - 1] == substr($text, $x, 1))
		{
			return true;
		}else{
			return false;
		}
	}	

	function IsEMail($text)
	{
		if ($text == '')
		{
			return false;
		}
		if (!preg_match("/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/", $text))
		{
			return false;
		}else{
			return true;
		}
	}

	function IsSID($text)
	{
		$value = explode('-', $text);
		$size = sizeof($value);
		if ($size < 4)
		{
			return false;
		}
		if ($value[0] != 'S')
		{
			return false;
		}
		for ($i = 1; $i < $size; $i++)
		{
			if (!is_numericaff($value[$i]))
			{
				return false;
			}
		}
		return true;
	}	
	
	function IsSUIID($text)
	{
		if (is_numericaff($text))
		{
			return true;
		}else{
			return false;
		}
	}
	
	function IsAddressZip($text)
	{
		if (strlen($text) == 6 and is_numericaff($text))
		{
			return true;
		}else{
			return false;
		}
	}	
	
	function GetAge($date) 
	{
		$date = strtotime($date);
	
		$y1 = date('Y', $date);
		$m1 = date('m', $date);
		$d1 = date('d', $date);
		
		$y2 = date('Y');
		$m2 = date('m');
		$d2 = date('d');
		
		if($m1 > $m2 or $m1 == $m2 and $d1 > $d2)
		{
			return ($y2 - $y1 - 1);
		}else{
			return ($y2 - $y1);
		}
	}			
	
	function GetCheckLengthString($text, $len, $posfix = '...')
	{
		if (strlen($text) > $len)
		{
			return substr($text, 0, $len).$posfix;
		}else{
			return $text;
		}
	}

	function GetPostCheckStyle($value)
	{
		if ($value != false)
		{
			return '';
		}else{
			return 'background-color: #fffafa; border-width: 2px; padding: 1px; border-color: #df8080;';
		}
	}

	function GetCheckBoxHTMLRef($value, $htmlvalue = 1)
	{
		if ((int)$value == (int)$htmlvalue or $value == 'true')
		{
			return 'checked';
		}else{
			return '';
		}
	}
	
	function GetRadioButtonHTMLRef($value, $htmlvalue)
	{
		if ((string)$value == (string)$htmlvalue)
		{
			return 'checked';
		}else{
			return '';
		}
	}

	function GetComboBoxHTMLRef($value, $htmlvalue)
	{
		if ((string)$value == (string)$htmlvalue)
		{
			return 'selected';
		}else{
			return '';
		}
	}

	function GetCSSClassForList($index)
	{
		return (Odd($index)) ? 'list_a' : 'list_b';	
	}
	
	function ClearSpace($text)
	{
		return str_replace(' ',  '', $text);
	}
	
	function OnlyNumber($text)
	{
		$result = '';
		$text = trim($text);
		for ($i = 0; $i < strlen($text); $i++)
		{
			if (is_numericaff($text[$i]))
			{
				$result = $result.$text[$i];
			}
		}
		return $result;
	}	
	
	function ByteDigitConvert($value, &$index, $digits = 2)
	{
		$result = $value;
	 
		$index = 0;
		while ($result >= 1024)
		{
			Inc($index);
			$result = MathLongDIV($result, 1024, $digits);
		}
		return $result;
	}

	function BytePreviewFormat($value, $digits = 3, $space = ' ')
	{
		$value = (string)$value;
		$result = '';
		$c = 0;
		for ($i = strlen($value) - 1; $i >= 0; $i--)
		{
			if ($c == $digits)
			{
				$result = $space.$result;
				$c = 0;
			}
			$result = $value[$i].$result;
			Inc($c);
			
		}
		return trim($result);
	}
	
	function ByteRead($value, $prefix = true)
	{
		$MLI_NameOfByte = array ('áàéò', 'ÊÁ', 'ÌÁ', 'ÃÁ', 'ÒÁ', 'ÏÁ', 'ÝÁ');
		$byte = ByteDigitConvert($value, $index);
		
		$temp = '';
		if ($prefix)
		{
			$temp = ' ('.BytePreviewFormat($value).' '.$MLI_NameOfByte[0].')';
		}
		
		return FloatReadFormat($byte).' '.$MLI_NameOfByte[$index].$temp;
	}		
	
	function GetErrorEndHtmlText($text, $note)
	{
		if ($note != '')
		{
			$note = '<br><br><FONT class="context">'.$note.'</FONT>';
		}
		return '
				<body>
					<table class="area">
						<tr>
							<td align="center">
								<FONT class="attantion">
									<b>'.$text.'</b>
									<br><br>
									<FONT class="context"><div style="display: inline;" id="update">...</div></FONT>
									'.$note.'
								</FONT>
							</td>
						</tr>
					</table>
					'.UpdatePageJavaScript().'
				</body>
			</html>		
		';
	}	
	
	function PrintDateTimeFormat(&$row, &$print, $datename, $timename)
	{
		if ($row[$datename] != '') 
		{
			$temp_select_value_a = $row[$datename];
			$temp_select_value_b = DateReader($temp_select_value_a);
			$temp_select_value_c = DateReaderShort($temp_select_value_a);
			$temp_select_value_d = $row[$timename];
			$temp_select_value_e = date('H:i', strtotime($temp_select_value_d));				
		}else{
			$temp_select_value_a = '';
			$temp_select_value_b = '';
			$temp_select_value_c = '';
			$temp_select_value_d = '';
			$temp_select_value_e = '';			
		}	
		$print[$datename] = $temp_select_value_a;
		$print[$datename.'ReadLong'] = $temp_select_value_b;
		$print[$datename.'Read'] = $temp_select_value_c;
		$print[$timename] = $temp_select_value_d;
		$print[$timename.'Short'] = $temp_select_value_e;
		$print[$datename.'Time'] = $temp_select_value_a.' '.$temp_select_value_d;
		$print[$datename.'TimeShort'] = $temp_select_value_a.' '.$temp_select_value_e;
		$print[$datename.'ReadTime'] = $temp_select_value_c.' '.$temp_select_value_d;
		$print[$datename.'ReadTimeShort'] = $temp_select_value_c.' '.$temp_select_value_e;		
	}
	
	function PrintDateFormat(&$row, &$print, $name)
	{
		if ($row[$name] != '') 
		{
			$temp_select_value_a = $row[$name];
			$temp_select_value_b = DateReader($temp_select_value_a);
			$temp_select_value_c = DateReaderShort($temp_select_value_a);
		}else{
			$temp_select_value_a = '';
			$temp_select_value_b = '';
			$temp_select_value_c = '';
		}
		$print[$name] = $temp_select_value_a;
		$print[$name.'ReadLong'] = $temp_select_value_b;
		$print[$name.'Read'] = $temp_select_value_c;		
	}	
	
	function PrintNumericFormat(&$row, &$print, $name, $gender)
	{
		if ($row[$name] > 0)
		{
			$temp_select_value_a = $row[$name];
			$temp_select_value_b = ExtractNumberFullReader(NumberFullReader($temp_select_value_a, $gender));
			$temp_select_value_c = $temp_select_value_a.' ('.$temp_select_value_b.')';
		}else{
			$temp_select_value_a = '-';
			$temp_select_value_b = '';
			$temp_select_value_c = '-';		
		}		
		$print[$name] = $temp_select_value_a;
		$print[$name.'Read'] = $temp_select_value_b;
		$print[$name.'ReadFull'] = $temp_select_value_c;	
	}	

	function PrintCaseFormat(&$row, &$print, $name)
	{
		if (is_array($row))
		{
			$temp = new RussianNameProcessor($row[$name]);
		}else{
			$temp = new RussianNameProcessor($name);
		}		
		$print[$name.'Case1'] = $temp->fullName($temp->gcaseIm);
		$print[$name.'Case2'] = $temp->fullName($temp->gcaseRod);
		$print[$name.'Case3'] = $temp->fullName($temp->gcaseDat);
		$print[$name.'Case4'] = $temp->fullName($temp->gcaseVin);
		$print[$name.'Case5'] = $temp->fullName($temp->gcaseTvor);
		$print[$name.'Case6'] = $temp->fullName($temp->gcasePred);	
	}		
	
	function PrintCaseShortFormat(&$row, &$print, $name1, $name2, $name3, $printname)
	{
		if (is_array($row))
		{
			$name1 = $row[$name1];
			$name2 = $row[$name2];
			$name3 = $row[$name3];
		}		
		
		$temp = new RussianNameProcessor(trim(trim($name1).' '.trim($name2).' '.trim($name3)));
		
		$namecase1 = $temp->lastName($temp->gcaseIm);
		$namecase2 = $temp->lastName($temp->gcaseRod);
		$namecase3 = $temp->lastName($temp->gcaseDat);
		$namecase4 = $temp->lastName($temp->gcaseVin);
		$namecase5 = $temp->lastName($temp->gcaseTvor);
		$namecase6 = $temp->lastName($temp->gcasePred);
		
		$print[$printname.'ShortNameCase1'] = GetFIOShort($namecase1, $name2, $name3);
		$print[$printname.'ShortNameCase2'] = GetFIOShort($namecase2, $name2, $name3);
		$print[$printname.'ShortNameCase3'] = GetFIOShort($namecase3, $name2, $name3);
		$print[$printname.'ShortNameCase4'] = GetFIOShort($namecase4, $name2, $name3);
		$print[$printname.'ShortNameCase5'] = GetFIOShort($namecase5, $name2, $name3);
		$print[$printname.'ShortNameCase6'] = GetFIOShort($namecase6, $name2, $name3);
		
		$print[$printname.'ShortNameBackCase1'] = GetFIOShortBack($namecase1, $name2, $name3);
		$print[$printname.'ShortNameBackCase2'] = GetFIOShortBack($namecase2, $name2, $name3);
		$print[$printname.'ShortNameBackCase3'] = GetFIOShortBack($namecase3, $name2, $name3);
		$print[$printname.'ShortNameBackCase4'] = GetFIOShortBack($namecase4, $name2, $name3);
		$print[$printname.'ShortNameBackCase5'] = GetFIOShortBack($namecase5, $name2, $name3);
		$print[$printname.'ShortNameBackCase6'] = GetFIOShortBack($namecase6, $name2, $name3);		
	}	
	
	function PlaceFormat($text)
	{
		$text = str_replace('.', '. ', $text);
		
		while (strpos($text, '  ') != false)
		{
			$text = str_replace('  ', ' ', $text);
		}
		
		return trim($text);
	}		
	
	function MoneyFormat($number)
	{
		return number_format($number, 2, ',', ' ');
	}
	
	function GetAddress($format, $country, $zip, $region, $area, $city, $town, $street, $home, $case, $build, $apatment, $room, $is_legal)
	{
		if ($format == 0 or $format == 3 or $format == 5)
		{
			$country = '';
			$temp = trim(str_replace('ãîðîä', 'ã', str_replace('  ', ' ', str_replace('.', ' ', strtolower($region)))));			
			if (substr($temp, 0, 2) != 'ã ' and substr($temp, -2) != ' ã')
			{
				$region = '';
				$area = '';
			}				
		}
		if ($format == 5)
		{
			$country = '';
			$region = '';
			$area = '';
			$zip = '';
		}
		if ($format == 1)
		{
			$country = '';
			$zip = '';
		}
		if ($format == 2)
		{
			$country = '';
		}
		
		$apatment_prefix = ($is_legal) ? 'îô' : 'êâ';
		$split = ', ';
		
		$result = '';		
		$result = InsertTo($result, $country, '', $split);
		$result = InsertTo($result, $zip, '', $split);
		$result = InsertTo($result, $region, '', $split);
		$result = InsertTo($result, $area, '', $split);
		$result = InsertTo($result, $city, '', $split);
		$result = InsertTo($result, $town, '', $split);
		$result = InsertTo($result, $street, '', $split);
		$result = InsertTo($result, $home, 'ä. ', $split);
		$result = InsertTo($result, $case, 'ê. ', $split);
		$result = InsertTo($result, $build, 'ñòð. ', $split);
		$result = InsertTo($result, $apatment, $apatment_prefix.'. ', $split);		
		if (!$is_legal)
		{
			$result = InsertTo($result, $room, 'êîì. ', $split);
		}	
		return $result;
	}
	
	function PrintAddressFormat(&$row, &$print, $name, $is_legal)
	{
		$country = $row[$name.'Country'];
		$zip = $row[$name.'Zip'];
		$region = $row[$name.'Region'];
		$area = $row[$name.'Area'];
		$city = $row[$name.'City'];
		$town = $row[$name.'Town'];
		$street = $row[$name.'Street'];
		$home = $row[$name.'Home'];
		$case = $row[$name.'Case'];
		$build = $row[$name.'Build'];
		$apatment = $row[$name.'Apatment'];
		$room = $row[$name.'Room'];
		$building_number = $row[$name.'BuildingNumber'].'-å çä.: ';
		
	
		$print[$name] = $building_number.GetAddress(0, $country, $zip, $region, $area, $city, $town, $street, $home, $case, $build, $apatment, $room, $is_legal);
		$print[$name.'Long'] = GetAddress(1, $country, $zip, $region, $area, $city, $town, $street, $home, $case, $build, $apatment, $room, $is_legal);
		$print[$name.'LongZip'] = GetAddress(2, $country, $zip, $region, $area, $city, $town, $street, $home, $case, $build, $apatment, $room, $is_legal);
		$print[$name.'ShortZip'] = GetAddress(3, $country, $zip, $region, $area, $city, $town, $street, $home, $case, $build, $apatment, $room, $is_legal);
		$print[$name.'Full'] = GetAddress(4, $country, $zip, $region, $area, $city, $town, $street, $home, $case, $build, $apatment, $room, $is_legal);
		$print[$name.'Short'] = GetAddress(5, $country, $zip, $region, $area, $city, $town, $street, $home, $case, $build, $apatment, $room, $is_legal);
	}		
	
	
	function SearchFullTextParser($text, $escape = true)
	{
		$text = trim($text);
		
		$text = str_replace('+', '', $text);
		$text = str_replace('*', '', $text);	
		$text = str_replace('*', '', $text);
		
		$quot = false;		
		for ($i = 1; $i < strlen($text); $i++)
		{			
			if ($quot)
			{
				if ($text[$i] == '"')
				{
					$quot = false;
				}
			}else{
				if ($text[$i] == '"')
				{
					$quot = true;
				}			
			}
			if ($quot and $text[$i] == ' ')
			{
				$text[$i] = chr(0);	
			}			
		}
		
		if ($quot)
		{
			$text = $text.'"';
		}
		
		if ($escape)
		{
			$text = str_replace('"', '\"', $text);
		}

		$value = explode(' ', $text);
		
		$result = '';
		
		$c = 0;
		
		for ($i = 0; $i < sizeof($value); $i++)
		{
			$value[$i] = trim($value[$i]);
			if ($value[$i] != '')
			{
				if ($c != 0)
				{
					$result = $result.' ';
				}
				
				$result = $result.'+'.$value[$i].'*';
				
				Inc($c);
			}
		}
		
		$result = str_replace(Chr(0), ' ', $result);	
		
		return $result;
	}
?>