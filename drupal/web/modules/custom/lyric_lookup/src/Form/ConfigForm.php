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
  protected $lyricLookupDefault;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->lyricLookupDefault = $container->get('lyric_lookup.default');
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
      ->set('debug', $form_state->getValue('debug'))
      ->save();
  }

}
