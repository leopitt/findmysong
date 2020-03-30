<?php

namespace Drupal\lyric_lookup\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ConfigForm.
 */
class ConfigForm extends ConfigFormBase {

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
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('musixmatch_api_key'),
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
      ->save();
  }

}
