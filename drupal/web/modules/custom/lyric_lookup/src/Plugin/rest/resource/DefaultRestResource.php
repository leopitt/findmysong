<?php

namespace Drupal\lyric_lookup\Plugin\rest\resource;

use Drupal\lyric_lookup\LyricLookupService;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "default_rest_resource",
 *   label = @Translation("Lyric lookup REST resource"),
 *   uri_paths = {
 *     "canonical" = "/lyric-lookup/{lyric}"
 *   }
 * )
 */
class DefaultRestResource extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->logger = $container->get('logger.factory')->get('lyric_lookup');
    $instance->currentUser = $container->get('current_user');
    return $instance;
  }

    /**
     * Responds to GET requests.
     *
     * @param string $payload
     *
     * @return \Drupal\rest\ResourceResponse
     *   The HTTP response object.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *   Throws exception expected.
     */
    public function get($lyric) {
      $results = LyricLookupService::lookup($lyric);
      return new ResourceResponse($results);
    }

}
