var map = L.map('backgroundMap', {
    "tap": false
}).setView([0, 0], 3);
var cityMarkers = L.layerGroup().addTo(map);
var countryBorder = L.layerGroup().addTo(map);
var body = document.querySelector("body");

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

// fetch country list
fetch('routes.php?route=countrylist')
    .then(response => response.json())
    .then((data) => {
        htmlInjector(data, "#country-list", "#country-list-template")
    });

// load country info from selected country
document.querySelector("#country-list").addEventListener("change", (event) => {
    loadCountryInfo(event.target.value);
    paintCityMarkers(cityMarkers, event.target.value);
});

// geocode location
window.navigator.geolocation.getCurrentPosition((position) => {
    fetch(`routes.php?route=geocode&lat=${position.coords.latitude}&lng=${position.coords.longitude}`)
        .then(response => response.json())
        .then((geoCodeData) => {
            loadCountryInfo(geoCodeData.alpha2, position.coords.latitude, position.coords.longitude);
            paintCityMarkers(cityMarkers, geoCodeData.alpha2);
        });
}, null, { enableHighAccuracy: false });