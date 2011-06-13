var url_servidor = 'http://127.0.0.1/~juan/'
var ruta_imagenes = 'images/openstreetmaps/';

/**
 *
 */
function crearFormRuta() {
  $('datos').innerHTML = '<form action="#">' +
  'Nombre <input id="nombre_ruta" type="text" />' +
  '<input type="button" value="Guardar" onclick="guardarRuta()" />' +
  '</form>';
}

/**
 *
 */
function guardarRuta() {
  var ruta = new Array();
  ruta[0] = $('nombre_ruta').value;

  var rutaData = {"data": ruta }
  
  var rutaJSON = Object.toJSON(rutaData);
  
  alert(rutaJSON);

  new Ajax.Request(url_servidor + 'drubu/web/backend.php/rutas/guardar', {
    parameters: {
      ruta: rutaJSON
    },
    onSuccess: function(responseJSON) {
      var response = responseJSON.responseText.evalJSON();
      if (response.success) {
        $('markerto').style.visibility = 'hidden';
        $('addwaypoint').style.visibility = 'hidden';
        $('paradas').style.visibility = 'visible';
        //desactiva los eventos de click sobre el mapa para evitar q muevan los waypoints
        jQuery(yourLayers.Markers.div.parentNode).css('cursor', 'default');
        yourLayers.controls.click.deactivate();
        alert('funcionó!');
      } else {
        alert(response.error); 
      }
    },
    onFailure: function(response) {
      alert('Error de conexion al servidor!');
    }
  });
}
