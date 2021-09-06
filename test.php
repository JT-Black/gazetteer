<?php

//echo "<pre>";


/**
 * Converts an ISO alpha-2 code to an ISO alpha-3 code.
 *
 * @param string $alpha2 alpha-2 country code
 * @return null|string alpha-3 country code
 */
function getAlpha3(string $alpha2) {
    $countriesJson = file_get_contents(__DIR__ . '/src/data/isoCountries.json');
    $countries = json_decode($countriesJson, true);

    foreach ($countries as $country) {
        if (strtolower($alpha2) === strtolower($country["alpha-2"])) {
            return strtolower($country["alpha-3"]);
        }
    }

    return null;
}

/**
 * Returns a geojson feature data structure from an ISO alpha-3 country code. 
 *
 * @param string $alpha3
 * @return null|string
 */
function getGeoJson(string $alpha3) {
    $geoJson = file_get_contents(__DIR__ . '/src/data/countries.geojson');
    $geoData = json_decode($geoJson, true);

    foreach ($geoData["features"] as $feature) {
        if (strtolower($alpha3) === strtolower($feature["properties"]["ISO_A3"])) {
            return json_encode($feature);
        }
    }

    return null;
}

var_dump(getGeoJson(42));