/* Copyright (c) 2009, L. IJsselstein and others
  Yournavigation.org All rights reserved.
 */

function YoursWaypointAdd(yourLs, i) {
	/*
	 * Create a new DOM element to enter waypoint info
	 */
	var wp = yourLs.addWaypoint(i);
	yourLs.selectWaypoint(wp.position);

	// Update the number of the end
	jQuery("li.waypoint[waypointnr='" + wp.position + "']").attr("waypointnr", wp.position + 1);

	// Add the DOM LI
	var wypt_li = YoursWaypointCreateDOM(yourLs, wp);
	jQuery(yourLs.route_via + " > li.waypoint:last-child").before(wypt_li);

	// By inserting new elements we may have moved the map
	map.updateSize();
	YoursWaypointRenumberUI (yourLs);
}

function YoursWaypointCreateDOM(yourLs, waypoint) {
	/*
	 * Create a new DOM element to enter waypoint info
	 */
	var waypointName;
	if (waypoint.type == "from") {
		waypointName = "start";
	} else if (waypoint.type == "to") {
		waypointName = "finish";
	} else {
		waypointName = "waypoint " + waypoint.position;
	}

	// If we already have two, enable both their delete buttons
	var wypt_li = $(document.createElement("li"));
	jQuery (wypt_li).attr("waypointnr", waypoint.position);
	jQuery(wypt_li).addClass("waypoint");

	var marker_image = $(document.createElement("img"));
	jQuery(marker_image).attr("src", waypoint.markerUrl());
	jQuery(marker_image).attr("alt", "Via:");
	jQuery(marker_image).attr("title", "Click to position " + waypointName +  " on the map");
	jQuery(marker_image).bind("click", function() {
		if (yourLs.Selected !== undefined && yourLs.Selected.position == this.parentNode.attributes.waypointnr.value) {
			// Already selected, deselect
			yourLs.selectWaypoint();
		} else {
			// Select
			yourLs.selectWaypoint(this.parentNode.attributes.waypointnr.value);
		}
	});
	jQuery(marker_image).addClass("marker");

	var text = $(document.createElement("input"));
	jQuery(text).attr("type", "text");
        jQuery(text).attr("name", "via_text");
        jQuery(text).attr("value", "e.g. Street, City");
        jQuery(text).bind("change", function() { elementChange(yourLs, this); });
        jQuery(text).bind("focus", function() { this.select(); });

	var del_button = $(document.createElement("input"));
        jQuery(del_button).attr("type", "image");
        jQuery(del_button).attr("name", "via_del_image");
        jQuery(del_button).attr("src", Drubu.getRutaImagenes() + "del.png");
        jQuery(del_button).attr("alt", "Remove " + waypointName + " from the map");
        jQuery(del_button).attr("title", "Remove " + waypointName + " from the map");
        jQuery(del_button).bind("click", function() { elementClick(yourLs, this); });
        jQuery(del_button).attr("value", "");
        jQuery(del_button).addClass("via_del_image");

        // When adding the 3rd waypoint, enable delete on the existing two.
	if (jQuery(yourLs.route_via + " li").length >= 2) {
		// Nic: The waypoints have already been renumbered here ??
		jQuery ("input.via_del_image", jQuery(yourLs.route_via + " li.waypoint[waypointnr='0']")).attr("disabled", "");
		jQuery ("input.via_del_image", jQuery(yourLs.route_via + " li.waypoint[waypointnr='2']")).attr("disabled", "");
	}
	else jQuery(del_button).attr ("disabled", "disabled");

	var via_image = $(document.createElement("img"));
        jQuery(via_image).attr("src", Drubu.getRutaImagenes() + "ajax-loader.gif");
        jQuery(via_image).css("visibility", "hidden");
        jQuery(via_image).attr("alt", "");
        jQuery(via_image).addClass("via_image");

	var via_message = $(document.createElement("span"));
        jQuery(via_message).addClass("via_message");

        jQuery(wypt_li).addClass("via");
        jQuery(wypt_li).append(marker_image);
        jQuery(wypt_li).append(' ');
        jQuery(wypt_li).append(text);
        jQuery(wypt_li).append(' ');
        jQuery(wypt_li).append(del_button);
        jQuery(wypt_li).append(' ');
        jQuery(wypt_li).append(via_image);
        jQuery(wypt_li).append(via_message);

	
	return wypt_li;
}

