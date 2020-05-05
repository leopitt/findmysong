<?php

namespace Drupal\lyric_lookup\Plugin\Oauth2Client;

use Drupal\oauth2_client\Plugin\Oauth2Client\Oauth2ClientPluginBase;

/**
 * OAuth2 Client to authenticate with Spotify.
 *
 * See https://developer.spotify.com/documentation/general/guides/authorization-guide/
 *
 * @Oauth2Client(
 *   id = "spotify",
 *   name = @Translation("Spotify"),
 *   grant_type = "client_credentials",
 *   client_id = "5e56e612d84e4cc1ad65618ba09321ab",
 *   client_secret = "2a6b1be636104e32a7abf0e96c957b1b",
 *   authorization_uri = "https://accounts.spotify.com/authorize",
 *   token_uri = "https://accounts.spotify.com/api/token",
 *   resource_owner_uri = "",
 *   scopes = "",
 *   scope_separator = " ",
 * )
 */
class Spotify extends Oauth2ClientPluginBase {}
