                                                                        // HTML Injector
function htmlInjector(data, containerSelector, templateSelector) {
    let innersnippet = document.querySelector(templateSelector).innerHTML;
    let template = Handlebars.compile(innersnippet);
    let content = document.querySelector(containerSelector);
    console.log(content);
    content.innerHTML = template(data);
}
                                                                        // Load Country Info
function loadCountryInfo(alpha2, lat = null, lng = null){
    countryBorder.clearLayers();

    body.classList.add("is-loading");

    let params = new URLSearchParams({
        route : "getcountryinfo",
        country: alpha2,
    });
    
    if (lat && lng){
        params.append('lat', lat);
        params.append('lng', lng);
    }
                                                                        // fetch country geoCodeData
    fetch(`routes.php?${params.toString()}`)
    .then(response => response.json())
    .then((countryInfoData) => {
        body.classList.remove("is-loading");
        if (countryInfoData.geojson) {
            let geo = L.geoJSON(countryInfoData.geojson);
            countryBorder.addLayer(geo);
            let bounds = geo.getBounds();
            map.fitBounds(bounds);
        }

        htmlInjector(countryInfoData, "#country-info-container", "#country-info-template");
        Bulma.parseDocument();
    });
}

                                                                    // Paint Markers Function
function paintCityMarkers(layer, alpha2){
    
    layer.clearLayers();

    fetch(`routes.php?route=getcities&country=${alpha2}`)
    .then(response => response.json())
    .then((response) => {
        console.log(response);
        response.forEach((city) => {
            let marker = L.marker([city.lat, city.lng]);
            marker.on("click", ()=>{
                console.log(city);

                fetch(`routes.php?route=getcityinfo&name=${city.name}&country=${city.country}`)
                    .then(response => response.json())
                    .then((data) => {
                        //console.log(data)
                        htmlInjector(data, "#city-modal-container", "#city-modal-template");
                        Bulma.parseDocument();
                        let citymodal = Bulma('#city-modal').modal();
                        citymodal.open();
                        //htmlInjector(data, "#city-card-title", "#city-card-template");
                    });

                //let citymodal = Bulma('[data-modal="clickwindow"]').modal();
                //citymodal.on("open", ()=>{
                //console.log("open function working");
                    
                //})
                //citymodal.open();
            });
            layer.addLayer(marker);
        });
    })

}




