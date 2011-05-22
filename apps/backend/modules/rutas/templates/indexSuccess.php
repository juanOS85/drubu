<?php use_helper('JavascriptBase') ?>
<div id="content">
  <div id="routingbar">
    <div id="nav_header">
      <ul>
        <li><a href="#fragment-route"><span>Ruta</span></a></li>
        <li><a href="#fragment-directions"><span>Camino</span></a></li>
        <li><a href="#fragment-informacion"><span>Informaci&oacute;n de ruta</span></a></li>
        <li><a href="#fragment-export"><span>Exportar</span></a></li>
      </ul>
    </div>
    <div id="fragment-route">
      <form id="route" name="route" action="#" onsubmit="return false;">
        <ul id="route_via" class="route_via">
        </ul>
        <ul>
          <li>
            <div>
              <?php echo image_tag('openstreetmaps/markers/yellow', 'alt=marker to height=30 style=vertical-align:middle;') ?>
              <input type="button" onclick="YoursWaypointAdd(yourLayers);" value="A&ntilde;adir parada" tabindex="4"/>
            </div>
          </li>
        </ul>
      </form>
      <?php echo image_tag('openstreetmaps/transport/psv', 'title=Veh&iacute;culo de servicio p&uacute;blico alt=Veh&iacute;culo de servicio p&uacute;blico id=psv onclick=YoursSetVehicle(yourLayers, this.id); style=border:4px solid white;') ?>
      <form id="options" action="#">
        <input type="button" name="method" id="recommended" onclick="YoursSelectAdjective(yourLayers, this.id);" value="Recommended" />
        <input type="button" name="method" id="guardar" onclick="alert('TODO');" value="Guardar" />
        <br /><br />
        <input type="button" name="clear" onclick="YoursClear(yourLayers);" value="Limpiar" style="background:#efefef"/>
      </form>
      <div id="status"></div>
        <div id="fragment-info" class="former_nav_content">
          <div id="feature_info"></div>
        </div>
      </div>
      <div id="fragment-directions" class="nav_content">
        <div id="directions"></div>
      </div>
      <div id="fragment-informacion" class="nav_content">
      	<div id="datos">
      	  <?php echo $rutaForm ?>
      	</div>
      </div>
      <div id="fragment-export" class="nav_content">
        <form id="export" action="#">
          <p>Export</p>
          <ul>
            <li><input type="radio" name="type" value="gpx" checked="checked"  />GPS exchange format (.gpx)</li>
            <li><input type="radio" name="type" value="wpt"/>Waypoint (.wpt)</li>
          </ul>
          <p>
            <input type="button" name="export" value="Export" onclick="Yours.Export(yourLayers);" />
          </p>
        </form>
      </div>
    </div>
    <div id="sidebar">
      <table class="sidebar_title" width="100%">
        <tr>
          <td align="left" id="sidebar_title"></td>
          <td align="right"><a href="javascript:closeSidebar()">Close</a></td>
        </tr>
      </table>
      <div id="sidebar_content"></div>
    </div>
    <?php echo javascript_tag('
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
    ') ?>
    <?php echo javascript_tag('
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

        new Ajax.Updater(\'sidebar_content\', \'/key\', {asynchronous:true, evalScripts:true, method:\'get\', parameters:\'layer=\' + layer + \'&zoom=\' + zoom})
      }
    ') ?>
    <?php echo javascript_tag('
      function startSearch() {
        updateSidebar("Search Results", "");
      }

      function describeLocation() {
        var args = getArgs($("viewanchor").href);
        new Ajax.Request(\'/geocoder/description\', {asynchronous:true, evalScripts:true, onLoading:function(request){startSearch()}, parameters:\'lat=\' + args[\'lat\'] + \'&lon=\' + args[\'lon\'] + \'&zoom=\' + args[\'zoom\']})
      }

      function setSearchViewbox() {
        var extent = getMapExtent();

        $("minlon").value = extent.left;
        $("minlat").value = extent.bottom;
        $("maxlon").value = extent.right;
        $("maxlat").value = extent.top;
      }
    ') ?>
    <noscript>
      <div id="noscript">
        <p>You are either using a browser that does not support JavaScript, or you have disabled JavaScript.</p>
        <p>OpenStreetMap uses JavaScript for its slippy map.</p>
        <p>You may want to try the <?php echo link_to('Tiles@Home static tile browser', 'http://tah.openstreetmap.org/Browse/') ?> if you are unable to enable JavaScript.</p>
      </div>
    </noscript>
    <div id="map">
      <div id="permalink">
        <?php echo link_to('Permalink', 'rutas/index', array('id' => 'permalinkanchor')) ?><br/>
        <?php echo link_to('Shortlink', 'rutas/index', array('id' => 'shortlinkanchor')) ?><br/>
        <?php echo link_to('(CC) BY-SA 2010 OpenStreetMap Contributors', 'http://www.openstreetmap.org') ?>
      </div>
      <div id="galeria"></div>
    </div> 
    <div id="attribution">
      <table width="100%">
        <tr>
          <td align="left">http://creativecommons.org/licenses/by-sa/2.0/</td>
          <td align="right">http://openstreetmap.org</td>
        </tr>
        <tr>
          <td colspan="2" align="center">Licensed under the Creative Commons Attribution-Share Alike 2.0 license by the OpenStreetMap project and its contributors.</td>
        </tr>
      </table>
    </div>
    <?php echo javascript_include_tag('openlayers/OpenLayers') ?>
    <?php echo javascript_include_tag('openlayers/OpenStreetMap') ?>
    <?php echo javascript_include_tag('map') ?>
    <?php echo javascript_include_tag('routing') ?>
    <?php echo javascript_include_tag('yours') ?>
    <?php echo javascript_include_tag('jquery/jquery-1.4.2.min') ?>
    <?php echo javascript_include_tag('jquery/jquery-ui-1.8.custom.min') ?>
    <?php echo javascript_include_tag('panoramio') ?>
    <?php echo javascript_tag('
      jQuery.noConflict();
      var yourLayers = new Yours.Route();
    ') ?>
    <?php echo javascript_tag('
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
        var centre = new OpenLayers.LonLat(parseFloat ($_GET["lon"]),
        parseFloat ($_GET["lat"]));
        
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
          \'order\':\'popularity\',
          \'set\':\'full\',
          \'from\':0,
          \'to\':50,
          \'minx\': bbox.left,
          \'miny\': bbox.bottom,
          \'maxx\': bbox.right,
          \'maxy\': bbox.top,
          \'size\':\'thumbnail\'
        } 
        
        OpenLayers.loadURL(url, parametros, this, mostrarFotos);
      }

      function toggleData() {
        if (map.dataLayer.visibility) {
          new Ajax.Request(\'browse/start\', {asynchronous:true, evalScripts:true})
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
    ') ?>
  </div>
</div>
