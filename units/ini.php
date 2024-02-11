<?
	class TIniFile
	{
		public $filename;
		public $arr;

		function __construct($file = false, $timeout = 10000)
		{
			if ($file)
			{
				$this->loadFromFile($file, $timeout);
			}
		}

		function initArray()
		{
			$this->arr = parse_ini_file($this->filename, true);
		}

		function loadFromFile($file, $timeout = 10000)
		{
			$timeout = $timeout / 1000;
			$result = false;
			$this->filename = $file;
			
			if (file_exists($file))
			{
				$temp = is_readable($file);
				if (!$temp)
				{
					$ldt = time() + $timeout;
					while (time() < $ldt and $temp == false)
					{
						$temp = is_readable($file);
						sleep(1);
					}
				}
				if ($temp)
				{
					$this->initArray();
					$result = true;
				}
			}
			return $result;
		}

		function read($section, $key, $def = '')
		{
			if (isset($this->arr[$section][$key]))
			{
				return $this->arr[$section][$key];
			}else{
				return $def;
			}
		}

		function write($section, $key, $value)
		{
			if (is_bool($value))
			{
				$value = $value ? 1 : 0;
			}
			$this->arr[$section][$key] = $value;
		}

		function eraseSection($section)
		{
			if (isset($this->arr[$section]))
			{
				unset($this->arr[$section]);
			}
		}

		function deleteKey($section, $key)
		{
			if (isset($this->arr[$section][$key]))
			{
				unset($this->arr[$section][$key]);
			}
		}

		function readSections(&$array)
		{
			$array = array_keys($this->arr);
			return $array;
		}

		function readKeys($section, &$array)
		{
			if (isset($this->arr[$section]))
			{
				$array = array_keys($this->arr[$section]);
				return $array;
			}
			return array();
		}

		function updateFile($timeout = 10000)
		{
			$timeout = $timeout / 1000;
			$newline = chr(13).chr(10);
			$result = '';
			foreach ($this->arr as $sname=>$section)
			{
				$result = $result.'[' . $sname . ']'.$newline;
				foreach ($section as $key=>$value)
				{
					$result = $result.$key .'="'.$value.'"'.$newline;
				}
				$result = $result.$newline;
			}
			if (file_exists($this->filename))
			{
				$temp = is_writeable($this->filename);
				if (!$temp)
				{
					$ldt = time() + $timeout;
					while (time() < $ldt and $temp == false)
					{
						$temp = is_writeable($this->filename);
						sleep(1);
					}
				}
			}else{
				$temp = true;
			}
			if ($temp)
			{
				$fhwnd = fopen($this->filename, "w");
				$temp = fwrite($fhwnd, $result);
				fclose($fhwnd);
			}
			return $temp;
		}

		function __destruct()
		{
			
		}
	}
?>