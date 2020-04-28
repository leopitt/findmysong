<?php

namespace Drupal\lyric_lookup\Controller;

use Drupal\Core\Config;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\lyric_lookup\LyricLookupService;

/**
 * Class DefaultController.
 */
class DefaultController extends ControllerBase {

  /**
   * API URL.
   *
   * See http://api.musixmatch.com/.
   *
   * @var string
   *   public api key.
   */
  private $apiUrl = 'http://api.musixmatch.com/ws/1.1/';

  /**
   * Returns a page title.
   */
  public function getTitle($name) {
    return ucfirst($name) . '\'s songs';
  }

  /**
   * Lookup.
   *
   * @return string
   *   Return Hello string.
   */
  public function lookup($name) {
    $track_list = LyricLookupService::lookup($name);

    if ($track_list && count($track_list) > 0) {
      $header = ['Title', 'Artist'];
      $rows = [];

      foreach ($track_list as $track_list_item) {
        $track = $track_list_item['track'];
        $track_id = $track['track_id'];
        $track_name = $track['track_name'];
        $track_artist = $track['artist_name'];

        $rows[] = [$track_name, $track_artist];

        $output['tracks'][] = [
          '#type' => 'markup',
          '#markup' => '<pre> ' . print_r($track, TRUE) . '</pre>',
        ];
      }

      $output['tracks'] = [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $rows,
      ];
    }

    $search_url = Url::fromRoute('lyric_lookup.default_form')->toString();

    $output['actions'] = [
      '#type' => 'markup',
      '#markup' => '<p><a href="' . $search_url . '">Search again</a></p>',
    ];

    return $output;
  }

}
