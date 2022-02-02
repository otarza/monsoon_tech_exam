<?php

namespace Drupal\monsoon_tech_exam\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Drupal\monsoon_tech_exam\Services\WeatherService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Stores weather config using State API.
 */
class WeatherConfigForm extends FormBase {

  /**
   * State service.
   *
   * @var Drupal\Core\State\StateInterface
   */
  protected StateInterface $state;

  /**
   * Weather service.
   *
   * @var Drupal\monsoon_tech_exam\Services\WeatherService
   */
  protected WeatherService $weatherService;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): WeatherConfigForm {
    $instance = parent::create($container);
    $instance->state = $container->get('state');
    $instance->weatherService = $container->get('monsoon_tech_exam.weather_service');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'weather_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#description' => $this->t('Please get API key from: https://openweathermap.org.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $this->state->get('monsoon_tech_exam.api_key'),
      '#weight' => '0',
    ];

    $city_name = $this->state->get('monsoon_tech_exam.city_name');
    $form['city_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City Name'),
      '#description' => $this->t('Name of the city you would like to show weather from.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => ($city_name) ?: 'Dublin',
      '#weight' => '1',
    ];

    $cron_mode = $this->state->get('monsoon_tech_exam.cron_mode');
    $form['cron_mode'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Cron Mode'),
      '#description' => $this->t('When checked, data is fetched and stored on cron run. If this is not checked, data will be fetched every time it is shown in a block.'),
      '#default_value' => ($cron_mode) ?: 0,
      '#weight' => '2',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#weight' => '3',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    if (array_key_exists('api_key', $values)) {
      $this->state->set('monsoon_tech_exam.api_key', $values['api_key']);
    }

    if (array_key_exists('city_name', $values)) {
      $this->state->set('monsoon_tech_exam.city_name', $values['city_name']);
    }

    if (array_key_exists('cron_mode', $values)) {
      $this->state->set('monsoon_tech_exam.cron_mode', $values['cron_mode']);
    }

    // Retrieve and store information upon configuration update.
    $this->weatherService->storeWeatherData($this->weatherService->fetchWeatherData());

    \Drupal::messenger()->addMessage($this->t('Configuration saved!'));
  }

}
