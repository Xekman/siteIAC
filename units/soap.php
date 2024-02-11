<?
	$SOAP_XML_NAMESPACE_SOAP[$XML_NAMESPACE_URL_INDEX] = 'http://schemas.xmlsoap.org/soap/envelope/';
	$SOAP_XML_NAMESPACE_SOAP[$XML_NAMESPACE_INDEX] = 'soapenv';
	
	$SOAP_XML_CODEPAGE = 'UTF-8';
	$SOAP_XML_VERSION = '1.0';
	
	$SOAP_XML_WSDL = 'http://schemas.xmlsoap.org/wsdl/';
	
	function SOAPGetServiceAddressLocation($wsdl)
	{
		global $SOAP_XML_WSDL;
		
		$result = false;
		
		$xml = new DOMDocument();
		if (!$xml->load($wsdl))
		{
			return $result;
		}			
		
		foreach($xml->getElementsByTagNameNS($SOAP_XML_WSDL, 'definitions') as $xml_definitions)
		{
			foreach($xml_definitions->getElementsByTagNameNS($SOAP_XML_WSDL, 'service') as $xml_service)
			{
				foreach($xml_service->getElementsByTagNameNS($SOAP_XML_WSDL, 'port') as $xml_port)
				{
					foreach($xml_port->getElementsByTagNameNS('http://schemas.xmlsoap.org/wsdl/soap/', 'address') as $xml_address)
					{				
						$result = GetXMLAttribute($xml_address, 'location');		
					}
				}			
			}	
		}	
		unset($xml);
		return $result;
	}
	
	function SOAPGetServiceTargetNameSpace($wsdl)
	{
		global $SOAP_XML_WSDL;
		
		$result = false;
		
		$xml = new DOMDocument();
		if (!$xml->load($wsdl))
		{
			return $result;
		}			
		
		foreach($xml->getElementsByTagNameNS($SOAP_XML_WSDL, 'definitions') as $xml_definitions)
		{
			$result = $xml_definitions->getAttribute('targetNamespace');
		}	
		unset($xml);
		return $result;
	}	
	
	function SOAPGetServiceAddressLocationAndTargetNameSpace($wsdl, &$location, &$targetnamespace)
	{
		global $SOAP_XML_WSDL;
		
		$result = false;
		
		$xml = new DOMDocument();
		if (!$xml->load($wsdl))
		{
			return $result;
		}			
		
		foreach($xml->getElementsByTagNameNS($SOAP_XML_WSDL, 'definitions') as $xml_definitions)
		{
			$targetnamespace = $xml_definitions->getAttribute('targetNamespace');
			foreach($xml_definitions->getElementsByTagNameNS($SOAP_XML_WSDL, 'service') as $xml_service)
			{
				foreach($xml_service->getElementsByTagNameNS($SOAP_XML_WSDL, 'port') as $xml_port)
				{
					foreach($xml_port->getElementsByTagNameNS('http://schemas.xmlsoap.org/wsdl/soap/', 'address') as $xml_address)
					{				
						$location = GetXMLAttribute($xml_address, 'location');		
					}
				}			
			}	
		}	
		
		unset($xml);
		
		return true;
	}	
	
	function SOAPGetFault(&$xml, $url_namespace = null)
	{
		$result = false;
		
		if ($xml == false)
		{
			return $result;
		}		
		
		return GetXMLNode($xml, 'Fault', $url_namespace);
	}
	
	function SOAPIsFault(&$xml, $url_namespace = null)
	{
		if (SOAPGetFault($xml, $url_namespace) == false)
		{
			return false;
		}else{
			return true;
		}
	}
	
	function SOAPGetFaultString(&$xml, $url_namespace = null)
	{
		$result = '';
		
		$node = SOAPGetFault($xml, $url_namespace);
		
		if ($node == false)
		{
			return $result;
		}
		
		$result = GetXMLValue($node, 'faultstring');
		
		if ($result == '')
		{
			$result = GetXMLValue($node, 'detail');
		}
		
		return $result;
	}	
	
	function SOAPGetFaultCode(&$xml, $url_namespace = null)
	{
		$result = '';
		
		$node = SOAPGetFault($xml, $url_namespace);
		
		if ($node == false)
		{
			return $result;
		}
		
		$result = GetXMLValue($node, 'faultcode');
		
		return $result;
	}		
?>