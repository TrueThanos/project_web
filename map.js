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
); // base layer


console.info('sales are', sales);


var featuresLayer;
function addFeatures(currentFilter='') {
	////////////populate map with GeoJSON feature layer
	featuresLayer = new L.GeoJSON(data, {
	  onEachFeature: function (feature, marker) {
		const salesforsupermarket = sales.filter(function(salesitem) {
			return salesitem.super_market_id == feature.properties["@id"];
		});
		let saleshtml = "";

		salesforsupermarket.forEach(sale=> saleshtml += ("<li>" + "id: " + sale.product_id + " price: " + sale.price + " " + sale.date + " likes: " + sale.like + " dislikes: " + sale.dislike + " " + "</li>"));

		marker.bindPopup(
			"<div>" +
			"<h4>" + feature.properties.name + "</h4>" +
			"<ul>" + saleshtml + "</ul>" +
			"<a class='sales_button' href='./sales.php?super_market_id=" + feature.properties["@id"] + "'>Add offer</a>" +
			"</div>"	
		);
	  }
	});
	featuresLayer.addTo(mymap);
}

addFeatures();


function showPopups(currentFilter='') {
	mymap.removeLayer(featuresLayer);
	featuresLayer = new L.GeoJSON(data, {
		onEachFeature: function (feature, marker) {
			marker.bindPopup("<h4>" + feature.properties.name + "</h4>").addTo(mymap);
			if (feature.properties.name.startsWith(currentFilter)) {
				console.info('Showing popup for', feature.properties.name)
				marker.openPopup();
			}
		}
	});
}

// input event gets called when user writes on input box
inputElement.addEventListener('input', updateValue);

function updateValue(e) {	
	// e.target.value is the string that user wrote
	showPopups(e.target.value);
}