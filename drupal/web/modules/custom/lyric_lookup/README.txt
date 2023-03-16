INTRODUCTION
------------

Integrates with the Musixmatch API to lookup lyrics.

CONFIGURATION
-------------

This has settings for a Musixmatch API key, Spotify API secret and debug
(boolean). Don't add them to exported config. Place it in your
settings.local.php. E.g.

  $config['lyric_lookup.config']['musixmatch_api_key'] = 'MusixmatchAPIKey';
  $config['lyric_lookup.config']['spotify_api_secret'] = 'SpotifyAPIKey';
  $config['lyric_lookup.config']['debug'] = '1';
