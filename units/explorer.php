<?
	function EndSlash($path)
	{
		for ($i = strlen($path) - 1; $i > -1; $i--)
		{
			if ($path[$i] != '\\')
			{
				break;
			}
		}
		return substr($path, 0, $i + 1);
	}

	function GetLocalPath($path)
	{
		return str_replace('/', '\\', $path);
	}

	function IsSetExpansion($expansion, $value)
	{
		$result = false;

		$value = strtolower($value);

		for ($i = 0; $i < sizeof($expansion); $i++)
		{
			if ($value  == $expansion[$i])
			{
				$result = true;
				break;
			}
		}
		return $result;
	}

	function directory_exists($path)
	{
		if (file_exists($path) and is_dir($path))
		{
			return true;
		}else{
			return false;
		}
	}	
	
	function CreateNewDir($path, $protection = false)
	{
		$root = '';
		$dirs = explode('\\', $path);
		$flag = false;
		for ($i = 0; $i < sizeOf($dirs); $i++)
		{
			if (strlen($dirs[$i]) != 0)
			{
				if (strlen($root) != 0)
				{
					$root = $root.'\\'.$dirs[$i];
				}else{
					$root = $dirs[$i];
					if ($protection and substr($root, 1, 1) != ':')
					{
						return false;
					}
				}
				if (!directory_exists($root))
				{
					if (!mkdir($root))
					{
						return false;
					}
				}
				$flag = true;
			}
		}
		return $flag;
	}	

	function IsFileSystemName($filename)
	{
		if (strlen(strpos($filename, '/')) != 0 or
			strlen(strpos($filename, '\\')) != 0 or
			strlen(strpos($filename, '|')) != 0 or
			strlen(strpos($filename, ':')) != 0 or
			strlen(strpos($filename, '*')) != 0 or
			strlen(strpos($filename, '?')) != 0 or
			strlen(strpos($filename, '"')) != 0 or
			strlen(strpos($filename, '<')) != 0 or
			strlen(strpos($filename, '>')) != 0)
		{
			return false;
		}else{
			return true;
		}
	}

	function GetCorrectFileSystemName($filename, $replace = '_')
	{
		$result = $filename;
		$result = str_replace('/', $replace, $result);
		$result = str_replace('\\', $replace, $result);
		$result = str_replace('|', $replace, $result);
		$result = str_replace(':', $replace, $result);
		$result = str_replace('*', $replace, $result);
		$result = str_replace('?', $replace, $result);
		$result = str_replace('"', $replace, $result);
		$result = str_replace('&quot;', $replace, $result);
		$result = str_replace('<', $replace, $result);
		$result = str_replace('>', $replace, $result);
		return $result;
	}

	function RemoveDirectoryFull($path, $self = true)
	{
		if (!directory_exists($path))
		{
			return false;
		}
		$dhwnd = opendir($path);
		while (($file = readdir($dhwnd)))
		{
			$file_path = $path.'\\'.$file;
			if (file_exists($file_path) and !is_dir($file_path))
			{
				if (unlink($file_path) != true)
				{
					return false;
				}
			}else{
				if ($file != '.' and $file != '..')
				{
					if (RemoveDirectoryFull($file_path) != true)
					{
						return false;
					}
				}
			}
		}
		closedir($dhwnd);  
		if ($self)
		{
			if (rmdir($path) != true)
			{
				return false;
			}else{
				return true;
			}
		}else{
			return true;
		}
	}

	function ClearDirectoryFull($path, $sysfile = false)
	{
		if (!directory_exists($path))
		{
			return false;
		}
		$dhwnd = opendir($path);
		while (($file = readdir($dhwnd)))
		{
			$file_path = $path.'\\'.$file;
			if (file_exists($file_path) and !is_dir($file_path))
			{
				$unlink = true;
				if ($sysfile)
				{
					if (strtolower($file) == '.htaccess')
					{
						$unlink = false;
					}
				}
				if ($unlink)
				{
					if (unlink($file_path) != true)
					{
						return false;
					}
				}
			}else{
				if ($file != '.' and $file != '..')
				{
					if (ClearDirectoryFull($file_path, $sysfile) != true)
					{
						return false;
					}
				}
			}
		}
		closedir($dhwnd);  
		return true;
	}

	function CopyDirectoryFull($source, $dest, $replace = true)
	{
		if (!directory_exists($source))
		{
			return false;
		}

		if (!directory_exists($dest))
		{
			if (!mkdir($dest))
			{
				return false;
			}
		}

		$dhwnd = opendir($source);
		while (($file = readdir($dhwnd)))
		{
			$file_path_source = $source.'\\'.$file;
			$file_path_dest = $dest.'\\'.$file;

			if (file_exists($file_path_source) and !is_dir($file_path_source))
			{
				if ($replace)
				{
					if (file_exists($file_path_dest))
					{
						if (unlink($file_path_dest) != true)
						{
							return false;
						}
					}
				}
				if (copy($file_path_source, $file_path_dest) != true)
				{
					return false;
				}
			}else{
				if ($file != '.' and $file != '..')
				{
					if (CopyDirectoryFull($file_path_source, $file_path_dest, $replace) != true)
					{
						return false;
					}
				}
			}
		}
		closedir($dhwnd);  

		return true;
	}

	function DeleteFile($path)
	{
		$result = true;
		if (file_exists($path))
		{
			$result = unlink($path);
		}
		return $result;
	}

	function GetShieldJavaName($value)
	{
		return str_replace("'", '_', GetCorrectFileSystemName($value));
	}
	
	function ExtractFileName($path)
	{
		return basename($path);
	}	
	
	function GetFileExpansion($path, $point = false)
	{
		$result = '';
		$c = 0;
		for ($i = strlen($path) - 1; $i >= 0; $i--)
		{
			if ($path[$i] == '.')
			{
				if (!$point)
				{
					$c = $c + 1;
				}
				$result = substr($path, -$c);
				break;
			}
			$c = $c + 1;
		}
		return $result;
	}	
	
	function ExtractFileShortName($path, $point = false)
	{
		$result = '';
		for ($i = strlen($path) - 1; $i >= 0; $i--)
		{
			if ($path[$i] == '.')
			{
				if ($point)
				{
					$i = $i + 1;
				}			
				$result = substr($path, 0, $i);
				break;
			}
		}
		return $result;
	}	
?>