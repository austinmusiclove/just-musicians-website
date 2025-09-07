<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "MusicGroup",
    "name": "<?php echo $args['name']; ?>",
    "@id": "<?php echo $args['url']; ?>",
    <?php if (!empty($args['website'])) { ?>"sameAs": "<?php echo $args['website']; ?>",<?php } ?>
    "description": "<?php echo $args['description']; ?>",
    <?php if (!empty($args['genres'])) { ?>"genre": "<?php echo html_entity_decode(implode(', ', $args['genres'])); ?>",<?php } ?>
    "url": "<?php echo $args['url']; ?>",
    "image": "<?php echo $args['image']; ?>",
    "areaServed": {
        "@type": "City",
        "name": "<?php echo $args['city']; ?>",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "<?php echo $args['city']; ?>",
            "addressRegion": "<?php echo get_state_code($args['state']); ?>",
            "addressCountry": "US"
        }
    }
}
</script>
