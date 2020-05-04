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
 *   client_id = "013fc58693644d62a4dd959464daf06a",
 *   client_secret = "00ff46f2268a409191424e6217c4fde5",
 *   authorization_uri = "https://accounts.spotify.com/authorize",
 *   token_uri = "https://accounts.spotify.com/api/token",
 *   resource_owner_uri = "",
 *   scopes = "",
 *   scope_separator = " ",
 * )
 */
class Spotify extends Oauth2ClientPluginBase {}
