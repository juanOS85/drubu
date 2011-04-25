var panoramio_style;

/**
 *
 */
function mostrarFotos(response) {
	var json = new OpenLayers.Format.JSON();
	var panoramio = json.read(response.responseText);

	// var panoramio =  eval('(' + response.responseText + ')');
	// Contamos el número de fotos que hay en la caja
	var features = new Array(panoramio.photos.length);
	OpenLayers.Util.getElement('galeria').innerHTML =""; //inicializo la galeria

	for (var i = 0; i < panoramio.photos.length; i++) {
		var upload_date = panoramio.photos[i].upload_date;
		var owner_name = panoramio.photos[i].owner_name;
		var photo_id = panoramio.photos[i].photo_id;
		var longitude = panoramio.photos[i].longitude;
		var latitude = panoramio.photos[i].latitude;
		var pheight = panoramio.photos[i].height;
		var pwidth = panoramio.photos[i].width;
		var photo_title = panoramio.photos[i].photo_title;
		var owner_url = panoramio.photos[i].owner_url;
		var owner_id = panoramio.photos[i].owner_id;
		var photo_file_url = panoramio.photos[i].photo_file_url;
		var photo_url = panoramio.photos[i].photo_url;

		// Para mostrar todas las imágenes
		// OpenLayers.Util.getElement('galeria').innerHTML += "<a href='"+ photo_url +"'><img src='"+photo_file_url + "' title='"+ photo_title +"'/></a>&nbsp;";

		// Defino un punto
		var fpoint = new OpenLayers.Geometry.Point(longitude,latitude);
		fpoint.transform(epsg4326, map.getProjectionObject() );

		// atributos
		var atributos = {
			'upload_date' : upload_date,
			'owner_name':owner_name,
			'photo_id':photo_id,
			'longitude':longitude,
			'latitude':latitude,
			'pheight':pheight,
			'pwidth':pwidth,
			'pheight':pheight,
			'photo_title':photo_title,
			'owner_url':owner_url,
			'owner_id':owner_id,
			'photo_file_url':photo_file_url,	
			'photo_url':photo_url
		}

		features[i] = new OpenLayers.Feature.Vector(fpoint, atributos ,panoramio_style);
	}
	addLayerPano(features);
}

/**
 *
 */
function addLayerPano(features) {
	// estilo punto
	var panoramio_style = new OpenLayers.StyleMap(OpenLayers.Util.applyDefaults({
		pointRadius: 7,
		fillOpacity: 1,
		externalGraphic: "images/openstreetmaps/markers/white.png"
	}, OpenLayers.Feature.Vector.style["default"]));

	var vectorPano = new OpenLayers.Layer.Vector("Imagenes", { styleMap: panoramio_style });
	vectorPano.addFeatures(features);
	map.addLayer(vectorPano);

	// add control para evento
	selectControl = new OpenLayers.Control.SelectFeature(vectorPano, {
		onSelect: onFeatureSelect,
		onUnselect: onFeatureUnselect
	});

	map.addControl(selectControl);
	selectControl.activate();
}

/**
 *
 */
function onPopupClose(evt) {
	selectControl.unselect(selectedFeature);
}

/**
 *
 */
function onFeatureSelect(feature) {
	selectedFeature = feature;

	mensaje = "<a href='http://www.panoramio.com'><img src='./panoramio_header-logo.png' alt='Panoramio' width='146' height='27' /></a><br>" +
	"<h2>" + feature.attributes.photo_title + "</h2><p>" +
	"<a href='"+ feature.attributes.photo_url + "'><img src='http://mw2.google.com/mw-panoramio/photos/small/" + feature.attributes.photo_id + ".jpg' border='0' alt=''></a><br>" +
	"autor: <a href='" + feature.attributes.owner_url + "'>" + feature.attributes.owner_name + "</a>";

	popup = new OpenLayers.Popup.FramedCloud("chicken", feature.geometry.getBounds().getCenterLonLat(), null, mensaje, null, true, onPopupClose);
	feature.popup = popup;
	map.addPopup(popup);
}

/**
 *
 */
function onFeatureUnselect(feature) {
	map.removePopup(feature.popup);
	feature.popup.destroy();
	feature.popup = null;
}   
