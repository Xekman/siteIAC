var snowmax=30;
var sinkspeed=0.9; 
var snowmaxsize=9;
var snowminsize=4;
var showimagesize = 16;
var showborderheight = 25;
var showborderwidth = 0;

var snow=new Array();
var marginbottom;
var marginright;
var timer;
var i_snow=0;
var x_mv=new Array();
var crds=new Array();
var lftrght=new Array();
var browserinfos=navigator.userAgent;
var ie5=document.all&&document.getElementById&&!browserinfos.match(/Opera/);
var ns6=document.getElementById&&!document.all;
var opera=browserinfos.match(/Opera/);
var browserok=ie5||ns6||opera;

function randommaker(range) 
{
	rand=Math.floor(range*Math.random());
	return rand;
}

function initSnow(max) 
{
	if (!browserok) 
	{
		return;
	}
	
	if (typeof max != 'undefined')
	{
		snowmax = max;
	}
	
	for (i=0;i<=snowmax;i++) 
	{
		var rnd = randommaker(6);
		document.write("<span class='show" + rnd + "' id='s"+i+"' style='size:" + rnd + "; top:-"+snowmaxsize+"px;'></span>");
	}	

	if (ie5 || opera) 
	{
		marginbottom = document.body.clientHeight - showimagesize - showborderwidth;
		marginright = document.body.clientWidth - showimagesize - showborderheight;
	}else{
		if (ns6) 
		{
			marginbottom = window.innerHeight - showimagesize - showborderwidth;
			marginright = window.innerWidth - showimagesize - showborderheight;
		} 
	}	
	
	var snowsizerange=snowmaxsize-snowminsize;
	for (i=0;i<=snowmax;i++) 
	{
		crds[i]=0;
		lftrght[i]=Math.random()*15;
		x_mv[i]=0.03+Math.random()/10;
		snow[i]=document.getElementById("s"+i);
		snow[i].size=randommaker(snowsizerange)+snowminsize;
		snow[i].sink=sinkspeed*snow[i].size/5;
		snow[i].posx=randommaker(marginright-snow[i].size)
		snow[i].posy=randommaker(2*marginbottom-marginbottom-2*snow[i].size);
		snow[i].style.left=snow[i].posx+"px";
		snow[i].style.top=snow[i].posy+"px";
	}
	moveSnow();
}

function moveSnow() 
{
	for(i=0;i<=snowmax;i++) 
	{
		crds[i]+=x_mv[i];
		snow[i].posy+=snow[i].sink;
		snow[i].style.left=snow[i].posx+lftrght[i]*Math.sin(crds[i])+"px";
		snow[i].style.top=snow[i].posy+"px";
		if (snow[i].posy>=marginbottom-2*snow[i].size || parseInt(snow[i].style.left)>(marginright-3*lftrght[i])) 
		{
			snow[i].posx=randommaker(marginright-snow[i].size)
			snow[i].posy=0;
		}
	}
	var timer=setTimeout("moveSnow()", 50);
}