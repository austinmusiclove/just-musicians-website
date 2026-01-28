
<!-- Content -->
<div class="container lg:grid lg:grid-cols-8 gap-24 py-8">


    <!-- Main Content -->
    <div class="grid gap-12 col lg:col-span-5 article-body mb-8 lg:mb-0">


        <!-- Description -->
        <?php echo get_template_part('template-parts/venues/parts/description', '', []); ?>

        <!-- Genres -->
        <!-- Tags/Amenities for performers (sound engineer, green room, etc.) -->

        <!-- Map -->
        <?php echo get_template_part('template-parts/venues/parts/map', '', []); ?>

        <!-- Reviews -->
        <?php echo get_template_part('template-parts/venues/parts/reviews', '', []); ?>

    </div>


    <!-- Sidebar -->
    <?php echo get_template_part('template-parts/venues/parts/sidebar', '', []); ?>

</div>