function waypointRemove(yourLs, waypointnr) {
	/*
	 * Remove a waypoint from the UI and the route object
	 */

	// Deselect waypoint
	if (yourLs.Selected !== undefined && yourLs.Selected.position == waypointnr) {
		yourLs.selectWaypoint();
	}

	// Delete waypoint
	yourLs.removeWaypoint(parseInt(waypointnr));

	// Remove from UI
	jQuery(yourLs.route_via + " li.waypoint[waypointnr='" + waypointnr + "']").remove();

	// Renumber in the UI
	YoursWaypointRenumberUI(yourLs);

	// When we are down to two, disable both their delete buttons
	if (jQuery(yourLs.route_via + " li").length <= 2) {
		jQuery ("input.via_del_image", jQuery(yourLs.route_via + " li.waypoint[waypointnr='0']")).attr("disabled", "disabled");
		jQuery ("input.via_del_image", jQuery(yourLs.route_via + " li.waypoint[waypointnr='1']")).attr("disabled", "disabled");
	}

	// Redraw map
	map.updateSize();
}

function YoursWaypointReorderCallback(yourLs, event, ui) {
	var oldPosition = parseInt(jQuery(ui.item.context).attr("waypointnr"));
	var newPosition = parseInt(jQuery(ui.item.context).prev().attr("waypointnr")) + 1;
	if (isNaN(newPosition)) {
		// The waypoint was moved to the first position
		newPosition = 0;
	}
	// Add the new waypoint
	var wptOld = yourLs.Waypoints[oldPosition];
	yourLs.addWaypoint(newPosition);
	yourLs.updateWaypoint(newPosition, wptOld.lonlat, wptOld.name)
	// Remove the old waypoint
	if (oldPosition > newPosition) {
		// After adding the new waypoint, the old position has increased
		oldPosition++;
	}
	yourLs.removeWaypoint(oldPosition);

	yourLs.selectWaypoint();
	map.updateSize();

	YoursWaypointRenumberUI(yourLs);
}

function YoursWaypointRenumberUI(yourLs) {
	jQuery(yourLs.route_via).children().each(function(index, wypt_li) {
		var waypointName;
		if (index == 0) {
			waypointName = "start";
		} else if (index == yourLs.Waypoints.length - 1) {
			waypointName = "finish";
		} else {
			waypointName = "waypoint " + index;
		}

		// Update HTML list
		jQuery(wypt_li).attr("waypointnr", index);

		var marker_image = jQuery("img.marker", wypt_li);
		marker_image.attr("src", yourLs.Waypoints[index].markerUrl());
		marker_image.attr("title", "Click to position " + waypointName +  " on the map");

		var del_button = jQuery("input[name='via_del_image']", wypt_li);
		del_button.attr("alt", "Remove " + waypointName + " from the map");
		del_button.attr("title", "Remove " + waypointName + " from the map");
	});
}

function YoursInitWaypoints(yourLs) {
	// Add begin waypoint
	jQuery(yourLs.route_via).append(YoursWaypointCreateDOM(yourLs, yourLs.Start));

	// Add end waypoint
	jQuery(yourLs.route_via).append(YoursWaypointCreateDOM(yourLs, yourLs.End));

	// Let the user choose begin waypoint first
	yourLs.selectWaypoint(yourLs.Start.position);
}

