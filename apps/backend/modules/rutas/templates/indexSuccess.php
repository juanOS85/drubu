<?php use_helper('JavascriptBase') ?>
<div id="content">
  <div id="routingbar">
    <div id="nav_header">
      <ul>
        <li><a href="#fragment-route"><span>Route</span></a></li>
        <li><a href="#fragment-directions"><span>Directions</span></a></li>
        <!-- <li><a href="#fragment-info"><span>Info</span></a></li> -->
        <li><a href="#fragment-export"><span>Export</span></a></li>
      </ul>
    </div>
    <div id="fragment-route">
      <form id="route" name="route" action="#" onsubmit="return false;">
        <ul id="route_via" class="route_via">
        </ul>
        <ul>
          <li>
            <div>
              <!-- <img src="markers/yellow.png" alt="marker to" height="30" style="vertical-align:middle;"/>
              <input type="button" onclick="YoursWaypointAdd(yourLayers);" value="Add Waypoint" tabindex="4"/> -->
            </div>
          </li>
        </ul>
      </form>
      <!-- <form id="parameters" action="#">
        <p>Type of transport</p>
        <ul>
          <li><input type="radio" name="type" onclick="typeChange(this);" value="motorcar" checked="checked" /><img src="transport/car.png" id="motorcar" onclick="typeChange(this);">Car</li>
          <li><input type="radio" name="type" onclick="typeChange(this);" value="hgv"/><img src="transport/bus.png">Heavy goods</li>
          <li><input type="radio" name="type" onclick="typeChange(this);" value="goods"/><img src="transport/bus.png">Goods</li>
          <li><input type="radio" name="type" onclick="typeChange(this);" value="psv"/><img src="transport/bus.png">Public service</li>
          <li><input type="radio" name="type" onclick="typeChange(this);" value="bicycle"/><img src="transport/bicycle.png">Bicycle</li>
          <li><input type="radio" name="type" onclick="typeChange(this);" value="motorcycle"/><img src="transport/motorbike.png">Motorcycle</li>
          <li><input type="radio" name="type" onclick="typeChange(this);" value="foot"/><img src="transport/pedestrian.png" id="foot" onclick="typeChange(this);">Foot</li>
          <li><input type="radio" name="type" onclick="typeChange(this);" value="moped"/><img src="transport/motorbike.png">Moped</li>
          <li><input type="radio" name="type" onclick="typeChange(this);" value="mofa"/><img src="transport/motorbike.png">Mofa</li>
        </ul>
      </form> -->
      <?php echo image_tag('openstreetmaps/transport/motorcar', 'title=Car alt=Car id=motorcar onclick=YoursSetVehicle(yourLayers, this.id); style=border:4px solid white;') ?>
      <?php echo image_tag('openstreetmaps/transport/hgv', 'title=Heavy Goods Vehicle alt=Heavy Goods Vehicle id=hgv onclick=YoursSetVehicle(yourLayers, this.id); style=border:4px solid white;') ?>
      <?php echo image_tag('openstreetmaps/transport/goods', 'title=Goods Vehicle alt=Goods Vehicle id=goods onclick=YoursSetVehicle(yourLayers, this.id); style=border:4px solid white;') ?>
      <?php echo image_tag('openstreetmaps/transport/psv', 'title=Public Service Vehicle alt=Public Service Vehicle id=psv onclick=YoursSetVehicle(yourLayers, this.id); style=border:4px solid white;') ?>
      <?php echo image_tag('openstreetmaps/transport/motorcycle', 'title=Motorcycle alt=Motorcycle id=motorcycle onclick=YoursSetVehicle(yourLayers, this.id); style=border:4px solid white;') ?>
      <?php echo image_tag('openstreetmaps/transport/bicycle', 'title=Bicycle alt=Bicycle id=bicycle onclick=YoursSetVehicle(yourLayers, this.id); style=border:4px solid white;') ?>
      <?php echo image_tag('openstreetmaps/transport/foot', 'title=Pedestrian alt=Pedestrian id=foot onclick=YoursSetVehicle(yourLayers, this.id); style=border:4px solid white;') ?>
      <?php echo image_tag('openstreetmaps/transport/mofa', 'title=Moped alt=Moped id=moped onclick=YoursSetVehicle(yourLayers, this.id); style=border:4px solid white;') ?>
      <?php echo image_tag('openstreetmaps/transport/mofa', 'title=Mofa alt=Mofa id=mofa onclick=YoursSetVehicle(yourLayers, this.id); style=border:4px solid white;') ?>
      <form id="options" action="#">
        <input type="button" name="method" id="recommended" onclick="YoursSelectAdjective(yourLayers, this.id);" value="Recommended" />
        <input type="button" name="method" id="shortest" onclick="YoursSelectAdjective(yourLayers, this.id);" value="Shortest" />
        <!--</div>-->
        <br /><br />
        <input type="button" name="clear" onclick="YoursClear(yourLayers);" value="Clear" style="background:#efefef"/>
      </form>
      <!-- These buttons are redundant
      <form id="calculate" action="#">
        <div id="route_action">
          <input type="button" name="calculate" onclick="elementClick(this);" value="Find route" tabindex="3"/>
          <input type="button" name="reverse" onclick="elementClick(this);" value="Reverse" tabindex="7"/>
        </div>
      </form> -->
      <p align="center"><font size=4><?php echo link_to('Help', 'http://wiki.openstreetmap.org/wiki/Osm.org_Routing_Demo') ?></font></p>
      <div id="status"></div>
        <div id="fragment-info" class="former_nav_content">
          <div id="feature_info"></div>
        </div>
        <?php // echo javascript_include_tag('http://static.polldaddy.com/p/3717617.js') ?>
        <!-- <noscript>
          <?php echo link_to('How can this demo be improved ?', 'http://polldaddy.com/poll/3717617/') ?>
          <span style="font-size:9px;">
          <?php echo link_to('customer surveys', 'http://polldaddy.com/features-surveys/') ?>
          </span>
        </noscript> -->
      </div>
      <div id="fragment-directions" class="nav_content">
        <div id="directions"></div>
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

        if (options.title) { $("sidebar_title").innerHTML = options.title; }

        if (options.width) { $("sidebar").style.width = options.width; }
        else { $("sidebar").style.width = "30%"; }

        $("sidebar").style.display = "block";

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
        <?php echo link_to('Shortlink', 'rutas/index', array('id' => 'shortlinkanchor')) ?>
      </div>
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
    <?php echo javascript_tag('
      jQuery.noConflict();
      var yourLayers = new Yours.Route();
    ') ?>
    <?php echo javascript_tag('
      var brokenContentSize = $("content").offsetWidth == 0;
      var marker;
      var map;

      OpenLayers.Lang.setCode("en");

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
          var bbox = new OpenLayers.Bounds(-8.62355613708496, 49.9061889648438, 1.75900018215179, 60.8458099365234);
          setMapExtent(bbox);
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
          $("routingbar").style.width = "0 px";
          $("routingbar").style.display = "none";
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
  <span id="greeting">
    <?php echo link_to('log in', 'http://www.openstreetmap.org/login?referer=%2F', array('id' => 'loginanchor', 'title' => 'Log in with an existing account')) ?>
    <?php echo link_to('sign up', 'http://www.openstreetmap.org/user/new', array('id' => 'registeranchor', 'title' => 'Create an account for editing')) ?>
  </span>
  <div>
    <ul id="tabnav">
      <li><?php echo link_to('View', 'rutas/index', array('class' => 'active', 'id' => 'viewanchor', 'title' => 'View the map')) ?></li>
      <li><?php echo link_to('Edit', 'http://www.openstreetmap.org/edit', array('class' => '', 'id' => 'editanchor', 'title' => 'Edit the map')) ?></li>
      <li><?php echo link_to('History', 'http://www.openstreetmap.org/browse', array('class' => '', 'id' => 'historyanchor', 'title' => 'View edits for this area')) ?></li>
      <li><?php echo link_to('Export', 'http://www.openstreetmap.org/export', array('onclick' => 'new Ajax.Request(\'/export/start\', {asynchronous:true, evalScripts:true}); return false;', 'id' => 'exportanchor', 'title' => 'Export map data')) ?></li>
      <li><?php echo link_to('GPS Traces', 'http://www.openstreetmap.org/traces', array('class' => '', 'id' => 'traceanchor', 'title' => 'Manage GPS traces')) ?></li>
      <li><?php echo link_to('User Diaries', 'http://www.openstreetmap.org/diary', array('id' => 'diaryanchor', 'title' => 'View user diaries')) ?><a href="http://www.openstreetmap.org/diary" id="diaryanchor" title="View user diaries">User Diaries</a></li>
    </ul>
  </div>
  <!-- <div id="left">
    <div id="logo">
      <center>
        <h1>OpenStreetMap</h1>
        <a href="index.html"><img alt="OpenStreetMap logo" border="0" height="120" src="images/osm_logo.png" width="120" /></a><br/>
        <h2 class="nowrap">The Free Wiki World Map</h2>
      </center>
    </div>
    <div id="intro">
      <p>OpenStreetMap is a free editable map of the whole world. It is made by people like you.</p>
      <p>OpenStreetMap allows you to view, edit and use geographical data in a collaborative way from anywhere on Earth.</p>
      <p>OpenStreetMap's hosting is kindly supported by the <?php echo link_to('UCL VR Centre', 'http://www.vr.ucl.ac.uk') ?> and <?php echo link_to('bytemark', 'http://www.bytemark.co.uk') ?>. Other supporters of the project are listed in the <?php echo link_to('wiki', 'http://wiki.openstreetmap.org/wiki/Partners') ?>.</p>
    </div>
      <div id="left_menu" class="left_menu">
      <a href="http://wiki.openstreetmap.org" title="Help &amp; Wiki site for the project">Help &amp; Wiki</a><br />
      <a href="copyright">Copyright &amp; License</a><br />
      <a href="http://blogs.openstreetmap.org/" title="News blog about OpenStreetMap, free geographical data, etc.">News blog</a><br />
      <a href="http://wiki.openstreetmap.org/wiki/Merchandise" title="Shop with branded OpenStreetMap merchandise">Shop</a><br />
      <a href="index.html#" onclick="openMapKey(); return false;" title="Map key for the mapnik rendering at this zoom level">Map key</a>
    </div>
    <div id="sotm" class="notice">
      <a href="http://www.stateofthemap.org/register/"><img alt="Come to the 2010 OpenStreetMap Conference, The State of the Map, July 9-11 in Girona!" border="0" src="images/sotm.png" title="Come to the 2010 OpenStreetMap Conference, The State of the Map, July 9-11 in Girona!" /></a>
    </div>
    <div class="optionalbox">
      <span class="whereami"><a href="javascript:describeLocation()" title="Describe the current location using the search engine">Where am I?</a></span>
      <h1>Search</h1>
      <div class="search_form">
        <div id="search_field">
          <form action="index.html" method="get" onsubmit="setSearchViewbox(); new Ajax.Request('/geocoder/search', {asynchronous:true, evalScripts:true, onComplete:function(request){endSearch()}, onLoading:function(request){startSearch()}, parameters:Form.serialize(this)}); return false;">
            <input id="query" name="query" type="text" value="" />
            <input id="minlon" name="minlon" type="hidden" />
            <input id="minlat" name="minlat" type="hidden" />
            <input id="maxlon" name="maxlon" type="hidden" />
            <input id="maxlat" name="maxlat" type="hidden" />
            <input name="commit" type="submit" value="Go" />
          </form>
        </div>
      </div>
      <p class="search_help">examples: 'Alkmaar', 'Regent Street, Cambridge', 'CB2 5AQ', or 'post offices near Lünen' <a href='http://wiki.openstreetmap.org/wiki/Search'>more examples...</a></p>
    </div>
    <center>
      <div class="donate">
        <a href="http://donate.openstreetmap.org/" title="Support OpenStreetMap with a monetary donation">Make a Donation</a>
      </div>
    </center>
  </div> -->
</div>
