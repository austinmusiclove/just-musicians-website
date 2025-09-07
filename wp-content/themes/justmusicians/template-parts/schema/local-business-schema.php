<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "@id": "<?php echo $args['url']; ?>",
    "name": "<?php echo $args['name']; ?>",
    "url": "<?php echo $args['url']; ?>",
    "image": "<?php echo $args['image']; ?>",
    "description": "<?php echo $args['description']; ?>",
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "<?php echo $args['city']; ?>",
        "addressRegion": "<?php echo get_state_code($args['state']); ?>",
        "addressCountry": "US"
    },
    "areaServed": {
        "@type": "City",
        "name": "<?php echo $args['city']; ?>",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "<?php echo $args['city']; ?>",
            "addressRegion": "<?php echo get_state_code($args['state']); ?>",
            "addressCountry": "US"
        }
    },
    <?php if (!empty($args['phone'])) { ?>"telephone": "<?php echo $args['phone']; ?>",<?php } ?>
    <?php if (!empty($args['website'])) { ?>"sameAs": "<?php echo $args['website']; ?>",<?php } ?>
    "subjectOf": {
        "@type": "MusicGroup",
        "name": "<?php echo $args['name']; ?>",
        <?php if (!empty($args['website'])) { ?>"sameAs": "<?php echo $args['website']; ?>",<?php } ?>
        "description": "<?php echo $args['description']; ?>",
        <?php if (!empty($args['genres'])) { ?>"genre": "<?php echo html_entity_decode(implode(', ', $args['genres'])); ?>",<?php } ?>
        "url": "<?php echo $args['url']; ?>",
        "image": "<?php echo $args['image']; ?>"
    }
}
</script>

