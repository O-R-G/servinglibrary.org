// query string
const params = new Proxy(new URLSearchParams(window.location.search), {
 	get: (searchParams, prop) => searchParams.get(prop),
});
const fullQueryString = window.location.href.lastIndexOf('?') === -1 ? '' : window.location.href.substr(window.location.href.lastIndexOf('?') + 1);
// implement infinite scrolling via ajax
var page = 1;
var isWaiting = false;
window.onscroll = function(ev) 
{
	var scrollTop = (document.documentElement && document.documentElement.scrollTop) || document.body.scrollTop;
	// make this so that it is *before* scrolled 
	// all the way to the bottom of the page
	var scrolledToBottom = (scrollTop + window.innerHeight) >= document.body.scrollHeight;
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
				document.getElementById("served").innerHTML += xmlhttp.responseText;
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
	// const requestUrl = 

	xmlhttp.open("GET", "views/time-ajax.php?page="+(page++) + '&' + fullQueryString, true);
	xmlhttp.send();
}