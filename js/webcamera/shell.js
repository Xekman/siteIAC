function webcamGetState()
{
	var state = parseInt(getValue('webcamState'));

	if (!is_numeric(state) || state == '')
	{
		state = 0;
	}
	
	return parseInt(state);
}

function webcamSetScreen()
{
	var state = webcamGetState();
		
	var path = '';
	var classname = 'webcam_screen_b';
	var width = 0;
	var height = 0;
	
	if (state < 1)
	{
		hideObject('webcamScreenLightbox');
	}
	
	if (state == 1)
	{
		showObject('webcamScreenLightbox');
		path = getValue('webcamImagePath');
		classname = 'webcam_screen_a';
	}		
	
	if (state == 2)
	{
		showObject('webcamScreenLightbox');
		path = getValue('webcamScreenPath');
		classname = 'webcam_screen_a';
		
		obj = document.getElementById('webcamImage');
		if (obj)
		{
			width = obj.offsetWidth;
			height = obj.offsetHeight;
		}			
	}	
	if (path != '')
	{	
		path = path + '?uniq=' + Math.random();
		
		obj = document.getElementById('webcamScreenImage');
		if (obj)
		{
			obj.src = path;	
			if (width != 0)
			{
				obj.width = width;
				obj.height = height;
			}
		}			
		obj = document.getElementById('webcamScreenLightbox');
		if (obj)
		{
			obj.href = path;
		}			
	}
	obj = document.getElementById('webcamScreen');
	if (obj)
	{
		obj.className = classname;
	}		
}

function webcamSetView()
{
	var state = webcamGetState();
	
	if (state > 0)
	{
		showObject('webcamDelete');
	}else{
		hideObject('webcamDelete'); 
	}
	
	if (state > 2)
	{
		hideObject('webcamMainPanel'); 
		showObject('webcamBrowsPanel'); 
	}else{
		showObject('webcamMainPanel');
		hideObject('webcamBrowsPanel'); 
	}
	
	webcamSetScreen();
}

function webcamStateBrows(forward)
{
	var state = webcamGetState();
	
	if (forward)
	{
		state = state + 4;		
		openDialogBox('webcamFile');
	}else{
		state = state - 4;
	}
	
	setValue('webcamState', state);
	webcamSetView();
}

$(document).ready(function()
{
	var webcamImage =  $('#webcamImage');

	webcam.set_swf_url('js/webcamera/webcamera.swf');
	webcam.set_api_url('webcamera.php');
	webcam.set_quality(100);
	webcam.set_shutter_sound(false);
	webcamImage.html
	(
		webcam.get_html(webcamImage.width(), webcamImage.height())
	);

	var shootEnabled = false;

	$('#webcamOpen').click(function()
	{
		hideObject('webcamScreenImage'); 
		showObject('webcamImage');

		hideObject('webcamScreenPanel'); 
		showObject('webcamShootPanel');

		return false;
	});

	$('#webcamClose').click(function()
	{
		_webcamClose();
		return false;
	});

	$('#webcamShoot').click(function()
	{
		if(!shootEnabled)
		{
			return false;
		}
		webcam.freeze();
		hideObject('webcamShootPanel');
		showObject('webcamUploadPanel');
		return false;
	});

	$('#webcamDelete').click(function()
	{
		setValue('webcamState', -1);
		webcamSetView();
		return false;
	});

	$('#webcamCancel').click(function()
	{
		webcam.reset();
		_webcamCancel();
		return false;
	});

	$('#webcamUpload').click(function()
	{
		webcam.upload();
		webcam.reset();

		alert('Изображение успешно передано с веб-камеры');

		setValue('webcamState', 2);
		webcamSetScreen();
		_webcamCancel();
		_webcamClose();
		return false;
	});

	function _webcamClose()
	{
		hideObject('webcamImage'); 
		showObject('webcamScreenImage');

		hideObject('webcamShootPanel'); 
		showObject('webcamScreenPanel');

		webcamSetView();
	}
	
	function _webcamCancel()
	{
		hideObject('webcamUploadPanel');
		showObject('webcamShootPanel');
	}

	webcam.set_hook('onLoad',function()
	{
		shootEnabled = true;
	});

	webcam.set_hook('onComplete', function(msg)
	{
		msg = $.parseJSON(msg);
		if(msg.error)
		{
			alert(msg.message);
		}
	});

	webcam.set_hook('onError',function(e)
	{
		webcamImage.html(e);
	});
});