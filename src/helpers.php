<?php 
use Zttp\Zttp;

/**             *** Geocode and Get City Location ***
 * geocodes and Returns an array cointaining the latitude and longitude of a given city.
 * @param string $country
 * @param string $name
 * @return array
 */
function getCityLocation($country, $name) {
    $response = Zttp::withHeaders(["X-Api-Key" => "Wxvgi0iEGtOFcmOkYBGAFg==CQaXOV3SK9BZ4Vql"])
    ->get("https://api.api-ninjas.com/v1/geocoding", [
        "city" => $name,
        "country" => $country,
    ]);
    $locationJson = $response->json();
    
    return [
        "lat" => $locationJson[0]["latitude"],
        "lng" => $locationJson[0]["longitude"],
    ];
}

   /**           *** Get Country Info ***
    * Returns an array containing information about a given country.
    * @param [type] $country
    * @return array
    */
function getCountryInfo($country){
    $response = Zttp::withHeaders(["X-Api-Key" => "Wxvgi0iEGtOFcmOkYBGAFg==CQaXOV3SK9BZ4Vql"])
    ->get("https://api.api-ninjas.com/v1/country", [
        "name" => $country,
    ]);
    $infoJson = $response->json();

    if (empty($infoJson)) {
        return [];
    }
    
    return [
        "name" => $infoJson[0]["name"],
        "population" => $infoJson[0]["population"] * 1000,
        "capital" => $infoJson[0]["capital"],
        "currency" => $infoJson[0]["currency"]["code"],
        "alpha2" => $infoJson[0]["iso2"],
    ];
}

/**             *** Get Country Wiki ***
 * Returns wikipedia articles about a given country
 *
 * @param string $country
 * @return array
 */
function getCountryWiki(string $country) {
    $name = getCountryName($country);
    $response = Zttp:: get("api.geonames.org/wikipediaSearchJSON?",[
        "username" => "jtblack",
        "q" => $name,
    ]);
    $wikiJson = $response->json();
    $wiki = [];
    foreach ($wikiJson["geonames"] as $article) {
        $wiki[] = [
            "title" => $article["title"],
            "summary" => $article["summary"],
            "url" => $article["wikipediaUrl"],
        ];
    }
    return $wiki;
}

/**             *** Get Country News ***
 * Returns a collection of recent news stories about a given country.
 *
 * @param string $country
 * @return array
 */
function getCountryNews($country) {
    $response = Zttp::withHeaders(["X-Api-Key" => "60221968afc3471cb412fb630372774c" ])->get("https://newsapi.org/v2/top-headlines", [
        "country" => $country,
        "pageSize" => 5,
    ]);
    $newsJson = $response->json();
    $news = [];
    foreach ($newsJson["articles"] as $article) {
        $news[] = [
            "title" => $article["title"],
            "summary" => $article["description"],
            "url" => $article["url"],
            "urlToImage" => $article["urlToImage"],
        ];
    }
    return $news;
}

/**         *** Get Country Weather ***
 * Returns an array containing the weather and daily forecast for a given country's capital city.
 * @param float $lat
 * @param float $lng
 * @return array 
 */
function getCountryWeather($lat, $lng) {
    $response = Zttp::get("https://api.openweathermap.org/data/2.5/onecall", [
        "appid" => "05d1bcce91d52882b453241db1f9e94b",
        "exclude" =>"minutely,hourly,alerts",
        "lat" => $lat,
        "lon" => $lng,
        "units" => "metric",
    ]);
    $weatherJson = $response->json();

    if (isset($weatherJson["cod"])) {
        return null;
    }

    $forecast = [];
    
    foreach ($weatherJson["daily"] as $day){
        $forecast[] = [
            "low" => $day["temp"]["min"],
            "high"=> $day["temp"]["max"],
            "day" => date("D", $day["dt"]),
        ];
    }

    return [
        "main" => $weatherJson["current"]["weather"][0]["main"],
        "description" => $weatherJson["current"]["weather"][0]["description"],
        "icon" => "https://openweathermap.org/img/wn/{$weatherJson['current']['weather'][0]['icon']}@2x.png",
        "temp" => $weatherJson["current"]["temp"],
        "forecast" => $forecast,
    ];
}

/**                                                                                             *** Get Alpha-3 ***
 * Converts an ISO alpha-2 code to an ISO alpha-3 code.
 *
 * @param string $alpha2 alpha-2 country code
 * @return null|string alpha-3 country code
 */
function getAlpha3(string $alpha2) {
    $countriesJson = file_get_contents(__DIR__ . '/data/isoCountries.json');
    $countries = json_decode($countriesJson, true);

    foreach ($countries as $country) {
        if (strtolower($alpha2) === strtolower($country["alpha-2"])) {
            return strtolower($country["alpha-3"]);
        }
    }

    return null;
}

/**                                                                                             *** Get Country Name ***
 * Converts an ISO alpha-2 code to a country name.
 *
 * @param string $alpha2 alpha-2 country code
 * @return null|string alpha-3 country code
 */
function getCountryName(string $alpha2) {
    $countriesJson = file_get_contents(__DIR__ . '/data/isoCountries.json');
    $countries = json_decode($countriesJson, true);

    foreach ($countries as $country) {
        if (strtolower($alpha2) === strtolower($country["alpha-2"])) {
            return strtolower($country["name"]);
        }
    }

    return null;
}


