<?php

namespace Drupal\lyric_lookup;

use Drupal\Component\Utility\Html;
use Drupal\Core\Config;

/**
 * Class LyricLookupService.
 */
class LyricLookupService implements LyricLookupServiceInterface {

  /**
   * Lookup.
   *
   * @return string
   *   Return Hello string.
   */
  public static function lookup($name) {
    // Load config.
    $debug = FALSE;
    if ($config = \Drupal::config('lyric_lookup.config')) {
      $debug = $config->get('debug');
    }

    if ($debug) {
      \Drupal::logger('lyric_lookup')->notice('Calling LyricLookupService::lookup(@name).', ['@name' => $name]);
    }

    // Generate cache id.
    $cid = 'lyric_lookup:name:' . Html::cleanCssIdentifier(strtolower($name));

    // Check for an entry in the cache.
    if ($cache = \Drupal::cache('lyric_lookup')->get($cid)) {
      if ($debug) {
        \Drupal::logger('lyric_lookup')->notice('Fetching the name from the cache.');
      }
      return $cache->data;
    }
    else {
      if ($debug) {
        \Drupal::logger('lyric_lookup')->notice('Fetching the name from the MusixMatch API.');
      }

      // Fetch from the MusixMatch API.
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

      if ($debug) {
        \Drupal::logger('lyric_lookup')->notice('Musixmatch API query: @query.', ['@query' => $query]);
      }

      // Parse the response.
      $response = $musixmatch_client->get($query);
      $data = $response->getBody();
      $json = json_decode($data, TRUE);

      if ($debug) {
        \Drupal::logger('lyric_lookup')->notice('Musixmatch API response: @json.', ['@json' => print_r($json, TRUE)]);
      }

      // Check we have a 200 response code.
      if ($json && $json['message']['header']['status_code'] == 200) {
        // Fetch the list of tracks.
        $track_list = $json['message']['body']['track_list'];

        if (count($track_list) > 0) {
          // Get spotify date for each track.
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

          // Add to the cache and return.
          \Drupal::cache('lyric_lookup')->set($cid, $track_list, strtotime('3 months'));
          return $track_list;
        }
      }
      else {
        \Drupal::logger('lyric_lookup')->error('Musixmatch API returned a @status status.', ['@status' => $json['message']['header']['status_code']]);
      }
    }

    return NULL;
  }

}
