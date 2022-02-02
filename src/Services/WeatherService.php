<?php

namespace Drupal\monsoon_tech_exam\Services;

/**
 * A service providing functionality to communicate with weather API.
 */
class WeatherService {

  /**
   * Fetches weather data from API.
   */
  public function fetchWeatherData(): ?string {
    $contents = NULL;
    $api_key = \Drupal::state()->get('monsoon_tech_exam.api_key');
    if (!empty($api_key)) {
      $city_name = \Drupal::state()->get('monsoon_tech_exam.city_name');
      $request_options = [
        'query' => [
          'q' => ($city_name) ?: "Dublin",
          'appid' => $api_key,
        ],
      ];
      $httpClient = \Drupal::httpClient();
      $request = $httpClient->request('GET', 'https://api.openweathermap.org/data/2.5/weather', $request_options);
      $contents = $request->getBody()->getContents();
    }

    return $contents;
  }

  /**
   * Store weather data using State API.
   */
  public function storeWeatherData($weatherDataJSONString) {
    \Drupal::state()->set('monsoon_tech_exam.weather_data', $weatherDataJSONString);
  }

  /**
   * Load weather data using State API.
   */
  public function loadWeatherData() {
    return \Drupal::state()->get('monsoon_tech_exam.weather_data');
  }

  /**
   * Get weather data either from State API or weather API.
   */
  public function getWeatherData() {
    $weatherData = NULL;
    $api_key = \Drupal::state()->get('monsoon_tech_exam.api_key');
    if (!empty($api_key)) {
      // Try to load stored weather data.
      $weatherData = $this->loadWeatherData();
      // Check if weather data was returned from State storage.
      if (empty($weatherData)) {
        // Fetch and store weather data if it is not yet stored.
        $weatherData = $this->fetchWeatherData();
        $this->storeWeatherData($weatherData);
      }
    }

    return $weatherData;
  }

}