function YoursInit(yourLs, map, waypointsUlId, statusDivId, directionsDivId, infoDivId, selectVehicleCallback, selectAdjectiveCallback, permalinkChangeCallback) {
        yourLs.route_via = "#" + waypointsUlId;
        yourLs.status = "#" + statusDivId;
        yourLs.directions = "#" + directionsDivId;
        yourLs.info = "#" + infoDivId;
        yourLs.selectVehicle = selectVehicleCallback;
        yourLs.selectAdjective = selectAdjectiveCallback;
        yourLs.permalinkChange = permalinkChangeCallback;
        
        jQuery(yourLs.route_via).sortable({
                update: function (events, ui) { YoursWaypointReorderCallback (yourLayers, events, ui); }
        });
	OpenLayers.Feature.Vector.style['default'].strokeWidth = '2';
	OpenLayers.Feature.Vector.style['default'].fillColor = '#0000FF';
	OpenLayers.Feature.Vector.style['default'].strokeColor = '#0000FF';

	jQuery(yourLs.status).ajaxError (function() {
		jQuery(this).html('<font color="red">Calculation Error</font>');
	});

	// Map definition based on http://wiki.openstreetmap.org/index.php/OpenLayers_Simple_Example
	
	/* Initialize a Route object from the YourNavigation API */
	yourLs.reset (map);

	YoursInitWaypoints(yourLs);
	
	// Check if a permalink is used
	var mTotal = 0, mCnt = 0;
	document.location.search.replace(/\??(?:(!|%21)([0-9.-]+)(,|%2C)([0-9.-]+)?)/g, function ()
	{
		mTotal++;
	});
	document.location.search.replace(/\??(?:(!|%21)([0-9.-]+)(,|%2C)([0-9.-]+)?)/g, function ()
	{
		var lonlat = new OpenLayers.LonLat(parseFloat (arguments[4]),
		  parseFloat (arguments[2]));
		if (mCnt++ == 0) {
			yourLs.selectWaypoint(yourLs.Start.position);
		} else if (mCnt == mTotal) {
			yourLs.selectWaypoint(yourLs.End.position);
		} else {
			YoursWaypointAdd(yourLs, mCnt - 1);
		}
		yourLs.updateWaypoint("selected", lonlat.clone().transform(map.displayProjection, epsg900913));
	});
	yourLs.parameters.type = 'motorcar';
	document.location.search.replace(/\??(?:&v=([a-zA-Z]+)?)/g, function()
	{
		yourLs.parameters.type = arguments[1];
	});
	selectVehicleCallback ('motorcar', yourLs.parameters.type);
	yourLs.parameters.adj = 'recommended';
	document.location.search.replace(/\??(?:&adj=([a-z]+)?)/g, function()
	{
		yourLs.parameters.adj = arguments[1];
	});
	yourLs.selectAdjective('recommended', yourLs.parameters.adj);
	
	yourLs.selectWaypoint(undefined);
	yourLs.autoroute = true;
	prepareDrawRoute(yourLs);
/*	if(mCnt > 1) {
		yourLs.draw();
	} Let's rather wait for the user to hit 'Find Route' */
} //End of RoutingInit()

