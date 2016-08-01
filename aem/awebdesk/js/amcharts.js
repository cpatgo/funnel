var adesk_amcharts_so = { };

function adesk_amchart(obj) {
	// first check all object properties
	if ( obj.type != 'column' && obj.type != 'line' && obj.type != 'pie' && obj.type != 'xy' ) obj.type = 'pie';
	if ( !obj.width ) obj.width = '100%';
	if ( !obj.height ) obj.height = '400';
	if ( !obj.bgcolor ) obj.bgcolor = '#FFFFFF';
	if (typeof obj.location == "undefined") obj.location = "admin";

	var minFlash = '8';
	var amchartpath;

	switch (obj.location) {
		default:
		case "admin":
			amchartacgpath = adesk_str_url("../awebdesk/");
			amchartpath = adesk_str_url(sprintf("../awebdesk/am%s/", obj.type)); break;
		case "public":
			amchartacgpath = adesk_str_url("awebdesk/");
			amchartpath = adesk_str_url(sprintf("awebdesk/am%s", obj.type)); break;
	}

		// now set new amChart
		adesk_amcharts_so[obj.divid] = new SWFObject(
			sprintf(amchartpath + "am%s.swf", obj.type),	// movie url
			sprintf("am%s", obj.type),
			obj.width,                          // width
			obj.height,                         // height
			minFlash,                           // minimum flash version
			obj.bgcolor
		);

	if ( false && AmCharts.recommended() == "js" ) {

		var amFallback = new AmCharts.AmFallback();
		amFallback.path = sprintf(amchartacgpath + "amcharts/flash/%s.swf", obj.type);

		// config files
		amFallback.settingsFile = obj.url;
		amFallback.dataFile = obj.url;

		// defaults
		//amFallback.settingsFile = 'amline_settings.xml';
		//amFallback.dataFile = 'amline_data.xml';

		// empties
		//amFallback.settingsFile = '';
		//amFallback.dataFile = '';

		amFallback.chartSettings = ''; // inline setings
		amFallback.chartData = ''; // inline data

		amFallback.pathToImages = amchartacgpath + "amcharts/javascript/images/";
		amFallback.type = obj.type;
		if (typeof obj.write != "undefined")
			amFallback.write(obj.divid);

	} else {

		adesk_amcharts_so[obj.divid].addVariable("path", amchartpath);
		adesk_amcharts_so[obj.divid].addVariable("settings_file", encodeURIComponent(obj.url));
		adesk_amcharts_so[obj.divid].addVariable("chart_data", "");
		adesk_amcharts_so[obj.divid].addVariable("preloader_color", "#999999");
		adesk_amcharts_so[obj.divid].addVariable("left", "0");

		adesk_amcharts_so[obj.divid].addVariable("additional_chart_settings", "<settings><export_as_image><file>" + amchartpath + "export.php</file></export_as_image><plot_area><margins><top>10</top></margins></plot_area></settings>");

		adesk_amcharts_so[obj.divid].addParam("wmode", "transparent");

		if (typeof obj.write != "undefined")
			adesk_amcharts_so[obj.divid].write(obj.divid);

	}

}
