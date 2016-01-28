// Server clock
// (must be initialized in the head with a PHP date object)

function padlength(what) {
		
	var output = (what.toString().length == 1) ? "0" + what : what;
	return output;
}

function displayTime() {
		
	serverdate.setSeconds(serverdate.getSeconds()+1);
						
	var datestring = padlength(serverdate.getFullYear() + " " + montharray[serverdate.getMonth()] + " " + serverdate.getDate()) + " ";
			
	// convert to 12 hour
			
	var currentHours = padlength(serverdate.getHours());
	var amPm = ( currentHours < 12 ) ? "AM" : "PM";
	var currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;
	currentHours = ( currentHours == 0 ) ? 12 : currentHours;
  		
	var timestring = currentHours + ":" + padlength(serverdate.getMinutes()) + ":" + padlength(serverdate.getSeconds()) + " " + amPm;
			
	document.getElementById("serverTime").innerHTML=datestring + " " + timestring;
}
		
		



//  Force getElementById to work

if(!document.getElementById) {
	if(document.all) {
		document.getElementById = function() {
			if(typeof document.all[arguments[0]] != "undefined") {
				return document.all[arguments[0]];
			} else {
				return null;
			}
		}
	} else if(document.layers) {
		document.getElementById = function() {
			if(typeof document[arguments[0]] != "undefined") {
				return document[arguments[0]];
			} else {
				return null;
			}
		}
	}
}




//  Alternative to "voided" links

function Hello() {
	// Empty function
}




//  Show and Hide objects

function objectShow(id) {
	document.getElementById(id).style.visibility = "visible";
}
function objectHide(id) {
	document.getElementById(id).style.visibility = "hidden";
}





//  Popup windows

function windowPop(url, id, width, height) {
	//var x = ((screen.width / 2) - ((width) / 2));
	//var y = ((screen.height / 2) - ((height) / 2));
	var x = (screen.width - width-30);
	var y = (screen.height - height-50);
	windowNew = window.open(url,id,"width="+ width +",height="+ height +",scrollbars=no,resizable=no,left="+ x +",top="+ y);
	windowNew.focus();
}




//  List Box Handler

function listBoxToggle(command) { 

	if (document.getElementById("listBox")) {

		if (command) {

			objectHide('listIndicator');
			objectShow('listBox');

		} else {

			objectHide('listBox');
			objectShow('listIndicator');
		}
	}
}

