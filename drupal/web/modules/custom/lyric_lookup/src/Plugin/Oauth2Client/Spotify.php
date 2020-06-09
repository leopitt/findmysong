<?php

namespace Drupal\lyric_lookup\Plugin\Oauth2Client;

use Drupal\oauth2_client\Plugin\Oauth2Client\Oauth2ClientPluginBase;

/**
 * OAuth2 Client to authenticate with Spotify.
 *
 * See https://developer.spotify.com/documentation/general/guides/authorization-guide/
 *
 * @Oauth2Client(
 *   authorization_uri = "https://accounts.spotify.com/authorize",
 *   client_id = "013fc58693644d62a4dd959464daf06a",
 *   client_secret = "placeholder_use_local_settings",
 *   grant_type = "authorization_code",
 *   id = "spotify",
 *   name = @Translation("Spotify"),
 *   resource_owner_uri = "",
 *   response_type = "code",
 *   show_dialog = "false",
 *   token_uri = "https://accounts.spotify.com/api/token",
 * )
 */
class Spotify extends Oauth2ClientPluginBase {

  /**
   * Override base class method to return a key from private settings.
   */
  public function getClientSecret() {
    return \Drupal::config('lyric_lookup.config')->get('spotify_api_secret');
  }

  /**
   * {@inheritdoc}
   */
  private function checkKeyDefined($key) {
    if (!isset($this->pluginDefinition[$key])) {
      throw new Oauth2ClientPluginMissingKeyException($key);
    }
  }

}