/**             *** Get GeoJson ***
 * Returns a geojson feature data structure from an ISO alpha-3 country code. 
 *
 * @param string $alpha3
 * @return null|string
 */
function getGeoJson(string $alpha3) {
    $geoJson = file_get_contents(__DIR__ . '/data/countries.geojson');
    $geoData = json_decode($geoJson, true);

    foreach ($geoData["features"] as $feature) {
        if (strtolower($alpha3) === strtolower($feature["properties"]["ISO_A3"])) {
            return $feature;
        }
    }

    return null;
}

/**             *** Get Cities ***
 * Returns an array of the 10 largest cities in a given country.
 * @param string $country
 * @return array $cities
 */
function getCities(string $country){
    $response = Zttp::withHeaders(["X-Api-Key" => "Wxvgi0iEGtOFcmOkYBGAFg==CQaXOV3SK9BZ4Vql"])
        ->get("https://api.api-ninjas.com/v1/city", [
            "country" => $country,
            "limit" => 10,
        ]);
    $cityJson = $response->json();
        //dd($cityJson);
    $cities = [];
    foreach ($cityJson as $city) {
        $cities[] = [
            "name" => $city["name"] ?? null,
            "country" => $city["country"] ?? null,
            "population" => $city["population"] ?? null,
            "isCapital" => $city["is_capital"] ?? null,
            "lat" => $city["latitude"] ?? null,
            "lng" => $city["longitude"] ?? null,
        ];
    }
    return $cities;
}

/**             *** Get City Info ***
 * Returns an array containing information about a given city
 * @param string $country
 * @param string $city
 */
function getCityInfo(string $country, string $name){
    $response = Zttp::withHeaders(["X-Api-Key" => "Wxvgi0iEGtOFcmOkYBGAFg==CQaXOV3SK9BZ4Vql"])
    ->get("https://api.api-ninjas.com/v1/city", [
        "country" => $country,
        "name" => $name,
        "limit" => 1,
    ]);
    $cityJson = $response->json();

    if (empty($cityJson)) {
        return [];
    }
    $city = [
            "name" => $cityJson[0]["name"],
            "country" => $cityJson[0]["country"],
            "population" => $cityJson[0]["population"],
            "isCapital" => $cityJson[0]["is_capital"],
            "lat" => $cityJson[0]["latitude"],
            "lng" => $cityJson[0]["longitude"],
    ];
    return $city;
}
/**             *** Get City News ***
 * Returns a collection of recent news stories about a given city.
 *
 * @param string $country
 * @return array
 */
function getCityNews(string $country, string $name) {
    $response = Zttp::withHeaders(["X-Api-Key" => "60221968afc3471cb412fb630372774c" ])->get("https://newsapi.org/v2/everything", [
        "q" => "{$name} {$country}",
        "pageSize" => 8,
    ]);
    $newsJson = $response->json();
    $news = [];
    foreach ($newsJson["articles"] as $article) {
        $news[] = [
            "title" => $article["title"],
            "summary" => $article["description"],
            "url" => $article["url"],
            "urlToImage" => $article["urlToImage"],
        ];
    }
    return $news;
}

/**             *** Get City Wiki ***
 * Returns a wikipedia article about subjects close to a given location
 *
 * @param string $country
 * @param string $name
 * @return array
 */
function getCityWiki(string $country, string $name) {
    $location = getCityLocation($country, $name);
    $response = Zttp:: get("api.geonames.org/findNearbyWikipediaJSON?",[
        "username" => "jtblack",
        "lat" => $location["lat"],
        "lng" => $location["lng"],
        "radius" => 2,
    ]);
    $wikiJson = $response->json();

    $wiki = [];
    foreach ($wikiJson["geonames"] as $article) {
        $wiki[] = [
            "title" => $article["title"],
            "summary" => $article["summary"],
            "url" => $article["wikipediaUrl"],
        ];
    }
    return $wiki;
}

/**         *** Get City Weather ***
 * Returns an array containing the weather and daily forecast for a given country's capital city.
 * @param float $lat
 * @param float $lng
 * @return array 
 */
function getCityWeather(string $country, string $name) {
    $location = getCityLocation($country, $name);

    $response = Zttp::get("https://api.openweathermap.org/data/2.5/onecall", [
        "appid" => "05d1bcce91d52882b453241db1f9e94b",
        "exclude" =>"minutely,hourly,alerts",
        "lat" => $location["lat"],
        "lon" => $location["lng"],
        "units" => "metric",
    ]);
    $weatherJson = $response->json();

    if (isset($weatherJson["cod"])) {
        return null;
    }

    $forecast = [];
    
    foreach ($weatherJson["daily"] as $day){
        $forecast[] = [
            "low" => $day["temp"]["min"],
            "high"=> $day["temp"]["max"],
            "day" => date("D", $day["dt"]),
        ];
    }

    return [
        "main" => $weatherJson["current"]["weather"][0]["main"],
        "description" => $weatherJson["current"]["weather"][0]["description"],
        "icon" => "https://openweathermap.org/img/wn/{$weatherJson['current']['weather'][0]['icon']}@2x.png",
        "temp" => $weatherJson["current"]["temp"],
        "forecast" => $forecast,
    ];
}