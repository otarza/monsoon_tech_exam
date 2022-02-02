<?php

namespace Drupal\monsoon_tech_exam\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class WeatherConfigForm.
 */
class WeatherConfigForm extends FormBase
{

  /**
   * Drupal\Core\State\StateInterface definition.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    $instance = parent::create($container);
    $instance->state = $container->get('state');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'weather_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
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
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
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

    \Drupal::messenger()->addMessage($this->t('Configuration saved!'));

  }

}
