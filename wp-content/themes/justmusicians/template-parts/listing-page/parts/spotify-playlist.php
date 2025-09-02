
<?php if (!$args['is_preview'] and !empty(get_field('spotify_artist_id'))) { ?>
<iframe width="100%" height="352" frameBorder="0" allowfullscreen=""
    src="https://open.spotify.com/embed/artist/<?php echo get_field('spotify_artist_id'); ?>?utm_source=generator"
    data-testid="embed-iframe"
    style="border-radius:12px"
    allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
    loading="lazy">
</iframe>
<?php } ?>

<?php if ($args['is_preview']) { ?>
<span x-show="pSpotifyArtistId" x-cloak>
    <iframe width="100%" height="352" frameBorder="0" allowfullscreen=""
        x-bind:src="`https://open.spotify.com/embed/artist/${pSpotifyArtistId}?utm_source=generator`"
        data-testid="embed-iframe"
        style="border-radius:12px"
        allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
        loading="lazy">
    </iframe>
</span>
<?php } ?>
