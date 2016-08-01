//Input the IDs of the IFRAMES you wish to dynamically resize to match its content height:
var adesk_IFrameIDs = new Array;
//Add a separate line for each iframe ID
//adesk_IFrameIDs[0] = "myframe";

//Should script hide iframe from browsers that don't support this script (non IE5+/NS6+ browsers. Recommended):
var adesk_HideIFrame = "no";

var adesk_GetFFVersion  = navigator.userAgent.substring(navigator.userAgent.indexOf("Firefox")).split("/")[1];
var adesk_FFextraHeight = parseFloat(adesk_GetFFVersion) >= 0.1 ? 16 : 0; //extra height in px to add to iframe in FireFox 1.0+ browsers

function callIFrameResizer() {
	for ( i=0; i < adesk_IFrameIDs.length; i++ ) {
		if ( document.getElementById )
			iFrameResizer(adesk_IFrameIDs[i]);
		// reveal iframe for lower end browsers? (see var above):
		if ( ( document.all || document.getElementById ) && adesk_HideIFrame != "yes" ) {
			var adesk_TempObj = document.all ? document.all[adesk_IFrameIDs[i]] : document.getElementById(adesk_IFrameIDs[i]);
			adesk_TempObj.style.display = "block";
		}
	}
}

function iFrameResizer(iframeID) {
	var currentIFrame = document.getElementById(iframeID);
	if ( currentIFrame && !window.opera ) {
		currentIFrame.style.display = "block";
		if ( currentIFrame.contentDocument && currentIFrame.contentDocument.body && currentIFrame.contentDocument.body.offsetHeight ) //ns6 syntax
			currentIFrame.height = currentIFrame.contentDocument.body.offsetHeight + adesk_FFextraHeight;
		else if ( currentIFrame.document && currentIFrame.document.body && currentIFrame.document.body.scrollHeight ) //ie5+ syntax
			currentIFrame.height = currentIFrame.document.body.scrollHeight;
		if ( currentIFrame.addEventListener )
			currentIFrame.addEventListener("load", iFrameReAdjust, false)
		else if ( currentIFrame.attachEvent ) {
			currentIFrame.detachEvent("onload", iFrameReAdjust); // Bug fix line
			currentIFrame.attachEvent("onload", iFrameReAdjust);
		}
	}
}

function iFrameReAdjust(adesk_LoadEvent) {
	var crossEvent = ( window.event ) ? event : adesk_LoadEvent;
	var rootIFrame = ( crossEvent.currentTarget ) ? crossEvent.currentTarget : crossEvent.srcElement;
	if ( rootIFrame )
		iFrameResizer(rootIFrame.id);
}

function iFrameLoad(iframeID, url) {
	if ( document.getElementById ) document.getElementById(iframeID).src = url;
}

if ( window.addEventListener )
	window.addEventListener("load", callIFrameResizer, false)
else if ( window.attachEvent )
	window.attachEvent("onload", callIFrameResizer)
else
	window.onload = callIFrameResizer;
