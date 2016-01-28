// implement infinite scrolling via ajax

// set this variable via php
var issue = 8;


var isWaiting = false;

window.onscroll = function(ev) 
{
	var scrollTop = (document.documentElement && document.documentElement.scrollTop) || document.body.scrollTop;
	// make this so that it is *before* scrolled 
	// all the way to the bottom of the page
	var scrolledToBottom = (scrollTop + window.innerHeight*2) >= document.body.scrollHeight;
	if(!isWaiting && scrolledToBottom)
	{
	   	test();
	   	console.log("bottom");
    }
};

function test()
{
	isWaiting = true;
	if(window.XMLHttpRequest)
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else
	{
		// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange = function() 
	{
		if(xmlhttp.readyState < 4) 
		{
			// start loading animation
			// startLoad();
			console.log("waiting");
		}
		else if(xmlhttp.readyState == 4 && xmlhttp.status == 200) 
		{
			// stop loading animation
			// stopLoad();
			console.log("done");
			if(xmlhttp.responseText)
			{
				// load older posts
				document.getElementById("main-container").innerHTML += xmlhttp.responseText;
				isWaiting = false;
			}
			else
			{
				// no more posts to load
				// 'done' animation
				// animate(68);
			}
		}
	}
	xmlhttp.open("GET", "views/home-ajax.php?issue="+(issue--), true);
	xmlhttp.send();
}