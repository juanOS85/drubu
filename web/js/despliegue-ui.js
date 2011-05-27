var onclose;

function openSidebar(options) {
  options = options || {};

  if (onclose) {
    onclose();
    onclose = null;
  }

  if (options.title) {
    $("sidebar_title").innerHTML = options.title;
  }

  if (options.width) {
    $("sidebar").style.width = options.width; 
  } else {
    $("sidebar").style.width = "30%";
  }

  $("sidebar").style.display = "inline";

  resizeMap();

  onclose = options.onclose;
}

function closeSidebar() {
  $("sidebar").style.display = "none";

  resizeMap();

  if (onclose) {
    onclose();
    onclose = null;
  }
}

function updateSidebar(title, content) {
  $("sidebar_title").innerHTML = title;
  $("sidebar_content").innerHTML = content;
}

function openMapKey() {
  updateMapKey();

  openSidebar({ 
    title: "Map key",
    onclose: closeMapKey
  });

  map.events.register("zoomend", map, updateMapKey);
  map.events.register("changelayer", map, updateMapKey);
}

function closeMapKey() {
  map.events.unregister("zoomend", map, updateMapKey);
  map.events.unregister("changelayer", map, updateMapKey);
}

function updateMapKey() {
  var layer = map.baseLayer.keyid;
  var zoom = map.getZoom();

  new Ajax.Updater('sidebar_content', '/key',{asynchronous:true, evalScripts:true, method:'get', parameters:'layer=' + layer + '&zoom=' + zoom})
}

function startSearch() {
  updateSidebar("Search Results", "");
}

function describeLocation() {
  var args = getArgs($("viewanchor").href);
  new Ajax.Request('/geocoder/description', {asynchronous:true, evalScripts:true, onLoading:function(request){startSearch()}, parameters:'lat=' + args['lat'] + '&lon=' + args['lon'] + '&zoom=' + args['zoom']})
}

function setSearchViewbox() {
  var extent = getMapExtent();

  $("minlon").value = extent.left;
  $("minlat").value = extent.bottom;
  $("maxlon").value = extent.right; 
  $("maxlat").value = extent.top;
}

jQuery.noConflict();
var yourLayers = new Yours.Route();

var brokenContentSize = $("content").offsetWidth == 0;
var marker;
var map;

OpenLayers.Lang.setCode("es");

function SelectAdjective (oldAdjective, newAdjective) {
  document.getElementById (oldAdjective).style.background = "#efefef";
  document.getElementById (newAdjective).style.background = "#f9d543";
}

function SelectVehicle (oldVehicle, newVehicle) {
  document.getElementById (oldVehicle).style.border = "4px solid white";
  document.getElementById (newVehicle).style.border = "4px solid red";
}

function mapInit(){
  map = createMap("map");

  map.dataLayer = new OpenLayers.Layer("Data", { "visibility": false });
  map.dataLayer.events.register("visibilitychanged", map.dataLayer, toggleData);
  map.addLayer(map.dataLayer);
  yourLayers.Layer.events.register("visibilitychanged", yourLayers.Layer, toggleRouting);
  map.addLayers([yourLayers.Layer, yourLayers.Markers]);

  var $_GET = {};
  document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function() {
    $_GET[arguments[1]] = arguments[2];
  });
  var centre = new OpenLayers.LonLat(parseFloat ($_GET["lon"]), parseFloat ($_GET["lat"]));

  if ($_GET["zoom"])
    setMapCenter(centre, parseFloat ($_GET["zoom"]));
  else {
    var bbox = new OpenLayers.Bounds(-76.755981442279, 3.2940822281691, -76.27361297306, 3.5511148030461);
    var lon = -76.519;
    var lat = 3.444;
    var z = 14;
    var centro = new OpenLayers.LonLat(lon, lat);
    setMapCenter(centro, z);
  }

  updateLocation();

  map.events.register("moveend", map, updateLocation);
  map.events.register("changelayer", map, updateLocation);

  handleResize();

  // Now set up the routingbar
  jQuery("#nav_header").tabs();
  // Make the via points sortable
  YoursInit (yourLayers, map, "route_via", "status", "directions", "feature_info",
  SelectVehicle, SelectAdjective, updateLocation);

  // Cargar el JSON de panoramio
  OpenLayers.ProxyHost = "/cgi-bin/proxy.cgi?url=";
  url = "http://www.panoramio.com/map/get_panoramas.php";
  parametros = {
  'order':'popularity',
  'set':'full',
  'from':0,
  'to':50,
  'minx': bbox.left,
  'miny': bbox.bottom,
  'maxx': bbox.right,
  'maxy': bbox.top,
  'size':'thumbnail'
  } 

  OpenLayers.loadURL(url, parametros, this, mostrarFotos);
}

