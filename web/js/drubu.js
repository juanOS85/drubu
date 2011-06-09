var url_servidor = 'http://127.0.0.1/~juan/'
var ruta_imagenes = 'images/openstreetmaps/';

/**
 *
 */
function crearFormRuta() {
  var nombreRutaLabel = document.createTextNode('Nombre Ruta');

  var nombreRuta = document.createElement('input');
  nombreRuta.setAttribute('id', 'nombre_ruta');
  nombreRuta.setAttribute('type', 'text');

  var submitRuta = document.createElement('button');
  submitRuta.setAttribute('type', 'button');
  submitRuta.setAttribute('value', 'Guardar');
  submitRuta.setAttribute('onclick', 'guardarRuta()');

  $('datos').appendChild(nombreRutaLabel);
  $('datos').appendChild(nombreRuta);
  $('datos').appendChild(submitRuta);
}

/**
 *
 */
function guardarRuta() {
  var ruta = new Object();
  ruta.nombre = $('nombre_ruta').value;

  alert(ruta.nombre);

  rutaJSON = Object.toJSON(ruta);

  new Ajax.Request(url_servidor + 'drubu/web/backend.php/rutas/guardarRuta', {
    parameters: {
      ruta: rutaJSON,
    },
    onSuccess: function(response) {
      alert('guardo!');
    },
    onFailure: function(response) {
      alert('no guardo :(');
    }
  });
}
