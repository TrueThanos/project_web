import {data} from './data.js';
//
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
// we transform the string sales to an object
sales = JSON.parse(sales);

var featuresLayer;
function addFeatures(currentFilter='', selected_category) {
	// populate map with GeoJSON feature layer
	featuresLayer = new L.GeoJSON(data, {
	  filter: function(feature, layer) {
		// we filter some of the data to not be shown
		function checksalesinsupermarket(salesitem) {
			return salesitem.super_market_id == feature.properties["@id"];
		}		
		function checksaleincategory(salesitem) {
			return salesitem.category_id == selected_category;
		}
		const salesforsupermarket = sales.filter(checksalesinsupermarket);
		const salesincategory = salesforsupermarket.filter(checksaleincategory);

		// if no selected category show all markers or user selected a category then show only if category length > 0
		return selected_category == "" || salesincategory.length > 0;
	  },
	  onEachFeature: function (feature, marker) {
		const salesforsupermarket = sales.filter(function(salesitem) {
			return salesitem.super_market_id == feature.properties["@id"];
		});

		let saleshtml = "";

		salesforsupermarket.forEach(sale=> saleshtml += ("<li>" + "id: " + sale.product_id + " price: " + sale.price + " " + sale.date + " likes: " + sale.likes + " dislikes: " + sale.dislikes + " " + "<a class='review_button' href='./review.php?sale_id=" + sale["sale_id"] + "'>Review</a>" + "</li>"));

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

// 
addFeatures('', selected_category);

function showPopups(currentFilter='') {
	mymap.removeLayer(featuresLayer);
	featuresLayer = new L.GeoJSON(data, {
		onEachFeature: function (feature, marker) {
			marker.bindPopup("<h4>" + feature.properties.name + "</h4>").addTo(mymap);
			if (feature.properties.name.includes(currentFilter)) {
				console.info('Showing popup for', feature.properties.name)
				marker.openPopup();
			}
		}
	});
}

// input event gets called when user writes on input(super markets filter by name) box 
inputElement.addEventListener('input', updateValue);

function updateValue(e) {	
	// e.target.value is the string that user wrote
	showPopups(e.target.value);
}