function isBarCode35(text)
{	
	var result = false;
	var l = strlen(text);
	if (l == 8)
	{
		text = strtoupper(text);
		if (preg_match('/^([0-9ÉÖÓÊÅÍÃØÙÇÛÂÀÏĞÎËÄß×ÑÌÈÒÜ]{8,10}|[0-9B-Z]{8,10})$/', text))
		{
			result = true;
		}
	}
	return result;
}

function getHiddenBoxIdFromHiddenBoxArrayOnBarCodeText(form, hiddenbox, barcodetext)
{
	var id = '';
	var obj = document[form][hiddenbox];
	if (obj)
	{
		if (typeof obj.length == 'number')
		{
			for (var i = 0; i < obj.length; i++) 
			{
				if (obj[i].value == barcodetext)
				{
					id = obj[i].id;
					break;
				}
			}
		}else{
			if (obj.value == barcodetext)
			{
				id = obj.id;
			}
		}
	}
	return id;
}

function getCheckBoxIdFromCheckBoxArrayOnBarCode35(form, checkbox, barcode35)
{
	var id = '';
	var obj = document[form][checkbox];
	if (obj)
	{
		if (typeof obj.length == 'number')
		{
			for (var i = 0; i < obj.length; i++) 
			{
				if (obj[i].value == barcode35)
				{
					id = obj[i].id;
					break;
				}
			}
		}else{
			if (obj[i].value == barcode35)
			{
				id = obj.id;
			}
		}
	}
	return id;
}

function extractBarCode35(text)
{
	var result = false;
	text = strtr(text, 'ÉÖÓÊÅÍÃØÙÇÛÂÀÏĞÎËÄß×ÑÌÈÒÜ', 'QWERTYUIOPSDFGHJKLZXCVBNM');
	text = strtolower(text);
	text = str_replace('z', 'a', text);
	text = base_convert(text, 35, 10);
	while (strlen(text) < 8)
	{
		text = '0' + text;
	}
	var version = Number(substr(text, -1, 1));
	
	if (version == 1)
	{
		result = Number(substr(text, 0, strlen(text) - 1));		
	}		
	return result;
}