<?php

namespace Drupal\lyric_lookup;

use Drupal\Core\Config;
use Drupal\Core\Url;
use Drupal\lyric_lookup\SpotifyService;

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
    // Call the MusixMatch API
    $musixmatch_api_url = 'http://api.musixmatch.com/ws/1.1/';
    $musixmatch_client = \Drupal::httpClient();
    $query = strtr('@basetrack.search?apikey=@key&q_lyrics=@name&s_track_rating=@sort&f_lyrics_language=@lang',
      [
        '@base' => $musixmatch_api_url,
        '@key' => \Drupal::config('lyric_lookup.config')->get('musixmatch_api_key'),
        '@name' => $name,
        '@sort' => 'DESC',
        '@lang' => 'en',
      ]
    );

    $response = $musixmatch_client->get($query);

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
        // Get Spotify API access token.
        $spotify = new SpotifyService();

        // Loop through track list and add Spotify links.
        if ($spotify) {
          foreach ($track_list as $key => $val) {
            $spotify_id = $spotify->getTrackId($val['track']['track_name']);

            // Add the Spotify track ID to the results.
            if ($spotify_id) {
              $track_list[$key]['track']['spotify_id'] = $spotify_id;
            }
          }
        }

        return $track_list;
      }

      return NULL;
    }
  }

}
