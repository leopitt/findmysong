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
 *   client_secret = "xxx",
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
   * {@inheritdoc}
   */
  public function getClientSecret() {
    return '00ff46f2268a409191424e6217c4fde5';
  }

  /**
   * Check that a key is defined when requested. Throw an exception if not.
   *
   * @param string $key
   *   The key to check.
   *
   * @throws \Drupal\oauth2_client\Exception\Oauth2ClientPluginMissingKeyException
   *   Thrown if the key being checked is not defined.
   */
  private function checkKeyDefined($key) {
    if (!isset($this->pluginDefinition[$key])) {
      throw new Oauth2ClientPluginMissingKeyException($key);
    }
  }

}
