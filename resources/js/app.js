var map = L.map('backgroundMap', {
    "tap": false
}).setView([50, 0], 3);
var cityMarkers = L.layerGroup().addTo(map);
var countryBorder = L.layerGroup().addTo(map);
var body = document.querySelector("body");

var cityMarker = L.ExtraMarkers.icon({
    shape: 'circle',
    markerColor: 'blue',
    prefix: 'fa',
    icon: 'industry',
    iconColor: 'white',
    iconRotate: 0,
    extraClasses: '',
    number: '',
    svg: false,
});

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    //attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

window.addEventListener("DOMContentLoaded", () => {
    window.setTimeout(() => {
        document.querySelector("#preloader").remove();
    }, 1000);
});

// fetch country list
fetch('routes.php?route=countrylist')
    .then(response => response.json())
    .then((data) => {
        htmlInjector(data, "#country-list", "#country-list-template")
    });

// load country info from selected country
document.querySelector("#country-list").addEventListener("change", (event) => {
    loadCountryInfo(event.target.value);
    setTimeout(() => {paintCityMarkers(cityMarkers, event.target.value)}, 4000);
});

// geocode location
window.navigator.geolocation.getCurrentPosition((position) => {
    fetch(`routes.php?route=geocode&lat=${position.coords.latitude}&lng=${position.coords.longitude}`)
        .then(response => response.json())
        .then((geoCodeData) => {
            loadCountryInfo(geoCodeData.alpha2, position.coords.latitude, position.coords.longitude);
            setTimeout(()=>{paintCityMarkers(cityMarkers, geoCodeData.alpha2)}, 4000);
            document.querySelector("#current-country").innerHTML = geoCodeData.name;
        });
}, null, { enableHighAccuracy: false });

document.querySelector("#country-namebox-container").addEventListener("click",(e) => {
    let countrymodal = Bulma('#country-modal').modal();
            countrymodal.open();
} )