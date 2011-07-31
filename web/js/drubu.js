/**
 * Namespace Drubu
 */
var Drubu = function() {

  var URL_SERVIDOR = 'http://127.0.0.1/~juan/'
  var RUTA_IMAGENES = 'images/openstreetmaps/';
  var rutaId;

  return {
    /**
     *
     */
    crearFormRuta : function() {
      jQuery.ajax({
        url: URL_SERVIDOR + 'drubu/web/backend.php/buses/activos',
        success: function(response) {
          var buses = jQuery.parseJSON(response);

          $('markerto').style.visibility = 'hidden';
          $('addwaypoint').style.visibility = 'hidden';
         // $('formParadas').style.visibility = 'visible';
          //desactiva los eventos de click sobre el mapa para evitar q muevan los waypoints
          jQuery(yourLayers.Markers.div.parentNode).css('cursor', 'default');
          yourLayers.controls.click.deactivate();

          var formRuta = '<form action="#">' +
          'Nombre <input id="ruta[nombre]" type="text" />' + '<br />' +
          'Cantidad paradas <input id="ruta[total_paradas]" type="text" value="' + yourLayers.Waypoints.length + '" readonly="readonly" maxlength="3" size="3" />' + 
          'Duración <input id="ruta[duracion]" type="text" maxlength="8" size="8" />' + '<br />' +
          'Conductor <input id="ruta[conductor]" type="text" />'+'<br />' +
          'Descripci&oacute;n <textarea id="ruta[descripcion]" rows=5 cols=23></textarea>' + '<br />' +
          'Bus <select id="ruta[bus]">' +
          '<option value="-1">Seleccione un bus</option>';

          for (var i = 0; i < buses.length; i++) {
            formRuta += '<option value="' + buses[i].id + '">' + buses[i].num_bus + '</option>';
          }

          formRuta += '</select>' + '<br />' +
          '<input type="button" value="Guardar" onclick="Drubu.guardarRuta()" />' +
          '</form>';

          $('datos').innerHTML = formRuta;
          
          $('paradas').style.visibility = 'hidden';

          var formParadas = '<form action"#">'

          for (var i = 0; i < yourLayers.Waypoints.length; i++) {
            if (i == 0) {
              formParadas += '<img src="' + RUTA_IMAGENES + 'markers/route-start.png" alt="fin" height="30" style="vertical-align:middle" /><br />';
            } else if (i == (yourLayers.Waypoints.length - 1)) {
              formParadas += '<img src="' + RUTA_IMAGENES + 'markers/route-stop.png" alt="fin" height="30" style="vertical-align:middle" /><br />';
            } else {
              formParadas += '<img src="' + RUTA_IMAGENES + 'markers/number' + i + '.png" alt="via" height="30" style="vertical-align:middle" /><br />';
            }

            formParadas += 'Direccion <input id="parada[direccion' + i +']" type="text">' +
            'Barrio <input id="parada[barrio' + i + ']" type="text">' +
            'Hora de parada <input id="parada[hora' + i + ']" type="text">';
          }

          formParadas += '<input type="button" value="Guardar" onclick="Drubu.guardarParadas()"/>' +
          '</form>';

          $('paradas').innerHTML = formParadas;
        },
        /*failure: function(response) {
          alert('Error de conexion al servidor!');
        }*/
      });

      /*new Ajax.Request(URL_SERVIDOR + 'drubu/web/backend.php/buses/activos', {
        onSuccess: function(response) {
          var buses = response.responseText.evalJSON();

          $('markerto').style.visibility = 'hidden';
          $('addwaypoint').style.visibility = 'hidden';
         // $('formParadas').style.visibility = 'visible';
          //desactiva los eventos de click sobre el mapa para evitar q muevan los waypoints
          jQuery(yourLayers.Markers.div.parentNode).css('cursor', 'default');
          yourLayers.controls.click.deactivate();

          var formRuta = '<form action="#">' +
          'Nombre <input id="ruta[nombre]" type="text" />' + '<br />' +
          'Cantidad paradas <input id="ruta[total_paradas]" type="text" value="' + yourLayers.Waypoints.length + '" readonly="readonly" maxlength="3" size="3" />' + 
          'Duración <input id="ruta[duracion]" type="text" maxlength="8" size="8" />' + '<br />' +
          'Conductor <input id="ruta[conductor]" type="text" />'+'<br />' +
          'Descripci&oacute;n <textarea id="ruta[descripcion]" rows=5 cols=23></textarea>' + '<br />' +
          'Bus <select id="ruta[bus]">' +
          '<option value="-1">Seleccione un bus</option>';

          for (var i = 0; i < buses.length; i++) {
            formRuta += '<option value="' + buses[i].id + '">' + buses[i].num_bus + '</option>';
          }

          formRuta += '</select>' + '<br />' +
          '<input type="button" value="Guardar" onclick="Drubu.guardarRuta()" />' +
          '</form>';

          $('datos').innerHTML = formRuta;
          
          $('paradas').style.visibility = 'hidden';

          var formParadas = '<form action"#">'

          for (var i = 0; i < yourLayers.Waypoints.length; i++) {
            if (i == 0) {
              formParadas += '<img src="' + RUTA_IMAGENES + 'markers/route-start.png" alt="fin" height="30" style="vertical-align:middle" /><br />';
            } else if (i == (yourLayers.Waypoints.length - 1)) {
              formParadas += '<img src="' + RUTA_IMAGENES + 'markers/route-stop.png" alt="fin" height="30" style="vertical-align:middle" /><br />';
            } else {
              formParadas += '<img src="' + RUTA_IMAGENES + 'markers/number' + i + '.png" alt="via" height="30" style="vertical-align:middle" /><br />';
            }

            formParadas += 'Direccion <input id="parada[direccion' + i +']" type="text">' +
            'Barrio <input id="parada[barrio' + i + ']" type="text">' +
            'Hora de parada <input id="parada[hora' + i + ']" type="text">';
          }

          formParadas += '<input type="button" value="Guardar" onclick="Drubu.guardarParadas()"/>' +
          '</form>';

          $('paradas').innerHTML = formParadas;
        },
        onFailure: function(response) {
          alert('Error de conexion al servidor!');
        }
      });*/
    },

    /**
     *
     */
    guardarRuta : function() {
      var ruta = new Object();
      ruta.nombre = $('ruta[nombre]').value;
      ruta.bus = $('ruta[bus]').value;
      ruta.totalParadas = $('ruta[total_paradas]').value;
      ruta.duracion = $('ruta[duracion]').value;
      ruta.conductor = $('ruta[conductor]').value;
      ruta.descripcion = $('ruta[descripcion]').value;

      var rutaJSON = Object.toJSON(ruta);
      
      jQuery.ajax({
        url: URL_SERVIDOR + 'drubu/web/backend_dev.php/rutas/guardar',
        type: 'POST',
        data: {
          ruta: rutaJSON
        },
        success: function(response) {
          var respuesta = jQuery.parseJSON(response);

          if (respuesta.success) {
            $('paradas').style.visibility = 'visible';
            rutaId = respuesta.rutaId;
            alert('Ruta guardada exitosamente!');
          } else {
            alert('La ruta no pudo ser guardada');
          }
        },
        /*failure: function(response) {
          alert('Error de conexion al servidor!');
        }*/
      });

      /*new Ajax.Request(URL_SERVIDOR + 'drubu/web/backend_dev.php/rutas/guardar', {
        parameters: {
          ruta: rutaJSON
        },
        onSuccess: function(response) {
          var respuesta = response.responseText.evalJSON();

          if (respuesta.success) {
            $('paradas').style.visibility = 'visible';
            rutaId = respuesta.rutaId;
            alert('Ruta guardada exitosamente!');
          } else {
            alert('La ruta no pudo ser guardada');
          }
        },
        onFailure: function(response) {
          alert('Error de conexion al servidor!');
        }
      });*/
    },

    /**
     *
     */
    guardarParadas : function() {
      var paradas = new Array(yourLayers.Waypoints.length);

      for (var i = 0; i < yourLayers.Waypoints.length; i++) {
        var wlonlat = yourLayers.Waypoints[i].lonlat.clone().transform(epsg900913, map.displayProjection); 

        paradas[i] = new Object();
        paradas[i].longitud = Math.round(wlonlat.lon * 100000) / 100000;
        paradas[i].latitud = Math.round(wlonlat.lat * 100000) / 100000;
        paradas[i].numero = i + 1;
        paradas[i].direccion = $('parada[direccion' + i +']').value;
        paradas[i].barrio = $('parada[barrio' + i +']').value;
        paradas[i].hora = $('parada[hora' + i +']').value;
      }
      
      var paradasJSON = Object.toJSON(paradas);

      alert(paradasJSON);

      jQuery.ajax({
        url: URL_SERVIDOR + 'drubu/web/backend.php/rutas/guardarParadas',
        type: 'POST',
        data: {
          paradas: paradasJSON,
          ruta: rutaId
        },
        success: function(response) {
          var respuesta = jQuery.parseJSON(response);

          if (respuesta.success) {
            alert('Paradas guardadas exitosamente!');
          } else {
            alert('Las paradas no pudieron ser guardadas');
          }
        },
        /*failure: function(response) {
          alert('Error de conexion al servidor!');
        }*/
      });

      /*new Ajax.Request(URL_SERVIDOR + 'drubu/web/backend.php/rutas/guardarParadas', {
        parameters: {
          paradas: paradasJSON,
          ruta: rutaId
        },
        onSuccess: function(response) {
          var respuesta = response.responseText.evalJSON();

          if (respuesta.success) {
            alert('Paradas guardadas exitosamente!');
          } else {
            alert('Las paradas no pudieron ser guardadas');
          }
        },
        onFailure: function(response) {
          alert('Error de conexion al servidor!');
        }
      });*/
    },

      /**
     *
     */
    ceeRuta : function() {
      var valorSeleccionado = jQuery('#combo').val();

      if (valorSeleccionado == 0) {
      $('show').style.visibility = 'visible';
      } else if (valorSeleccionado == 1) {
      } else {
      }
    },

    /**
     *
     */
    getRutaImagenes : function() {
      return RUTA_IMAGENES;
    },
  }
}();
