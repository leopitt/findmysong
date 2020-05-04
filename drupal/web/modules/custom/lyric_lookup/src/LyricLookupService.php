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
        $access_token = \Drupal::service('oauth2_client.service')->getAccessToken('spotify');
        $token = $access_token->getToken();

        // Loop through track list and add Spotify links.
        if ($token) {
          foreach ($track_list as $track) {
            // Access Spotify API here.
            $spotify_api_url = 'https://api.spotify.com/v1/';
            $spotify_client = \Drupal::httpClient();
            $query = strtr('@basesearch?q=@track&type=@type',
              [
                '@base' => $spotify_api_url,
                '@track' => urlencode($track['track']['track_name']),
                '@type' => 'track',
              ]
            );
            $spotify_response = $spotify_client->get($query, ['headers' => [
              'Authorization' => 'Bearer ' . $token,
              'Accept' => 'application/json',
            ]]);
            $spotify_data = $spotify_response->getBody();
            $spotify_json = json_decode($spotify_data, TRUE);
            // var_dump($spotify_json);
          }
        }

        return $track_list;
      }

      return NULL;
    }
  }

}
