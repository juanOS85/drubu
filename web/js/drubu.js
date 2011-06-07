var url_servidor = 'http://127.0.0.1/~juan/'
var ruta_imagenes = 'images/openstreetmaps/';

function recuperarPuntos() {
  var paradas = new Array();
  for (var i = 0; i < yourLayers.Waypoints.length; i++) {
    paradas[i] = new Object();
    paradas[i].lonlat = yourLayers.Waypoints[i].lonlat.clone().transform(epsg900913, this.map.displayProjection);
    paradas[i].position = yourLayers.Waypoints[i].position;
  }

  paradasJSON = Object.toJSON(paradas);

  new Ajax.Request(url_servidor + 'drubu/web/backend.php/rutas/crearFormsParadas', {
    parameters: {
      paradas: paradasJSON,
    },
    onSuccess: function() {
      $('markerto').style.visibility = "hidden";
      $('addwaypoint').style.visibility = "hidden";
      $('paradas').style.visibility = "visible";
    },
  });
}