function toggleData() {
  if (map.dataLayer.visibility) {
    new Ajax.Request('browse/start', {asynchronous:true, evalScripts:true})
  } else if (map.dataLayer.active) {
    closeSidebar();
  }
}

function toggleRouting() {
  if (yourLayers.Layer.visibility) {
    $("routingbar").style.width = "30%";
    $("routingbar").style.display = "block";
    closeSidebar(); // This will call resizeMap ()
  } else { //if (yourLayers.Layer.active) {
    $("routingbar").style.width = "30 px";
    $("routingbar").style.display = "block";
    yourLayers.selectWaypoint(undefined);
    resizeMap();
  }
}

function getPosition() {
  return getMapCenter();
}

function getZoom() {
  return getMapZoom();
}

function setPosition(lat, lon, zoom, min_lon, min_lat, max_lon, max_lat) {
  var centre = new OpenLayers.LonLat(lon, lat);

  if (min_lon && min_lat && max_lon && max_lat) {
    var bbox = new OpenLayers.Bounds(min_lon, min_lat, max_lon, max_lat);
    setMapExtent(bbox);
  } else {
    setMapCenter(centre, zoom);
  }

  if (marker)
    removeMarkerFromMap(marker);

  marker = addMarkerToMap(centre, getArrowIcon());
}

function updateLocation() {
  var lonlat = getMapCenter();
  var zoom = map.getZoom();
  var layers = getMapLayers();
  var extents = getMapExtent();
  var expiry = new Date();
  var objtype;
  var objid;

  updatelinks(lonlat.lon, lonlat.lat, zoom, layers, extents.left, extents.bottom, extents.right, extents.top, objtype, objid);

  expiry.setYear(expiry.getFullYear() + 10); 
  document.cookie = "_osm_location=" + lonlat.lon + "|" + lonlat.lat + "|" + zoom + "|" + layers + "; expires=" + expiry.toGMTString();
}

function resizeContent() {
  var content = $("content");
  var rightMargin = parseInt(getStyle(content, "right"));
  var bottomMargin = parseInt(getStyle(content, "bottom"));

  content.style.width = document.documentElement.clientWidth - content.offsetLeft - rightMargin;
  content.style.height = document.documentElement.clientHeight - content.offsetTop - bottomMargin + "px";
}

function resizeMap() {
  var centre = map.getCenter();
  var zoom = map.getZoom();
  var sidebar_width = $("sidebar").offsetWidth;
  var routingbar_width = $("routingbar").offsetWidth;

  if (routingbar_width > 0) {
    routingbar_width = routingbar_width + 5;
  }

  if (sidebar_width > 0) {
    sidebar_width = sidebar_width + 5;
  }

  $("map").style.left = (sidebar_width + routingbar_width) + "px";

  if ($("content").offsetWidth > sidebar_width + routingbar_width) 
    $("map").style.width = ($("content").offsetWidth - sidebar_width - routingbar_width) + "px";

  if ($("content").offsetHeight > 2)
    $("map").style.height = ($("content").offsetHeight - 2) + "px";

  map.setCenter(centre, zoom);
}

function handleResize() {
  if (brokenContentSize) {
    resizeContent();
  }

  resizeMap();
}

mapInit();

window.onload = handleResize;
window.onresize = handleResize;
