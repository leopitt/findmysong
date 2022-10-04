<?php

namespace Drupal\lyric_lookup\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ConfigForm.
 */
class ConfigForm extends ConfigFormBase {

  /**
   * Drupal\lyric_lookup\LyricLookupServiceInterface definition.
   *
   * @var \Drupal\lyric_lookup\LyricLookupServiceInterface
   */
  protected $lyricLookupService;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->lyricLookupService = $container->get('lyric_lookup.default');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'lyric_lookup.config',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('lyric_lookup.config');
    $form['musixmatch_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Musixmatch API key'),
      '#default_value' => $config->get('musixmatch_api_key'),
    ];

    $form['spotify_client_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Spotify Client ID'),
      '#default_value' => $config->get('spotify_client_id'),
    ];

    $form['spotify_api_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Spotify API secret'),
      '#default_value' => $config->get('spotify_api_secret'),
    ];

    $form['debug'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Debug mode'),
      '#description' => $this->t('Enable debug logging.'),
      '#default_value' => $config->get('debug'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('lyric_lookup.config')
      ->set('musixmatch_api_key', $form_state->getValue('musixmatch_api_key'))
      ->set('spotify_client_id', $form_state->getValue('spotify_client_id'))
      ->set('spotify_api_secret', $form_state->getValue('spotify_api_secret'))
      ->set('debug', $form_state->getValue('debug'))
      ->save();
  }

}
