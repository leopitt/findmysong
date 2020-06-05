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
 *   client_id = "5e56e612d84e4cc1ad65618ba09321ab",
 *   client_secret = "2a6b1be636104e32a7abf0e96c957b1b",
 *   grant_type = "authorization_code",
 *   id = "spotify",
 *   name = @Translation("Spotify"),
 *   resource_owner_uri = "",
 *   response_type = "code",
 *   show_dialog = "false",
 *   token_uri = "https://accounts.spotify.com/api/token",
 * )
 */
class Spotify extends Oauth2ClientPluginBase {}
