<?php

namespace Drupal\lyric_lookup\Plugin\Oauth2Client;

use Drupal\oauth2_client\Plugin\Oauth2Client\Oauth2ClientPluginBase;
use League\OAuth2\Client\Token\AccessToken;

/**
 * OAuth2 Client to authenticate with Spotify.
 *
 * See https://developer.spotify.com/documentation/general/guides/authorization-guide/
 *
 * The redirect URL is {domain}/oauth2-client/spotify/code
 * E.g. "http://findmysongadmin.docksal/oauth2-client/spotify/code".
 *
 * @Oauth2Client(
 *   authorization_uri = "https://accounts.spotify.com/authorize",
 *   grant_type = "authorization_code",
 *   id = "spotify",
 *   name = @Translation("Spotify"),
 *   resource_owner_uri = "",
 *   response_type = "code",
 *   show_dialog = "false",
 *   token_uri = "https://accounts.spotify.com/api/token",
 *   success_message = TRUE
 * )
 */
class Spotify extends Oauth2ClientPluginBase {

  /**
   * Retrieves the client_id of the OAuth2 server.
   *
   * @return string
   *   The client_id of the OAuth2 server.
   */
  public function getClientId() {
    return \Drupal::config('lyric_lookup.config')->get('spotify_client_id');
  }

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

  /**
   * {@inheritdoc}
   */
  public function storeAccessToken(AccessToken $accessToken) {
    $this->state->set('oauth2_client_access_token-' . $this->getId(), $accessToken);
    if ($this->displaySuccessMessage()) {
      $this->messenger->addStatus(
        $this->t('OAuth token stored.')
      );
    }
  }

  /**
   * {@inheritdoc}
   */
  public function retrieveAccessToken() {
    return $this->state->get('oauth2_client_access_token-' . $this->getId());
  }

  /**
   * {@inheritdoc}
   */
  public function clearAccessToken() {
    $this->state->delete('oauth2_client_access_token-' . $this->getId());
  }

}
