<?php

namespace Drupal\monsoon_tech_exam\Services;

class WeatherService {

  public function fetchWeatherData(){
    $httpClient = \Drupal::httpClient();
    $request = $httpClient->request('GET', 'https://api.openweathermap.org/data/2.5/weather?q=Dublin&APPID=169e10f26f321bd987fe9fb03bdaab85');

    return $request->getBody()->getContents();
  }

  public function storeWeatherData($weatherDataJSONString){
    \Drupal::state()->set('monsoon_tech_exam.weather_data', $weatherDataJSONString);
  }

  public function loadWeatherData(){
    return \Drupal::state()->get('monsoon_tech_exam.weather_data');
  }

  public function getWeatherData() {
    // Try to load stored weather data.
    $weatherData = $this->loadWeatherData();


    // Check if weather data was returned from State storage.
    if (empty($weatherData)) {
      // Fetch and store weather data if it is not yet stored.
      $weatherData = $this->fetchWeatherData();
      $this->storeWeatherData($weatherData);
    }

    return $weatherData;
  }

}
