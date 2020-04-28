<?php

namespace Drupal\lyric_lookup;

use Drupal\Core\Config;
use Drupal\Core\Url;

/**
 * Class LyricLookupService.
 */
class LyricLookupService implements LyricLookupServiceInterface {

  /**
   * Constructs a new LyricLookupService object.
   */
  public function __construct() {

  }

  /**
   * Lookup.
   *
   * @return string
   *   Return Hello string.
   */
  public static function lookup($name) {
    $apiUrl = 'http://api.musixmatch.com/ws/1.1/';
    $client = \Drupal::httpClient();
    $query = strtr('@basetrack.search?apikey=@key&q_lyrics=@name&s_track_rating=@sort&f_lyrics_language=@lang',
      [
        '@base' => $apiUrl,
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
          '#markup' => t('<p>@number tracks found.</p>', ['@number' => number_format($header['available'])]),
        ],
      ];

      if (count($track_list) > 0) {
        return $track_list;
      }

      return NULL;
    }
  }

}
