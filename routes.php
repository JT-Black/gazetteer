<?php
//echo "<pre>";
$route = $_GET["route"] ?? null;
switch ($route) {
    // country routes
    case 'countryinfo':
        $countryInfo = [
            "name" => "United States",
            "population" => "123,456,789",
            "capital" => "Washington DC",
            "currency" => "USD",
        ];
        echo json_encode($countryInfo,JSON_PRETTY_PRINT);
        break;
    case 'countrywiki':
        $countryWiki = [
            "wiki" => "https://en.wikipedia.org/wiki/United_States",
        ];
        echo json_encode($countryWiki,JSON_PRETTY_PRINT);
        break;
    case 'countrynews':
        $countryNews = [
            //"newsapi" => "apiKey=60221968afc3471cb412fb630372774c",
            [
                "title" => "Florida man has sex with alligator, kills it, then joins monastary",
                "content" => "Americans are getting dumber by the year - do we need babysitters?",
                "url" => "www.alltherealnews.com",
                "image" => "https://cdn.britannica.com/08/91308-050-59C4DF32/Crocodiles-alligators-teeth-snouts-crocodiles-mouth.jpg"
            ],
            [
                "title" => "Florida man has sex with alligator, kills it, then joins monastary",
                "content" => "Americans are getting dumber by the year - do we need babysitters?",
                "url" => "www.alltherealnews.com",
                "image" => "https://cdn.britannica.com/08/91308-050-59C4DF32/Crocodiles-alligators-teeth-snouts-crocodiles-mouth.jpg"
            ],
            [
                "title" => "Florida man has sex with alligator, kills it, then joins monastary",
                "content" => "Americans are getting dumber by the year - do we need babysitters?",
                "url" => "www.alltherealnews.com",
                "image" => "https://cdn.britannica.com/08/91308-050-59C4DF32/Crocodiles-alligators-teeth-snouts-crocodiles-mouth.jpg"
            ],
        ];
        echo json_encode($countryNews,JSON_PRETTY_PRINT);
        break;
    case 'countryweather':
        $countryWeather = [
            // weather api returned data
            "main" => "Drizzle",
            "description" => "slightly rainy with no chance of sunburn",
            "icon" => "https://openweathermap.org/img/wn/10d@2x.png",
            // mocked data
            "big-temp" => "17",
            "high0" => "17",
            "high1" => "18",
            "high2" => "15",
            "high3" => "21",
            "low0" => "10",
            "low1" => "8",
            "low2" => "10",
            "low3" => "12",
        ];
        echo json_encode($countryWeather,JSON_PRETTY_PRINT);
        break;

        // city routes

    case 'cityinfo':
        $cityInfo = [
            "name" => "Hatfield",
            "county" => "Hertfordshire",
            "population" => "56,789",
            "incorporated" => "1568",
        ];
        echo json_encode($cityInfo,JSON_PRETTY_PRINT);
        break;
    case 'citywiki':
        $cityWiki = [
            "wiki" => "https://en.wikipedia.org/wiki/Hatfield,_Hertfordshire",
        ];
        echo json_encode($cityWiki,JSON_PRETTY_PRINT);
        break;
    case 'citynews':
        $cityNews = [
            //"newsapi" => "apiKey=60221968afc3471cb412fb630372774c",
            [
                "title" => "Hatfield man has sex with alligator, kills it, then joins monastary",
                "content" => "Brits are getting dumber by the year - do we need babysitters?",
                "url" => "www.alltherealnews.com",
                "image" => "https://cdn.britannica.com/08/91308-050-59C4DF32/Crocodiles-alligators-teeth-snouts-crocodiles-mouth.jpg"
            ],
            [
                "title" => "Hatfield man has sex with alligator, kills it, then joins monastary",
                "content" => "Brits are getting dumber by the year - do we need babysitters?",
                "url" => "www.alltherealnews.com",
                "image" => "https://cdn.britannica.com/08/91308-050-59C4DF32/Crocodiles-alligators-teeth-snouts-crocodiles-mouth.jpg"
            ],
            [
                "title" => "Hatfield man has sex with alligator, kills it, then joins monastary",
                "content" => "Brits are getting dumber by the year - do we need babysitters?",
                "url" => "www.alltherealnews.com",
                "image" => "https://cdn.britannica.com/08/91308-050-59C4DF32/Crocodiles-alligators-teeth-snouts-crocodiles-mouth.jpg"
            ],
        ];
        echo json_encode($cityNews,JSON_PRETTY_PRINT);
        break;
    case 'cityweather':
        $cityWeather = [
            // weather api returned data
            "main" => "Drizzle",
            "description" => "slightly rainy with no chance of sunburn",
            "icon" => "https://openweathermap.org/img/wn/10d@2x.png",
            // mocked data
            "big-temp" => "14",
            "high0" => "16",
            "high1" => "8",
            "high2" => "18",
            "high3" => "14",
            "low0" => "6",
            "low1" => "4",
            "low2" => "12",
            "low3" => "6",
        ];
        echo json_encode($cityWeather,JSON_PRETTY_PRINT);
        break;
    default:
        throw new Exception('Route not found');
        break;
}


