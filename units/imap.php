<?
function getPlain($str, $boundary)
{
$lines = explode("\n", $str);
$plain = false;
$res = '';
$start = false;
foreach ($lines as $line)
{
	if (strpos($line, 'text/plain') !== false) $plain = true;

	if (strlen($line) == 1 && $plain) 
	{
		$start = true;
		$plain = false;
		continue;
	}
	if ($start && strpos($line, 'Content-Type') !== false) $start = false;
	if ($start)
	$res .= $line;
}
$res = substr($res, 0, strpos($res, '--' . $boundary));
$res = base64_decode($res == '' ? $str : $res);
return $res;
};
function getParts($object, & $parts)
{

	if ($object->type == 1)
	{

		foreach ($object->parts as $part)
		{
			getParts($part, $parts);
		}
	}
	else
	{
		$p['type'] = $object->type;
		$p['encode'] = $object->encoding;
		$p['subtype'] = $object->subtype;
		$p['bytes'] = $object->bytes;
		if ($object->ifparameters == 1)
		{
			foreach ($object->parameters as $param)
			{
				$p['params'][] = array('attr' => $param->attribute,
				'val'  => $param->value);
			}
		}
		if ($object->ifdparameters == 1)
		{
			foreach ($object->dparameters as $param)
			{
				$p['dparams'][] = array('attr' => $param->attribute,
				'val'  => $param->value);
			}
		}
		$p['disp'] = null;
		if ($object->ifdisposition == 1)
		{
			$p['disp'] = $object->disposition;
		}
		$parts[] = $p;
	}
};




















function mail_mime_to_array($imap,$mid,$parse_headers=false) 
{ 
    $mail = imap_fetchstructure($imap,$mid, FT_UID); 
    $mail = mail_get_parts($imap,$mid,$mail,0); 
    if ($parse_headers) $mail[0]["parsed"]=mail_parse_headers($mail[0]["data"]); 
    return($mail); 
} 
function mail_get_parts($imap,$mid,$part,$prefix) 
{    
    $attachments=array(); 
    $attachments[$prefix]=mail_decode_part($imap,$mid,$part,$prefix); 
    if (isset($part->parts)) // multipart 
    { 
        $prefix = ($prefix == "0")?"":"$prefix."; 
        foreach ($part->parts as $number=>$subpart) 
            $attachments=array_merge($attachments, mail_get_parts($imap,$mid,$subpart,$prefix.($number+1))); 
    } 
    return $attachments; 
} 
function mail_decode_part($connection,$message_number,$part,$prefix) 
{ 
    $attachment = array(); 

    if($part->ifdparameters) { 
        foreach($part->dparameters as $object) { 
            $attachment[strtolower($object->attribute)]=$object->value; 
            if(strtolower($object->attribute) == 'filename') { 
                $attachment['is_attachment'] = true; 
                $attachment['filename'] = $object->value; 
            } 
        } 
    } 

    if($part->ifparameters) { 
        foreach($part->parameters as $object) { 
            $attachment[strtolower($object->attribute)]=$object->value; 
            if(strtolower($object->attribute) == 'name') { 
                $attachment['is_attachment'] = true; 
                $attachment['name'] = $object->value; 
            } 
        } 
    } 

    $attachment['data'] = imap_fetchbody($connection, $message_number, $prefix, FT_UID); 
    if($part->encoding == 3) { // 3 = BASE64 
        $attachment['data'] = base64_decode($attachment['data']); 
    } 
    elseif($part->encoding == 4) { // 4 = QUOTED-PRINTABLE 
        $attachment['data'] = quoted_printable_decode($attachment['data']); 
    } 
    return($attachment); 
} 
?>
