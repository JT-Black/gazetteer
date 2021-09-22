<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/helpers.php';

use Zttp\Zttp;

$route = $_GET["route"] ?? null;

switch ($route) {
    case 'geocode':
        $response = Zttp::withHeaders(["X-Api-Key" => "Wxvgi0iEGtOFcmOkYBGAFg==CQaXOV3SK9BZ4Vql"])
            ->get("https://api.api-ninjas.com/v1/reversegeocoding", [
                "lat" => $_GET["lat"],
                "lon" => $_GET["lng"]
            ]);
        $geoJson = $response->json();
        $alpha2 = [
            "alpha2" => $geoJson[0]["country"],
            "name" => ucwords(getCountryName($geoJson[0]["country"])),
        ];
        //dd($alpha2);
        echo json_encode($alpha2);
    break;

    case 'countrylist':
        $countriesJson = file_get_contents(__DIR__ . '/src/data/isoCountries.json');
        $countries = json_decode($countriesJson, true);
        $countryList = [];
        foreach ($countries as $country) {
            $countryList[] = [
                "name" => $country["name"],
                "alpha2" => $country["alpha-2"],
                "alpha3" => $country["alpha-3"],
            ];
        }
        echo json_encode($countryList);
    break;

    // case 'countrylist':
    //     $countriesJson = file_get_contents(__DIR__ . '/src/data/countryBorders.geo.json');
    //     $countries = json_decode($countriesJson, true);
    //     $countryList = [];
    //     foreach ($countries as $country) {
    //         $countryList[] = [
    //             "name" => $country["features"]["properties"]["name"],
    //             "alpha2" => $country["features"]["properties"]["iso_a2"],
    //             "alpha3" => $country["features"]["properties"]["iso_a3"],
    //         ];
    //     }
    //     echo json_encode($countryList);
        //dd($countrylist);
    break;

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

        echo json_encode($countryInfo);
    break;

    case 'getcities':
        $cities = getCities($_GET["country"]);

        echo json_encode($cities);
    break;

    case 'getcityinfo':
        $cityInfo = [
            "info" => getCityInfo($_GET["country"], $_GET["name"]),
            "wiki" => getCityWiki($_GET["country"], $_GET["name"]),
            "news" => getCityNews($_GET["country"], $_GET["name"]),
            "weather" => getCityWeather($_GET["country"], $_GET["name"]),
        ];

        echo json_encode($cityInfo);
    break;
}
