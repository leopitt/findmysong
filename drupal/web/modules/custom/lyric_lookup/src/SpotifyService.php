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
    // Generate cache id.
    $cid = 'spotify:id:' . Html::cleanCssIdentifier(strtolower($track_name));

    // Check whether we have the track in our cache already.
    if ($cache = \Drupal::cache('spotify')->get($cid)) {
      return $cache->data;
    }
    else {
      // Otherwise, get from the Spotify API.
      $spotify_client = \Drupal::httpClient();
      // Get Spotify API access token.
      $this->accessToken = \Drupal::service('oauth2_client.service')->getAccessToken('spotify');

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