function YoursStatusUpdate(yourLs, code, result) {
// We could make this a method acting on a Yours Route object.
	switch (code) {
		case Yours.status.routeFinished:
			var ins = '';
			for (i = 0; i < yourLs.Segments.length; i++) {
				ins += '<p>' + yourLs.Segments[i].instructions + '</p>';
			}
			jQuery(yourLs.directions).html(ins);
			notice("", yourLs.status);
			
			var seg_ul;
			var seg_li;
			
			// Add segment info (nodes, distance), totals and permalink
			jQuery(yourLs.info).empty();
			jQuery(yourLs.info).append("Segments:");
			seg_ul = $(document.createElement("ul"));
			jQuery(seg_ul).attr("class", "segments");
			jQuery(yourLs.info).append(seg_ul);
			
			for (i = 0; i < yourLs.Segments.length; i++) {
				seg_li = $(document.createElement("li"));
				
				var seg_div = $(document.createElement("div"));
				jQuery(seg_div).addClass("segment");
				MyFirstSegment = yourLs.Segments[i];

				jQuery(seg_div).append((i + 1) + ") ");
				if (MyFirstSegment.distance === undefined) {
					jQuery(seg_div).append(jQuery(document.createElement("i")).append("Unavailable"));
				} else {
					jQuery(seg_div).append("length = "+Math.round(MyFirstSegment.distance * 100) / 100+" km, nodes = "+MyFirstSegment.nodes);
				}
				jQuery(seg_li).append(seg_div);
				jQuery(seg_ul).append(seg_li);
			}
			if (yourLs.completeRoute) {
				jQuery(yourLs.info).append("Route:");
			} else {
				jQuery(yourLs.info).append(jQuery(document.createElement("font")).attr("color", "red").append("Route (partial):"));
			}
			seg_ul = $(document.createElement("ul"));
			jQuery(seg_ul).attr("class", "segments");
			jQuery(yourLs.info).append(seg_ul);
			
			seg_li = $(document.createElement("li"));
			jQuery(seg_ul).append(seg_li);
			jQuery(seg_li).append("Distance = "+Math.round (yourLs.distance * 100) / 100 +" km");
			
			seg_li = $(document.createElement("li"));
			jQuery(seg_ul).append(seg_li);
			jQuery(seg_li).append("OSM nodes = "+yourLs.nodes);
			
			// Zoom in to the area around the route
			if (!result.quiet) {
				map.zoomToExtent(yourLs.Layer.getDataExtent());
			}
			//seg_li = $(document.createElement("li"));
			//jQuery(seg_ul).append(seg_li);
			
			// Rather update the permalink when the waypoints are placed or moved
			//if (yourLs.completeRoute) {
				// Create a permalink
				// FIXME: User can add waypoints while calculating route, in which case permalink() will fail
			//	jQuery(seg_li).append(jQuery(document.createElement("a")).attr("href", yourLs.permalink()).append("Permalink"));
			//}
			break;
		case Yours.status.starting:
			//alert('starting');
			//jQuery(message_div).attr("src","images/ajax-loader.gif");
			jQuery(yourLs.status).html('Calculating route <img src="' + Drubu.getRutaImagenes() + 'ajax-loader.gif">');
			break;
		case Yours.status.error:
			//alert(result);
			jQuery(yourLs.status).html('<font color="red">' + result + '</font>');
		default:
			//jQuery(yourLs.status).html(result);
			break;
	}
	//alert(code);
}

/*
 * Called when a namefinder result is returned
 */
function myCallback(result) {
	if (result == "OK") {
		if (undefined !== wait_image) {
			wait_image.css("visibility", "hidden");
		}
		if (undefined !== message_div) {
			message_div.html('');
		}
	} else {
		wait_image.css("visibility", "hidden");
		message_div.html('<font color="red">' + result + '</font>');
	}
}

/*
 * Called when a reverse geocoder result is returned
 */
function YoursWaypointUpdate(yourLs, waypoint) {
	if (waypoint !== undefined) {
		jQuery(yourLs.route_via + " li[waypointnr='" + waypoint.position + "'] input").val(waypoint.name);
	}
}

function YoursSelectAdjective(yourLs, param) {
	yourLs.selectAdjective (yourLs.parameters.adj, param);
	yourLs.parameters.adj = param;
	prepareDrawRoute(yourLs); // See elementClick->calculate

	yourLs.draw();
}

