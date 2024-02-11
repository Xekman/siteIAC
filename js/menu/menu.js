function menuInit(id, dir) 
{
	var cont = document.getElementById(id);
	
	if (dir == 1) 
	{		
		if (cont.maxh && cont.maxh <= cont.offsetHeight) 
		{
			return;
		}else{
			if (!cont.maxh) 
			{
				cont.style.display = 'block';
				cont.style.height = 'auto';
				cont.maxh = cont.offsetHeight;			
			}
		}
		menuDisplay(id, false);
	}else{		
		menuDisplay(id, true);
	}
}

function menuHide(id) 
{
	var cont = document.getElementById(id);
	cont.style.display = 'block';
	if (cont.offsetHeight < cont.maxh) 
	{
		menuDisplay(id, false);
	}
}

function menuDisplay(id, hide) 
{
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	if (hide) 
	{
		obj.style.display = 'none';
	}else{
		obj.style.display = 'block';
	}
}