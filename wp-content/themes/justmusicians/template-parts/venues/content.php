
<!-- Content -->
<div class="container lg:grid lg:grid-cols-8 gap-24 py-8">


    <!-- Main Content -->
    <div class="grid gap-12 col lg:col-span-5 article-body mb-8 lg:mb-0">


        <!-- Description -->
        <?php echo get_template_part('template-parts/venues/parts/description', '', []); ?>

        <!-- Genres -->
        <!-- Tags -->
        <!-- Musician Compensation -->
            <!-- Num comp reports -->
            <!-- Average total comp -->
            <!-- Average total comp variance -->
            <!-- Average total comp per performer -->
            <!-- Average total comp per performer per hour -->
            <!-- Payment type -->
            <!-- Payment speed -->
            <!-- Submit a Musician Compensation Report -->

        <!-- Map -->
        <?php echo get_template_part('template-parts/venues/parts/map', '', []); ?>

        <!-- Reviews -->
        <?php echo get_template_part('template-parts/venues/parts/reviews', '', []); ?>

    </div>


    <!-- Sidebar -->
    <?php echo get_template_part('template-parts/venues/parts/sidebar', '', []); ?>

</div>
