<?php

namespace Drupal\lyric_lookup;

use Drupal\Component\Utility\Html;

/**
 * Class SpotifyService.
 */
class SpotifyService implements SpotifyServiceInterface {

  /**
   * Spotify API Url.
   *
   * @var string
   */
  private $apiUrl = 'https://api.spotify.com/v1/';

  /**
   * Spotify API access token.
   *
   * @var string
   */
  protected $accessToken = '';

  /**
   * Get Spotify Track ID.
   *
   * @param string $track_name
   *   The track name.
   */
  public function getTrackId($track_name) {
    // Load lyric_lookup config.
    $debug = FALSE;
    if ($config = \Drupal::config('lyric_lookup.config')) {
      $debug = $config->get('debug');
    }

    // Generate cache id.
    $cid = 'spotify:id:' . Html::cleanCssIdentifier(strtolower($track_name));

    // Check whether we have the track in our cache already.
    if ($cache = \Drupal::cache('spotify')->get($cid)) {
      if ($debug) {
        \Drupal::logger('lyric_lookup')->notice('Spotify link found in cache.');
      }

      return $cache->data;
    }
    else {
      // Otherwise, get from the Spotify API.
      if ($debug) {
        \Drupal::logger('lyric_lookup')->notice('Fetching spotify link from Spotify API.');
      }

      // Create an httpClient.
      $spotify_client = \Drupal::httpClient();

      // Get Spotify API access token.
      $this->accessToken = \Drupal::service('oauth2_client.service')->getAccessToken('spotify');

      if ($debug) {
        \Drupal::logger('lyric_lookup')->notice(
          'Spotify API access token received: @token.', ['@token' => $this->accessToken]
        );
      }

      // Generate API query.
      $query = strtr('@basesearch?q=@track&type=@type&limit=@limit',
        [
          '@base' => $this->apiUrl,
          '@track' => urlencode($track_name),
          '@type' => 'track',
          '@limit' => 1,
        ]
      );
      $spotify_response = $spotify_client->get($query, [
        'headers' => [
          'Authorization' => 'Bearer ' . $this->accessToken->getToken(),
          'Accept' => 'application/json',
        ],
      ]);
      $spotify_data = $spotify_response->getBody();
      $spotify_json = json_decode($spotify_data, TRUE);

      // Check we've a result.
      if (isset($spotify_json['tracks']['items'][0]['id'])) {
        // Store in the cache and return.
        \Drupal::cache('spotify')->set($cid, $spotify_json['tracks']['items'][0]['id'], strtotime('3 months'));
        return $spotify_json['tracks']['items'][0]['id'];
      }
    }

    return FALSE;
  }

}
