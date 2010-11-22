function load_date(date) {
	$('#dates').load('/dash/dates/'+date)
	$('#downloads').load('/dash/downloads/'+date);
	$('#database').load('/dash/database/'+date);
}

// Google Analytics
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
try {
	var pageTracker = _gat._getTracker("UA-3341219-4");
	pageTracker._trackPageview();
} catch(err) {}
