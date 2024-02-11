<?	
	$SVEM_STATUS_UKNOWN = -1;
	$SVEM_STATUS_ACCEPT = 0;
	$SVEM_STATUS_CANCEL = 1;
	$SVEM_STATUS_FAILURE = 2;
	$SVEM_STATUS_INVALID = 3;
	$SVEM_STATUS_NOTIFY = 4;
	$SVEM_STATUS_PACKET = 5;
	$SVEM_STATUS_PING = 6;
	$SVEM_STATUS_PROCESS = 7;
	$SVEM_STATUS_REJECT = 8;
	$SVEM_STATUS_REQUEST = 9;
	$SVEM_STATUS_RESULT = 10;
	$SVEM_STATUS_STATE = 11;

	$SMEV_EXCHANGETYPE_UKNOWN = 0;
	$SMEV_EXCHANGETYPE_PGU_OIV = 1;
	$SMEV_EXCHANGETYPE_OIV1_OIV2 = 2;
	$SMEV_EXCHANGETYPE_OIV_OIV = 3;
	$SMEV_EXCHANGETYPE_IPSH_PN = 4;
	$SMEV_EXCHANGETYPE_IPSH_FK = 5;
	$SMEV_EXCHANGETYPE_OIV_FK = 6;
	
	$SMEV_STATUS[$SVEM_STATUS_ACCEPT] = 'ACCEPT';
	$SMEV_STATUS[$SVEM_STATUS_CANCEL] = 'CANCEL';
	$SMEV_STATUS[$SVEM_STATUS_FAILURE] = 'FAILURE';
	$SMEV_STATUS[$SVEM_STATUS_INVALID] = 'INVALID';
	$SMEV_STATUS[$SVEM_STATUS_NOTIFY] = 'NOTIFY';
	$SMEV_STATUS[$SVEM_STATUS_PACKET] = 'PACKET';
	$SMEV_STATUS[$SVEM_STATUS_PING] = 'PING';
	$SMEV_STATUS[$SVEM_STATUS_PROCESS] = 'PROCESS';
	$SMEV_STATUS[$SVEM_STATUS_REJECT] = 'REJECT';
	$SMEV_STATUS[$SVEM_STATUS_REQUEST] = 'REQUEST';
	$SMEV_STATUS[$SVEM_STATUS_RESULT] = 'RESULT';
	$SMEV_STATUS[$SVEM_STATUS_STATE] = 'STATE';

	$SVEM_TYPECODE_UKNOWN = -1;
	$SMEV_TYPECODE_GOVERNMENTSERVICE = 0;
	$SMEV_TYPECODE_GOVERNMENTFUNCTION = 1;
	$SMEV_TYPECODE_OTHRER = 2;
	
	$SMEV_TYPECODE[$SMEV_TYPECODE_GOVERNMENTSERVICE] = 'GSRV';
	$SMEV_TYPECODE[$SMEV_TYPECODE_GOVERNMENTFUNCTION] = 'GFNC';
	$SMEV_TYPECODE[$SMEV_TYPECODE_OTHRER] = 'OTHR';	
			
	$SMEV_XML_NAMESPACE_SMEV24_URL = 'http://smev.gosuslugi.ru/rev111111';
	$SMEV_XML_NAMESPACE_SMEV25_URL = 'http://smev.gosuslugi.ru/rev120315';			
			
	$SMEV_XML_NAMESPACE_SMEV[$XML_NAMESPACE_URL_INDEX] = $SMEV_XML_NAMESPACE_SMEV25_URL;
	$SMEV_XML_NAMESPACE_SMEV[$XML_NAMESPACE_INDEX] = 'smev';	
	$SMEV_XML_NAMESPACE_WSU[$XML_NAMESPACE_URL_INDEX] = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';
	$SMEV_XML_NAMESPACE_WSU[$XML_NAMESPACE_INDEX] = 'wsu';
	$SMEV_XML_NAMESPACE_WSSE[$XML_NAMESPACE_URL_INDEX] = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
	$SMEV_XML_NAMESPACE_WSSE[$XML_NAMESPACE_INDEX] = 'wsse';	
	
	$SEMV_DATE_FORMAT = 'Y-m-d\TH:i:s.000P';
	
	function SMEVInit24()
	{
		global $XML_NAMESPACE_URL_INDEX;
		
		global $SMEV_XML_NAMESPACE_SMEV;
		
		global $SMEV_XML_NAMESPACE_SMEV24_URL;
		
		$SMEV_XML_NAMESPACE_SMEV[$XML_NAMESPACE_URL_INDEX] = $SMEV_XML_NAMESPACE_SMEV24_URL;
	}

	function SMEVInit25()
	{
		global $XML_NAMESPACE_URL_INDEX;
		
		global $SMEV_XML_NAMESPACE_SMEV;
		
		global $SMEV_XML_NAMESPACE_SMEV25_URL;
		
		$SMEV_XML_NAMESPACE_SMEV[$XML_NAMESPACE_URL_INDEX] = $SMEV_XML_NAMESPACE_SMEV25_URL;
	}
	
	function SMEVSetDateFormat($date)
	{
		global $SEMV_DATE_FORMAT;

		return date($SEMV_DATE_FORMAT, strtotime($date));
	}
	
	function SMEVGetDateFormat($date)
	{
		global $SEMV_DATE_FORMAT;
		
		$value = explode('.', str_replace('T', ' ', $date));
		
		return date('Y-m-d H:i:s', strtotime(trim($value[0])));
	}	
	
	function SMEVCreateXML(&$xml, $signbodyname, $call, $call_url_namespace, $call_namespace)
	{
		global $XML_NAMESPACE_INDEX;
		global $XML_NAMESPACE_URL_INDEX;
		
		global $SOAP_XML_NAMESPACE_SOAP;
		global $SMEV_XML_NAMESPACE_WSU;
		global $SMEV_XML_NAMESPACE_WSSE;
		global $SMEV_XML_NAMESPACE_SMEV;
		
		global $SMEV_XML_NAMESPACE_SMEV24_URL;
		
		$result = false;
		
		$xml = new DOMDocument('1.0', 'UTF-8');
		
		if (is_array($call_url_namespace))
		{
			$call_namespace = $call_url_namespace[$XML_NAMESPACE_INDEX];
			$call_url_namespace = $call_url_namespace[$XML_NAMESPACE_URL_INDEX];
		}
		
		if (!isset($xml))
		{
			return $result;
		}
		$xml_envelope = AddXMLChild($xml, $xml, 'Envelope', '', $SOAP_XML_NAMESPACE_SOAP);
		
		$xml_envelope_header = AddXMLChild($xml_envelope, $xml, 'Header', '', $SOAP_XML_NAMESPACE_SOAP);
		$xml_envelope_header_security = AddXMLChild($xml_envelope_header, $xml, 'Security', '', $SMEV_XML_NAMESPACE_WSSE);
		SetXMLAttribute($xml_envelope_header_security, 'actor', 'http://smev.gosuslugi.ru/actors/smev', $SOAP_XML_NAMESPACE_SOAP);

		$xml_envelope_body = AddXMLChild($xml_envelope, $xml, 'Body', '', $SOAP_XML_NAMESPACE_SOAP);
		SetXMLAttribute($xml_envelope_body, 'Id', $signbodyname, $SMEV_XML_NAMESPACE_WSU);
		$xml_envelope_body_call = AddXMLChild($xml_envelope_body, $xml, $call, '', $call_url_namespace, $call_namespace);
		
		$xml_envelope_body_call_message = AddXMLChild($xml_envelope_body_call, $xml, 'Message', '', $SMEV_XML_NAMESPACE_SMEV);
		
		$xml_envelope_body_call_message_sender = AddXMLChild($xml_envelope_body_call_message, $xml, 'Sender', '', $SMEV_XML_NAMESPACE_SMEV);
		AddXMLChild($xml_envelope_body_call_message_sender, $xml, 'Code', '', $SMEV_XML_NAMESPACE_SMEV);
		AddXMLChild($xml_envelope_body_call_message_sender, $xml, 'Name', '', $SMEV_XML_NAMESPACE_SMEV);
		
		$xml_envelope_body_call_message_recipient = AddXMLChild($xml_envelope_body_call_message, $xml, 'Recipient', '', $SMEV_XML_NAMESPACE_SMEV);
		AddXMLChild($xml_envelope_body_call_message_recipient, $xml, 'Code', '', $SMEV_XML_NAMESPACE_SMEV);
		AddXMLChild($xml_envelope_body_call_message_recipient, $xml, 'Name', '', $SMEV_XML_NAMESPACE_SMEV);

			$xml_envelope_body_call_message_originator = AddXMLChild($xml_envelope_body_call_message, $xml, 'Originator', '', $SMEV_XML_NAMESPACE_SMEV);
			AddXMLChild($xml_envelope_body_call_message_originator, $xml, 'Code', '', $SMEV_XML_NAMESPACE_SMEV);
			AddXMLChild($xml_envelope_body_call_message_originator, $xml, 'Name', '', $SMEV_XML_NAMESPACE_SMEV);
		if ($SMEV_XML_NAMESPACE_SMEV[$XML_NAMESPACE_URL_INDEX] != $SMEV_XML_NAMESPACE_SMEV24_URL)
		{
			AddXMLChild($xml_envelope_body_call_message, $xml, 'ServiceName', '', $SMEV_XML_NAMESPACE_SMEV);
		}

		AddXMLChild($xml_envelope_body_call_message, $xml, 'TypeCode', '', $SMEV_XML_NAMESPACE_SMEV);
		AddXMLChild($xml_envelope_body_call_message, $xml, 'Status', '', $SMEV_XML_NAMESPACE_SMEV);
		AddXMLChild($xml_envelope_body_call_message, $xml, 'Date', '', $SMEV_XML_NAMESPACE_SMEV);
			AddXMLChild($xml_envelope_body_call_message, $xml, 'RequestIdRef', '', $SMEV_XML_NAMESPACE_SMEV);
			AddXMLChild($xml_envelope_body_call_message, $xml, 'OriginRequestIdRef', '', $SMEV_XML_NAMESPACE_SMEV);
			AddXMLChild($xml_envelope_body_call_message, $xml, 'ServiceCode', '', $SMEV_XML_NAMESPACE_SMEV);
			AddXMLChild($xml_envelope_body_call_message, $xml, 'CaseNumber', '', $SMEV_XML_NAMESPACE_SMEV);
		AddXMLChild($xml_envelope_body_call_message, $xml, 'ExchangeType', '', $SMEV_XML_NAMESPACE_SMEV);
			AddXMLChild($xml_envelope_body_call_message, $xml, 'TestMsg', '', $SMEV_XML_NAMESPACE_SMEV);
		$xml_envelope_body_call_messagedata = AddXMLChild($xml_envelope_body_call, $xml, 'MessageData', '', $SMEV_XML_NAMESPACE_SMEV);
		
		AddXMLChild($xml_envelope_body_call_messagedata, $xml, 'AppData', '', $SMEV_XML_NAMESPACE_SMEV);
		AddXMLChild($xml_envelope_body_call_messagedata, $xml, 'AppDocument', '', $SMEV_XML_NAMESPACE_SMEV);
		
		return $xml_envelope_body_call;
	}
	
	function SMEVGetAppData(&$xml)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
		
		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		return GetXMLNode(&$xml, 'AppData', $SMEV_XML_NAMESPACE_SMEV);
	}		
	
	function SMEVGetAppDocument(&$xml)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
		
		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		return GetXMLNode(&$xml, 'AppDocument', $SMEV_XML_NAMESPACE_SMEV);		
	}		
	
	function SMEVGetSender(&$xml, $code, $name)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
		
		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}	
		
		$node = GetXMLNode(&$xml, 'Sender', $SMEV_XML_NAMESPACE_SMEV);		
		
		if ($node == false)
		{
			return $result;
		}
		
		$result[0] = GetXMLValue($node, 'Code', $SMEV_XML_NAMESPACE_SMEV);
		$result[1] = GetXMLValue($node, 'Name', $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;
	}	
	
	function SMEVSetSender(&$xml, $code, $name)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
		
		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}	
		
		$node = GetXMLNode(&$xml, 'Sender', $SMEV_XML_NAMESPACE_SMEV);		
		
		if ($node == false)
		{
			return $result;
		}		
		
		$result = SetXMLValue($node, 'Code', $code, $SMEV_XML_NAMESPACE_SMEV);
		if (!$result)
		{
			return $result;
		}
		$result = SetXMLValue($node, 'Name', $name, $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;
	}		
	
	function SMEVGetRecipient(&$xml, $code, $name)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
		
		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}	
		
		$node = GetXMLNode(&$xml, 'Recipient', $SMEV_XML_NAMESPACE_SMEV);		
		
		if ($node == false)
		{
			return $result;
		}		
		
		$result[0] = GetXMLValue($node, 'Code', $SMEV_XML_NAMESPACE_SMEV);
		$result[1] = GetXMLValue($node, 'Name', $SMEV_XML_NAMESPACE_SMEV);

		return $result;
	}	
	
	function SMEVSetRecipient(&$xml, $code, $name)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
		
		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}	

		$node = GetXMLNode(&$xml, 'Recipient', $SMEV_XML_NAMESPACE_SMEV);		
		
		if ($node == false)
		{
			return $result;
		}
		
		$result = SetXMLValue($node, 'Code', $code, $SMEV_XML_NAMESPACE_SMEV);
		if (!$result)
		{
			return $result;
		}
		$result = SetXMLValue($node, 'Name', $name, $SMEV_XML_NAMESPACE_SMEV);

		return $result;
	}		
	
	function SMEVGetOriginator(&$xml, $code, $name)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
		
		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}	

		$node = GetXMLNode(&$xml, 'Originator', $SMEV_XML_NAMESPACE_SMEV);		
		
		if ($node == false)
		{
			return $result;
		}	
		
		$result[0] = GetXMLValue($node, 'Code', $SMEV_XML_NAMESPACE_SMEV);
		$result[1] = GetXMLValue($node, 'Name', $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;
	}		
	
	function SMEVSetOriginator(&$xml, $code, $name)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
		
		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}	
		
		$node = GetXMLNode(&$xml, 'Originator', $SMEV_XML_NAMESPACE_SMEV);		
		
		if ($node == false)
		{
			return $result;
		}	
	
		$result = SetXMLValue($node, 'Code', $code, $SMEV_XML_NAMESPACE_SMEV);
		if (!$result)
		{
			return $result;
		}
		$result = SetXMLValue($node, 'Name', $name, $SMEV_XML_NAMESPACE_SMEV);

		return $result;
	}		
	
	function SMEVGetServiceName(&$xml)
	{
		global $SMEV_XML_NAMESPACE_SMEV;

		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		$result = GetXMLValue($xml, 'ServiceName', $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;
	}	
	
	function SMEVSetServiceName(&$xml, $value)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
		
		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		$result = SetXMLValue($xml, 'ServiceName', $value, $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;
	}	
	
	function SMEVSetTypeCode(&$xml, $value)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
	
		global $SMEV_TYPECODE;

		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		$result = SetXMLValue($xml, 'TypeCode', $SMEV_TYPECODE[$value], $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;			
	}	

	function SMEVGetTypeCode(&$xml)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
	
		global $SMEV_TYPECODE;
		
		global $SVEM_TYPECODE_UKNOWN;
		
		$result = $SVEM_TYPECODE_UKNOWN;
		
		if (!isset($xml))
		{
			return $result;
		}		
	
		$typecode = GetXMLValue($xml, 'TypeCode', $SMEV_XML_NAMESPACE_SMEV);
		foreach ($SMEV_TYPECODE as $key => $value)
		{
			if ($value == $typecode)
			{
				$result = $key;
				break;
			}
		}

		return $result;
	}	
	
	function SMEVSetStatus(&$xml, $value)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
	
		global $SMEV_STATUS;

		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		$result = SetXMLValue($xml, 'Status', $SMEV_STATUS[$value], $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;			
	}	

	function SMEVGetStatus(&$xml)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
	
		global $SMEV_STATUS;
		
		global $SVEM_STATUS_UKNOWN;
		
		$result = $SVEM_STATUS_UKNOWN;
	
		if (!isset($xml))
		{
			return $result;
		}		
	
		$status = GetXMLValue($xml, 'Status', $SMEV_XML_NAMESPACE_SMEV);
		foreach ($SMEV_STATUS as $key => $value)
		{
			if ($value == $status)
			{
				$result = $key;
				break;
			}
		}

		return $result;
	}			
	
	function SMEVGetDate(&$xml)
	{
		global $SMEV_XML_NAMESPACE_SMEV;

		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		$result = SMEVGetDateFormat(GetXMLValue($xml, 'Date', $SMEV_XML_NAMESPACE_SMEV));
		
		return $result;
	}	
	
	function SMEVSetDate(&$xml, $value)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
		
		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		if (!isset($value))
		{
			$value = date('Y-m-d H:i:s');
		}
		
		$result = SetXMLValue($xml, 'Date', SMEVSetDateFormat($value), $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;
	}	
	
	function SMEVGetRequestIdRef(&$xml)
	{
		global $SMEV_XML_NAMESPACE_SMEV;

		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		$result = GetXMLValue($xml, 'RequestIdRef', $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;
	}	
	
	function SMEVSetRequestIdRef(&$xml, $value)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
		
		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		$result = SetXMLValue($xml, 'RequestIdRef', $value, $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;
	}			
	
	function SMEVGetOriginRequestIdRef(&$xml)
	{
		global $SMEV_XML_NAMESPACE_SMEV;

		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		$result = GetXMLValue($xml, 'OriginRequestIdRef', $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;
	}	
	
	function SMEVSetOriginRequestIdRef(&$xml, $value)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
		
		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		$result = SetXMLValue($xml, 'OriginRequestIdRef', $value, $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;
	}		
	
	function SMEVGetServiceCode(&$xml)
	{
		global $SMEV_XML_NAMESPACE_SMEV;

		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		$result = GetXMLValue($xml, 'ServiceCode', $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;
	}	
	
	function SMEVSetServiceCode(&$xml, $value)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
		
		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		$result = SetXMLValue($xml, 'ServiceCode', $value, $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;
	}		
	
	function SMEVGetCaseNumber(&$xml)
	{
		global $SMEV_XML_NAMESPACE_SMEV;

		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		$result = GetXMLValue($xml, 'CaseNumber', $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;
	}	
	
	function SMEVSetCaseNumber(&$xml, $value)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
		
		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		$result = SetXMLValue($xml, 'CaseNumber', $value, $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;
	}	
	
	function SMEVGetExchangeType(&$xml)
	{
		global $SMEV_XML_NAMESPACE_SMEV;

		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		$result = GetXMLValue($xml, 'ExchangeType', $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;
	}	
	
	function SMEVSetExchangeType(&$xml, $value)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
		
		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		$result = SetXMLValue($xml, 'ExchangeType', $value, $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;
	}
	
	function SMEVGetTestMsg(&$xml)
	{
		global $SMEV_XML_NAMESPACE_SMEV;

		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		$result = GetXMLValue($xml, 'TestMsg', $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;
	}	
	
	function SMEVSetTestMsg(&$xml, $value)
	{
		global $SMEV_XML_NAMESPACE_SMEV;
		
		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		$result = SetXMLValue($xml, 'TestMsg', $value, $SMEV_XML_NAMESPACE_SMEV);
		
		return $result;
	}		
	
	function SMEVSetSenderFromRecipient(&$input_xml, &$output_xml)
	{
		$result = false;
		
		if (!isset($input_xml) or !isset($output_xml))
		{
			return $result;
		}			
		
		$sender = SMEVGetSender($input_xml);
		if ($sender != false)
		{
			$result = SMEVSetRecipient($output_xml, $sender[0], $sender[1]);
		}
		
		return $result;
	}			
	
	function SMEVTrimXML(&$xml)
	{
		global $SMEV_XML_NAMESPACE_SMEV;

		$result = false;
		
		if (!isset($xml))
		{
			return $result;
		}			
		
		$NODES = array();

		$NODES[0] = 'RequestIdRef';
		$NODES[1] = 'OriginRequestIdRef';
		$NODES[2] = 'ServiceCode';
		$NODES[3] = 'CaseNumber';
		$NODES[4] = 'TestMsg';
		
		for ($i = 0; $i < sizeof($NODES); $i++)
		{
			$NODES[$i] = WIN1251ToUTF8($NODES[$i]);
			$node = GetXMLNode(&$xml, $NODES[$i], $SMEV_XML_NAMESPACE_SMEV);		
			if ($node != false)
			{
				if ($node->nodeValue == '')
				{
					DelXMLChild($node);
				}
			}		
		}
		
		$node = GetXMLNode(&$xml, 'Originator', $SMEV_XML_NAMESPACE_SMEV);

		if ($node != false)
		{
			$node2 = GetXMLNode(&$node, 'Code', $SMEV_XML_NAMESPACE_SMEV);
			if ($node2 != false)
			{
				if ($node2->nodeValue == '')
				{
					DelXMLChild($node);
				}				
			}		
		}
		
		$node = GetXMLNode(&$xml, 'AppData', $SMEV_XML_NAMESPACE_SMEV);

		if ($node != false)
		{
			if (!$node->hasChildNodes() and $node->nodeValue == '')
			{
				DelXMLChild($node);
			}		
		}
		
		$node = GetXMLNode(&$xml, 'AppDocument', $SMEV_XML_NAMESPACE_SMEV);

		if ($node != false)
		{
			if (!$node->hasChildNodes() and $node->nodeValue == '')
			{
				DelXMLChild($node);
			}		
		}
		
		$result = true;
		
		return $result;
	}		
?>