function YoursSetVehicle(yourLs, newVehicle) {
//	jQuery("img[id='" + yourLs.parameters.type + "']")[0].style.border = "4px solid white";
	// Should also work: document.getElementBId(MyF..type).style.border = ...
	//old radio code jQuery("input[name='type'][value='" + element.id + "']").attr("checked", true);
//	element.style.border = "4px solid red";
	yourLs.selectVehicle (yourLs.parameters.type, newVehicle);
	yourLs.parameters.type = newVehicle;
/*	if (element.value == "cycleroute") {
		// Disable the shortest route option
		jQuery('input[name=method]')[0].checked = true;
		jQuery('input[name=method]')[1].disabled = true;
	} else {
		// Enable the shortest route option
		jQuery('input[name=method]')[1].disabled = false;
	}*/
	
	prepareDrawRoute(yourLs); // See elementClick->calculate

	yourLs.draw();
}

/*
 * Function to read input in text field and try to get a location from geocoder
 */
function elementChange(yourLs, element) {
	if (element.value.length > 0) {
		// Try to find element in waypoint list
		viaNr = element.parentNode.attributes['waypointnr'].value;
		wait_image = jQuery(".via_image", element.parentNode);
		message_div = jQuery(".via_message", element.parentNode);
		yourLs.selectWaypoint(viaNr);

		wait_image.css("visibility", "visible");
		// Choose between Namefinder or Nominatim
		//Yours.NamefinderLookup( Yours.lookupMethod.nameToCoord, jQuery.trim(element.value), MyFirstWayPoint, map, myCallback);
		Yours.NominatimLookup( Yours.lookupMethod.nameToCoord, jQuery.trim(element.value), yourLs.Selected, map, myCallback);
	}
}

function YoursClear (yourLs)
{
	yourLs.clear();
	//clear the routelayer;
	notice("",yourLs.info);
	notice("",yourLs.status);
	jQuery('.waypoint').remove();
	YoursInitWaypoints(yourLs);
	map.updateSize();
}

function elementClick(yourLs, element) {
	var mode;
	if (element.type == "button" || element.type == "image") {
		mode = element.name;
		switch (mode) {
			case 'via_del_image':
				waypointRemove(yourLs, element.parentNode.attributes.waypointnr.value);
				break;
			case 'reverse':
				jQuery(yourLs.info).empty();
				
				yourLs.reverse();
				break;
			case 'calculate':
				prepareDrawRoute(yourLs);

				yourLs.draw();
				break;
		}
	}
}

function prepareDrawRoute(yourLs) {
	jQuery(yourLs.info).empty();
				
/*	for (var i = 0; i < document.forms['parameters'].elements.length; i++) {
		element = document.forms['parameters'].elements[i];
		if (element.checked === true) {
			yourLs.parameters.type = element.value;
			break;
		}
	} old radio button code */
/*	for (var j = 0; j < document.forms['options'].elements.length; j++) {
		element = document.forms['options'].elements[j];
		if (element.value == 'fast' &&
			element.checked === true) {
			yourLs.parameters.fast = '1';
		} else if (element.value == 'short' &&
			element.checked === true) {
			yourLs.parameters.fast = '0';
		}
	} old radio button code */
	
	//Layer
	for (var k = 0; k < yourLs.map.layers.length; k++) {
		if (yourLs.map.layers[k].visibility === true) {
			switch (yourLs.map.layers[k].name) {
				case 'Cycle Networks':
					yourLs.parameters.layer = 'cn';
					break;
				case 'Cycle Map':
					yourLs.parameters.layer = 'cycle';
					break;
				case 'Mapnik':
					yourLs.parameters.layer = 'mapnik';
					break;
			}
		}
	}
}

/*
 * Show an image indicating progress
 */
function addWaitImage() {
	return '<img src="' + Drubu.getRutaImagenes() + 'wait_small.gif"/>';
}


/*
 * Simple function that uses jQuery to display a message in a given documentElement
 */
function notice(message, div, type) {
	switch (type) {
		case 'warning':
			message = '<font color="orange">' + message + '</font>';
			break;
		case 'error':
			message = '<font color="red">' + message + '</font>';
			break;
		default:
			message = message;
	}
	jQuery(div).html(message);
}
