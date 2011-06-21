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
              <?php echo image_tag('openstreetmaps/markers/yellow', 'id=markerto alt=marker to height=30 style=vertical-align:middle;') ?>
              <input id="addwaypoint" type="button" onclick="YoursWaypointAdd(yourLayers);" value="A&ntilde;adir parada" tabindex="4"/>
            </div>
          </li>
        </ul>
      </form>
      <?php echo image_tag('openstreetmaps/transport/psv', 'title=Veh&iacute;culo de servicio p&uacute;blico alt=Veh&iacute;culo de servicio p&uacute;blico id=motorcar onclick=YoursSetVehicle(yourLayers, this.id); style=border:4px solid white;') ?>
      <form id="options" action="#">
        <input type="button" name="method" id="recommended" onclick="YoursSelectAdjective(yourLayers, this.id);" value="Dibujar ruta" />
        <input type="button" name="method" id="guardar" onclick="Drubu.crearFormRuta()" value="Guardar" />
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
      	</div>
      	<div id="paradas">
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
  </div>
</div>
<?php echo javascript_include_tag('despliegue-ui') ?>
