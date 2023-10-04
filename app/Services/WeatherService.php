<?php

namespace App\Services;

use GuzzleHttp\Client;

class WeatherService
{
    private $open_weather_map_key = "cdd7d259ef91b9679a3a2c59fca09fb5";
    private $accu_weather_key = "khl2FBQKChkRXv716ThAqloLNRFGgXWu";

    private $providers = [
        'open-weather-map' => 'https://api.openweathermap.org/data/2.5/weather',
        'accu-weather' => 'http://dataservice.accuweather.com'
    ];

    public function getWeather($provider, $city)
    {

        if (!array_key_exists($provider, $this->providers)) {
            throw new \InvalidArgumentException('Invalid weather provider.');
        }

        $url = $this->providers[$provider];
        $client = new Client();

        if ($provider == 'accu-weather') {
            $response_city_details = $client->request('GET', $url . '/locations/v1/cities/search', [
                'query' => [
                    'q' => $city,
                    'apikey' => $this->accu_weather_key,
                ]
            ]);
            $city_details = json_decode($response_city_details->getBody(), true);
            $response = $client->request('GET', $url . '/currentconditions/v1/' . $city_details[0]['Key'], [
                'query' => [
                    'q' => $city_details[0]['Key'],
                    'apikey' => $this->accu_weather_key,
                ]
            ]);
            return $this->correctData(json_decode($response->getBody()), $city, 'accu-weather');
        }

        $response = $client->request('GET', $url, [
            'query' => [
                'q' => $city,
                'appid' => $this->open_weather_map_key,
                'units' => 'metric'
            ]
        ]);

        return $this->correctData(json_decode($response->getBody()), $city, 'open-weather-map');
    }

    public function correctData($data, $city, $provider)
    {
        $correct = [];
        logger($data);
        if ($provider == 'accu-weather') {
            $correct['temp'] = $data[0]->Temperature->Metric->Value;
            $correct['description'] = $data[0]->WeatherText;
            $correct['city'] = $city;
        } else {
            $correct['temp'] = $data['main']['temp'];
            $correct['description'] = $data['weather'][0]['description'];
            $correct['city'] = $city;
        }
        return  $correct;
    }
}
