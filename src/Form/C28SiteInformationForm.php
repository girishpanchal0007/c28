<?php

namespace Drupal\c28\Form;

use Drupal\system\Form\SiteInformationForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Site information form alter for adding siteapikey.
 */
class C28SiteInformationForm extends SiteInformationForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Retrieve default site configuration.
    $site_config = $this->config('system.site');
    $form = parent::buildForm($form, $form_state);

    // Adding SiteAPI key.
    $form['site_information']['site_siteapikey'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Site API Key'),
      '#default_value' => !empty($site_config->get('siteapikey')) ? $site_config->get('siteapikey') : $this->t('No API Key yet'),
      '#description' => $this->t('Site API Key to access node json data'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // system.site.siteapikey configuration.
    $this->config('system.site')
      ->set('siteapikey', $form_state->getValue('site_siteapikey'))
      ->save();
    parent::submitForm($form, $form_state);

    // Add custom message when api save.
    drupal_set_message($this->t('Site API Key has been saved with Site API Key: @siteapikey', ['@siteapikey' => $form_state->getValue('site_siteapikey')]));
  }

}
