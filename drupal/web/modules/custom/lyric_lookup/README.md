# Lyric Lookup

Integrates with the Musixmatch API to lookup lyrics.

This has settings for a Musixmatch API key, Spotify API secret and debug
(boolean). Don't add them to exported config. Place it in your
settings.local.php. E.g.

```php
$config['lyric_lookup.config']['musixmatch_api_key'] = '6b7af801772f2e39a27a6cfca8246777';
$config['lyric_lookup.config']['spotify_api_secret'] = '348e5130268550023cb4bc1603cfec7f';
$config['lyric_lookup.config']['spotify_client_id'] = 'a25194a75273dcf459457919dae94a57';
$config['lyric_lookup.config']['debug'] = '1';
```
