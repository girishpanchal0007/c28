<?php

namespace Drupal\c28\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Logger\LoggerChannelFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\Entity\Node;

/**
 * Page JSON request and response controller.
 */
class C28PageJson extends ControllerBase {

  /**
   * The error and warnings logger.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $log;

  /**
   * Entity type manager interface.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Page json construct.
   *
   * @param Drupal\Core\Logger\LoggerChannelFactory $logger
   *   The logger.
   * @param Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entityTypeManager.
   */
  public function __construct(LoggerChannelFactory $logger, EntityTypeManagerInterface $entityTypeManager) {
    $this->log = $logger;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('logger.factory'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * JSON callback request.
   */
  public function jsonRequestData($bundle, $nid) {
    // Retrive siteapikey from site config setting.
    $siteapikey = \Drupal::config('system.site')->get('siteapikey');
    // Checking site API key and requested node id.
    if (!empty($siteapikey) && $siteapikey != 'No API Key yet' && !empty($nid)) {
      // Load node with requested node id.
      $node = Node::load($nid);
      if ($node->getType() == 'page' && $bundle == 'page') {
        // Prepare json object for node.
        $return = [
          'nid' => $nid,
          'title' => $node->title->value,
          'content' => $node->body->value,
        ];
      }
      else {
        $return = [
          'response' => 'Access Denied',
        ];
      }
    }
    else {
      $return = [
        'response' => 'Access Denied',
      ];
    }
    // Return json response.
    return new JsonResponse($return);
  }

}
