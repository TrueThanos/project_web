import {data} from './data.js';

const inputElement = document.querySelector("#name-filter");

let mymap = L.map("mapid", {
  zoom: 11,
  center: L.latLng([38.246242, 21.7350847])
}); //set center


mymap.setView([38.2462420, 21.7350847], 16);
let marker = L.marker([38.246242, 21.7350847], { draggable: "true" });
marker.addTo(mymap);
marker.bindPopup("<b>Πλατεία Γεωργίου</b>").openPopup();
marker.on("click", markerClick);
function markerClick(event) {
  this.getPopup()
    .setLatLng(event.latlng)
    .setContent("Συντεταγμένες σημείου: " + event.latlng.toString());
}


mymap.addLayer(
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png")
); //base layer


function addFeatures(currentFilter='') {
	////////////populate map with GeoJSON feature layer
	var featuresLayer = new L.GeoJSON(data, {
	  onEachFeature: function (feature, marker) {
		if (feature.properties.name.includes(currentFilter))
			marker.bindPopup("<h4>" + feature.properties.name + "</h4>");
		else
			return;
	  }
	});
	featuresLayer.addTo(mymap);
}

addFeatures();

inputElement.addEventListener('input', updateValue);

function updateValue(e) {	
	addFeatures(e.target.value);

}

