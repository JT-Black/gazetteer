<?php
//echo "<pre>";
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/helpers.php';
use Zttp\Zttp;

$route = $_GET["route"] ?? null;

switch ($route) {
        //              *** reverse geocode an iso-2 country code from latitude and longitude ***
    case 'geocode':
        $response = Zttp::withHeaders(["X-Api-Key" => "Wxvgi0iEGtOFcmOkYBGAFg==CQaXOV3SK9BZ4Vql"])
            ->get("https://api.api-ninjas.com/v1/reversegeocoding", [
                "lat" => $_GET["lat"],
                "lon" => $_GET["lng"]
            ]);
        $geoJson = $response->json();
        $alpha2 = [
            "alpha2" => $geoJson[0]["country"],
        ];
        echo json_encode($alpha2,JSON_PRETTY_PRINT);
        break;

            //              *** populate country list select menu ***
    case 'countrylist': 
        $response = Zttp::get("https://restcountries.eu/rest/v2/all");
        $countries = $response->json();
        $countryList = [];
        foreach ($countries as $country) {
            $countryList[] = [
                "name" => $country["name"],
                "alpha2" => $country["alpha2Code"],
                "alpha3" => $country["alpha3Code"],
                "flag" => $country["flag"],
            ];
        }
        echo json_encode($countryList,JSON_PRETTY_PRINT);
        break;

            //              *** get country info and location ***
    case 'getcountryinfo':
        $info = getCountryInfo($_GET["country"]);

        if (empty($info)){
            echo json_encode([]);
            die;
        }

        if (!isset($_GET["lat"]) || !isset($_GET["lng"])) {   
            $location = getCityLocation($info["alpha2"], $info["capital"]);    
        }
        else {
            $location = [
                "lat" => $_GET["lat"],
                "lng" => $_GET["lng"],
            ];
        }
 
        $countryInfo = [
            "geojson" => getGeoJson(getAlpha3($info["alpha2"])),
            "info" => getCountryInfo($info["alpha2"]),
            "wiki" => getCountryWiki($_GET["country"]),
            "news" => getCountryNews($info["alpha2"]),
            "weather" => getCountryWeather($location["lat"], $location["lng"]),

        ];
        //dd($countryInfo);
        echo json_encode($countryInfo,JSON_PRETTY_PRINT);
        break;

    case 'getcities':
        
            $cities = getCities($_GET["country"]);
            
        echo json_encode($cities,JSON_PRETTY_PRINT);
        break;

            //              *** get city info ***
    case 'getcityinfo':
            $cityInfo = [     
                "info" => getCityInfo($_GET["country"], $_GET["name"]),
                "wiki" => getCityWiki($_GET["country"], $_GET["name"]),
                "news" => getCityNews($_GET["country"], $_GET["name"]),
                "weather" => getCityWeather($_GET["country"], $_GET["name"]),
            ];

            //dd($cityInfo);
        echo json_encode($cityInfo,JSON_PRETTY_PRINT);
        break;


}
