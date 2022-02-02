<?php

namespace Drupal\monsoon_tech_exam\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\monsoon_tech_exam\Services\WeatherService;

/**
 * Provides weather info.
 *
 * @Block(
 *  id = "weather_block",
 *  admin_label = @Translation("Weather Block"),
 *  category = @Translation("Monsoon"),
 * )
 */
class WeatherBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Weather Service.
   *
   * @var \Drupal\monsoon_tech_exam\Services\WeatherService
   */
  protected WeatherService $weatherService;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, WeatherService $weatherService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->weatherService = $weatherService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('monsoon_tech_exam.weather_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $weatherData = $this->weatherService->getWeatherData();
    return [
      '#theme' => 'weather_block',
      '#weather_data' => ($weatherData) ? json_decode($weatherData) : NULL,
    ];
  }

}
