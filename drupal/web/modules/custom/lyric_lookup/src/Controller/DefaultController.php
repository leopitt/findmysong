<?php

namespace Drupal\lyric_lookup\Controller;

use Drupal\Core\Config;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

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
    $client = \Drupal::httpClient();
    $query = strtr('@basetrack.search?apikey=@key&q_lyrics=@name&s_track_rating=@sort&f_lyrics_language=@lang',
      [
        '@base' => $this->apiUrl,
        '@key' => \Drupal::config('lyric_lookup.config')->get('musixmatch_api_key'),
        '@name' => $name,
        '@sort' => 'DESC',
        '@lang' => 'en',
      ]
    );

    $response = $client->get($query);

    $data = $response->getBody();
    $json = json_decode($data, TRUE);

    if ($json && $json['message']['header']['status_code'] == 200) {

      $header = $json['message']['header'];
      $track_list = $json['message']['body']['track_list'];

      $output = [
        'summary' => [
          '#type' => 'markup',
          '#markup' => $this->t('<p>@number tracks found.</p>', ['@number' => number_format($header['available'])]),
        ],
      ];

      if (count($track_list) > 0) {
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

}
