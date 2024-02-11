var pluginObject;
var pluginObjectIsInstall = false;

function initPlugin()
{

	var uAgent = navigator.userAgent.toLowerCase();

	if ((uAgent.search(/firefox/) < 0) && (uAgent.search(/chrome/) < 0))
	{
		return false;
	}
	
	var platform = navigator.platform.toLowerCase();

	if (platform.search(/win/) < 0)
	{
		return false;
	}	
	
	pluginObject = document.getElementById('plugin');

	if (!pluginObject)
	{
		return false;
	}	
	
	try
	{
		try 
		{
			if (pluginObject.IsInstall() != '3.5.0.1')
			{
				setInnerHTML('plugin_state', 'устарел');
				return false;
			}
		} catch(e) 
		{
		  return false;
		}

		pluginObjectIsInstall = true;
	}finally{
		if (!pluginObjectIsInstall)
		{
			if (getCookie('plugin') != 'none')
			{
				showObject('plugin_install');
			}else{
				showObject('plugin_open');
			}
		}else{
			showObject('plugin_assistant');
			showObject('plugin_tuna');
			showObject('panel');
		}
	}

}

function MozillaFullScreen()
{
	if (!pluginObjectIsInstall)
	{
		return false;
	}
	pluginObject.MozillaFullScreen();
}

function Execute(s1, s2)
{
	if (!pluginObjectIsInstall)
	{
		return false;
	}
	pluginObject.Execute(s1, s2);
}

function GetKeyboardLayout()
{
	if (!pluginObjectIsInstall)
	{
		return 0;
	}
	return pluginObject.GetKeyboardLayout();
}

function MozillaMinimized()
{
	if (!pluginObjectIsInstall)
	{
		return 0;
	}
	return pluginObject.MozillaMinimized();
}

function MozillaFind()
{
	if (!pluginObjectIsInstall)
	{
		return 0;
	}
	return pluginObject.MozillaFind();
}

function GetLocalUserName()
{
	if (!pluginObjectIsInstall)
	{
		return '';
	}
	return pluginObject.GetLocalUserName();
}

function GetLocalUserSID()
{
	if (!pluginObjectIsInstall)
	{
		return '';
	}
	return pluginObject.GetLocalUserSID();
}

function BeepNewMessage(data)
{
	if (!pluginObjectIsInstall)
	{
		return 0;
	}
	if (data != '')
	{
		return pluginObject.BeepRTTTL(data);
	}
}

function GetKeyboardLayoutOver()
{
	var kl = '?';
	var klname = 'Неопределен';

	if (pluginObjectIsInstall)
	{
		var v = pluginObject.GetKeyboardLayout();
		if (v == 67699721)
		{
			kl = 'EN';
			klname = 'Ангилийский';
		}
		if (v == 68748313)
		{
			kl = 'RU';
			klname = 'Русский';
		}
	}
	setInnerHTML('keyboardlayoutover', kl + ' | ' + klname);
}

function SetKeyboardLayoutRu()
{
	if (!pluginObjectIsInstall)
	{
		return false;
	}
	pluginObject.SetKeyboardLayout(68748313);
}

function SetKeyboardLayoutEn()
{
	if (!pluginObjectIsInstall)
	{
		return false;
	}
	pluginObject.SetKeyboardLayout(67699721);
}

function SetKeyboardLayoutAuto()
{
	if (!pluginObjectIsInstall)
	{
		return false;
	}

	var v = pluginObject.GetKeyboardLayout();

	if (v == 67699721)
	{
		SetKeyboardLayoutRu();
	}
	if (v == 68748313)
	{
		SetKeyboardLayoutEn();
	}

	GetKeyboardLayoutOver();
}

function LockWorkStation()
{
	if (!pluginObjectIsInstall)
	{
		return false;
	}
	pluginObject.LockWorkStation();
}