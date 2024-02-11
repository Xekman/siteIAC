<?	
	$XML_NAMESPACE_URL_INDEX = 0;
	$XML_NAMESPACE_INDEX = 1;	
	
	function WIN1251ToUTF8($value)
	{
		return iconv('Windows-1251', 'UTF-8', $value);
	}	

	function UTF8ToWIN1251($value)
	{
		return iconv('UTF-8', 'Windows-1251', $value);
	}	
	
	function CreateXMLNode(&$xml, $name, $value = null, $url_namespace = null, $namespace = null)
	{	
		$result = false;
		
		if (!isset($xml) or $name == '')
		{
			return $result;
		}		
		
		if ($value == '')
		{
			$value = null;
		}		
		
		if (isset($url_namespace))
		{
			if (isset($value))
			{
				if ($namespace == '' or !isset($namespace))
				{
					$result = $xml->createElementNS($url_namespace, $name, $value);	
				}else{
					$result = $xml->createElementNS($url_namespace, $namespace.':'.$name, $value);	
				}
			}else{
				if ($namespace == '' or !isset($namespace))
				{
					$result = $xml->createElementNS($url_namespace, $name);	
				}else{
					$result = $xml->createElementNS($url_namespace, $namespace.':'.$name);	
				}			
			}
		}else{
			if (isset($value))
			{
				$result = $xml->createElement($name, $value);			
			}else{
				$result = $xml->createElement($name);		
			}		
		}
		
		return $result;		
	}		
	
	function CopyXMLNode($src, $xml, $dest, $full = true)
	{
		$result = false;
		
		if (!isset($src) or !isset($xml) or !isset($dest))
		{
			return $result;
		}		
		
		foreach ($dest->childNodes as $src_copy)
		{
			$src_copy = $xml->importNode($src_copy, $full);
			$src->appendChild($src_copy);
		}	
	}
	
	function AddXMLChild(&$src, &$xml, $name, $value = null, $url_namespace = null, $namespace = null)
	{
		global $XML_NAMESPACE_URL_INDEX;
		global $XML_NAMESPACE_INDEX;
		
		$result = false;
		
		if (!isset($src) or !isset($xml))
		{
			return $result;
		}			
		
		$name = WIN1251ToUTF8($name);
		$value = WIN1251ToUTF8($value);
		
		if (isset($url_namespace))
		{
			if (is_array($url_namespace))
			{
				$namespace = $url_namespace[$XML_NAMESPACE_INDEX];
				$url_namespace = $url_namespace[$XML_NAMESPACE_URL_INDEX];
			}
			
			$url_namespace = WIN1251ToUTF8($url_namespace);
			$namespace = WIN1251ToUTF8($namespace);

			return $src->appendChild(CreateXMLNode($xml, $name, $value, $url_namespace, $namespace));			
		}else{
			return $src->appendChild(CreateXMLNode($xml, $name, $value));
		}
	}		

	function DelXMLChild(&$src)
	{
		$result = false;
		
		if (!isset($src))
		{
			return $result;
		}			
		
		if (!isset($src->parentNode))
		{
			return $result;
		}
		
		$src->parentNode->removeChild($src);			
		
		$result = true;
		
		return $result;
	}	

	function GetXMLNode(&$src, $name, $url_namespace = null)
	{
		global $XML_NAMESPACE_URL_INDEX;
		
		$result = false;
		
		if (!isset($src))
		{
			return $result;
		}		
		
		$name = WIN1251ToUTF8($name);
		
		if (isset($url_namespace))
		{
			if (is_array($url_namespace))
			{
				$url_namespace = $url_namespace[$XML_NAMESPACE_URL_INDEX];
			}
			
			$url_namespace = WIN1251ToUTF8($url_namespace);
			
			foreach ($src->getElementsByTagNameNS($url_namespace, $name) as $element)
			{
				$result = $element;
				break;
			}		
		}else{
			foreach ($src->getElementsByTagName($name) as $element)
			{
				$result = $element;
				break;
			}			
		}
		return $result;
	}
	
	function GetXMLValue(&$src, $name, $url_namespace = null)
	{	
		$result = false;
		
		if (!isset($src))
		{
			return $result;
		}		
		
		$node = GetXMLNode($src, $name, $url_namespace);
		if ($node == false)
		{
			return $result;
		}
		
		$result = $node->nodeValue;
		
		$result = UTF8ToWIN1251($result);		
		
		return $result;
	}
	
	function SetXMLValue(&$src, $name, $value, $url_namespace = null)
	{		
		$result = false;
		
		if (!isset($src))
		{
			return $result;
		}
		
		$node = GetXMLNode($src, $name, $url_namespace);
		if ($node == false)
		{
			return $result;
		}
		
		$value = WIN1251ToUTF8($value);
		
		$node->nodeValue = $value;
		
		$result = true;
		
		return $result;
	}	
	
	function GetXMLAttribute(&$node, $name, $url_namespace = null)
	{	
		global $XML_NAMESPACE_URL_INDEX;
		
		$result = false;
		
		if (!isset($node))
		{
			return $result;
		}
		
		$name = WIN1251ToUTF8($name);
		
		if (isset($url_namespace))
		{
			if (is_array($url_namespace))
			{
				$url_namespace = $url_namespace[$XML_NAMESPACE_URL_INDEX];
			}				
			
			$url_namespace = WIN1251ToUTF8($url_namespace);
			
			if ($node->hasAttributeNS($url_namespace, $name))
			{
				$result = $node->getAttributeNS($url_namespace, $name);
			}
		}else{
			if ($node->hasAttribute($name))
			{
				$result = $node->getAttribute($name);
			}		
		}		
		
		if ($result == false)
		{
			return $result;
		}
		
		$result = UTF8ToWIN1251($result);
		
		return $result;
	}
	
	function SetXMLAttribute(&$node, $name, $value, $url_namespace = null, $namespace = null)
	{
		global $XML_NAMESPACE_URL_INDEX;
		global $XML_NAMESPACE_INDEX;

		$result = false;
		
		if (!isset($node))
		{
			return $result;
		}
		
		$name = WIN1251ToUTF8($name);
		$value = WIN1251ToUTF8($value);			
		
		if (isset($url_namespace))
		{
			if (is_array($url_namespace))
			{
				$namespace = $url_namespace[$XML_NAMESPACE_INDEX];
				$url_namespace = $url_namespace[$XML_NAMESPACE_URL_INDEX];
			}		
		
			$url_namespace = WIN1251ToUTF8($url_namespace);
			$namespace = WIN1251ToUTF8($namespace);	

			$result = $node->setAttributeNS($url_namespace, $namespace.':'.$name, $value);
		}else{
			$result = $node->setAttribute($name, $value);
		}
		
		if ($result == false)
		{
			return $result;
		}
		
		$result = true;
		
		return $result;
	}
	
	function DelXMLAttribute(&$node, $name, $url_namespace = null)
	{
		global $XML_NAMESPACE_URL_INDEX;
		
		$result = false;
		
		if (!isset($node))
		{
			return $result;
		}		
		
		$name = WIN1251ToUTF8($name);
		
		if (isset($url_namespace))
		{
			if (is_array($url_namespace))
			{
				$url_namespace = $url_namespace[$XML_NAMESPACE_URL_INDEX];
			}		
			
			$url_namespace = WIN1251ToUTF8($url_namespace);
			
			if ($node->hasAttributeNS($url_namespace_attr, $attribute))
			{
				$result = $node->removeAttributeNS($url_namespace, $name);
			}
		}else{
			if ($node->hasAttribute($attribute))
			{
				$result = $node->removeAttribute($name);
			}				
		}

		if ($result == false)
		{
			return $result;
		}
		
		$result = true;
		
		return $result;
	}
?>