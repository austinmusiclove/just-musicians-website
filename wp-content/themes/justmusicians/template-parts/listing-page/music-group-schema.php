<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "MusicGroup",
    "name": "<?php echo $args['name']; ?>",
    <?php if (!empty($args['website'])) { ?>"sameAs": "<?php echo $args['website']; ?>",<?php } ?>
    "description": "<?php echo $args['description']; ?>",
    "genre": "<?php echo $args['genres']; ?>",
    "url": "<?php echo $args['url']; ?>",
    "image": "<?php echo $args['image']; ?>",
    "areaServed": {
        "@type": "City",
        "name": "<?php echo $args['city']; ?>",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "<?php echo $args['city']; ?>",
            "addressRegion": "<?php echo $args['state']; ?>",
            "addressCountry": "US"
        }
    }
}
</script>
