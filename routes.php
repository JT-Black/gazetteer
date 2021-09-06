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
        //var_dump($geoJson[0]["country"]);
        $alpha2 = [
            "alpha2" => $geoJson[0]["country"],
        ];
        echo json_encode($alpha2,JSON_PRETTY_PRINT);
        break;

            //              *** populate country list select menu ***
    case 'countrylist': 
        $response = Zttp::get("https://restcountries.eu/rest/v2/all");
        $countries = $response->json();
        //var_dump($countries[0]["name"]);
        $countryList = [];
        foreach ($countries as $country) {
            //var_dump($country["alpha2Code"]);
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

//              *** old city routes ***
//     case 'cityinfo':
//         $response = Zttp::withHeaders(["X-Api-Key" => "Wxvgi0iEGtOFcmOkYBGAFg==CQaXOV3SK9BZ4Vql"])
//             ->get("https://api.api-ninjas.com/v1/city", [
//                 "name" => $_GET["city"],
//             ]);
//         $infoJson = $response->json();
//         //var_dump($cityData[0]);
//         $cityInfo = [
//             "name" => $infoJson[0]["name"],
//             "population" => $infoJson[0]["population"] * 1000,
//             "latitude" => $infoJson[0]["latitude"],
//             "longitude" => $infoJson[0]["longitude"],
//         ];
//         echo json_encode($infoJson,JSON_PRETTY_PRINT);
//         break;

//     case 'citywiki':
//         $response = Zttp::withHeaders(["X-Api-Key" => "apikey"])
//             ->get("https://wikipedia API", [
//                 "name" => $_GET["city"],
//             ]);
//         $wikiJson = $response->json();
//         //var_dump($wikiJson);
//         $cityWiki = [
//             "wiki" => "https://en.wikipedia.org/wiki/Hatfield,_Hertfordshire",
//         ];
//         echo json_encode($cityWiki,JSON_PRETTY_PRINT);
//         break;

//     case 'citynews':
//         $response = Zttp::withHeaders(["X-Api-Key" => "60221968afc3471cb412fb630372774c" ])->get("https://newsapi.org/v2/top-headlines", [
//             "city" => $_GET["city"],
//             "pageSize" => 5,
//         ]);
//         $newsJson = $response->json();
//         //var_dump($newsJson);
//         $cityNews = [];
//         foreach ($newsJson["articles"] as $story) {
//             //var_dump($story);
//             $cityNews[] = [
//                 "title" => $story["title"],
//                 "summary" => $story["description"],
//                 "url" => $story["url"],
//                 "urlToImage" => $story["urlToImage"],
//             ];
//         }
//         echo json_encode($cityNews,JSON_PRETTY_PRINT);
//         break;

//     case 'cityweather':



//         $cityWeather = [
            
//         ];
//         echo json_encode($cityWeather,JSON_PRETTY_PRINT);
//         break;
//     default:
//         throw new Exception('Route not found');
//         break;
// }

    //              *** old country routes ***
    // case 'countryinfo':
    //     $response = Zttp::withHeaders(["X-Api-Key" => "Wxvgi0iEGtOFcmOkYBGAFg==CQaXOV3SK9BZ4Vql"])
    //         ->get("https://api.api-ninjas.com/v1/country", [
    //             "name" => $_GET["country"],
    //         ]);
    //     $infoJson = $response->json();
    //     //var_dump($infoJson);
    //     // population is * 1000 because api returns population data an order of magnitude lower than it is.
    //     $countryInfo = [
    //         "name" => $infoJson[0]["name"],
    //         "population" => $infoJson[0]["population"] * 1000,
    //         "capital" => $infoJson[0]["capital"],
    //         "currency" => $infoJson[0]["currency"]["code"],
    //     ];
    //     echo json_encode($countryInfo,JSON_PRETTY_PRINT);
    //     break;

    // case 'countrywiki':
    //     $response = Zttp::withHeaders(["X-Api-Key" => "Wxvgi0iEGtOFcmOkYBGAFg==CQaXOV3SK9BZ4Vql"])
    //         ->get("https://api.api-ninjas.com/v1/country", [
    //             "name" => $_GET["country"],
    //         ]);
    //     $wikiJson = $response->json();
    //     //var_dump($wikiJson);
    //     $countryWiki = [
    //         "wiki" => "https://en.wikipedia.org/wiki/United_States",
    //     ];
    //     echo json_encode($countryWiki,JSON_PRETTY_PRINT);
    //     break;

    // case 'countrynews':
    //     $response = Zttp::withHeaders(["X-Api-Key" => "60221968afc3471cb412fb630372774c" ])->get("https://newsapi.org/v2/top-headlines", [
    //         "country" => $_GET["country"],
    //         "pageSize" => 5,
    //     ]);
    //     $newsJson = $response->json();
    //     //var_dump($newsJson);
    //     $countryNews = [];
    //     foreach ($newsJson["articles"] as $story) {
    //         //var_dump($story);
    //         $countryNews[] = [
    //             "title" => $story["title"],
    //             "summary" => $story["description"],
    //             "url" => $story["url"],
    //             "urlToImage" => $story["urlToImage"],
    //         ];
    //     }
    //     echo json_encode($countryNews,JSON_PRETTY_PRINT);
    //     break;

    // case 'countryweather':
    //     $response = Zttp::get("https://api.openweathermap.org/data/2.5/onecall", [
    //         "appid" => "05d1bcce91d52882b453241db1f9e94b",
    //         "exclude" =>"minutely,hourly,alerts",
    //         "lat" => $_GET["lat"],
    //         "lon" => $_GET["lng"],
    //         "units" => "metric",
    //     ]);
    //     $weatherJson= $response->json();
    //     //var_dump($weatherJson["current"]["weather"][0]["description"]);
    //     $countryWeather = [
    //         "main" => $weatherJson["current"]["weather"][0]["main"],
    //         "description" => $weatherJson["current"]["weather"][0]["description"],
    //         "icon" => "https://openweathermap.org/img/wn/{$weatherJson['current']['weather'][0]['icon']}@2x.png",
    //         "temp" => $weatherJson["current"]["temp"],
    //     ];
    //     echo json_encode($countryWeather,JSON_PRETTY_PRINT);
    //     break;
          
        // $countryForecast = [
        //     "high0" => "17",
        //     "high1" => "18",
        //     "high2" => "15",
        //     "high3" => "21",
        //     "low0" => "10", // $weatherJson["daily"][0]["temp"]["min"]
        //     "low1" => "8",
        //     "low2" => "10",
        //     "low3" => "12",
        // ];
