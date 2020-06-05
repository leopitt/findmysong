<?php

namespace Drupal\lyric_lookup;

/**
 * Class SpotifyService.
 */
class SpotifyService implements SpotifyServiceInterface {
  private $apiUrl = 'https://api.spotify.com/v1/';
  protected $accessToken = '';

  /**
   * Constructs a new SpotifyService object.
   */
  public function __construct() {
    // Get Spotify API access token.
    $this->accessToken = \Drupal::service('oauth2_client.service')->getAccessToken('spotify');
  }

  /**
   * Get Spotify Track ID.
   *
   * @param string $track_name
   *   The track name.
   */
  public function getTrackId($track_name) {
    // Access Spotify API here.
    $spotify_client = \Drupal::httpClient();
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

    // Add the Spotify track ID to the results.
    if (isset($spotify_json['tracks']['items'][0]['id'])) {
      return $spotify_json['tracks']['items'][0]['id'];
    }

    return FALSE;
  }

}
