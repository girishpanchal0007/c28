<?php

namespace Drupal\c28\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Provides a base implementation for RouteSubscriber.
 */
class C28RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   *
   * Alter admin site config setting form.
   */
  protected function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('system.site_information_settings')) {
      $route->setDefault('_form', 'Drupal\c28\Form\C28SiteInformationForm');
    }
  }

}
