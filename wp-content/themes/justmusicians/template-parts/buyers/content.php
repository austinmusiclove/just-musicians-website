<!-- Content -->
<div class="container lg:grid lg:grid-cols-8 gap-24 py-8">


    <!-- Main Content -->
    <div class="grid gap-12 col lg:col-span-5 article-body mb-8 lg:mb-0">


        <!-- Reviews -->
        <?php echo get_template_part('template-parts/buyers/parts/reviews', '', [
            'user_id'      => $args['user_id'],
            'display_name' => $args['display_name'],
        ]); ?>


    </div>


    <!-- Sidebar -->
    <?php echo get_template_part('template-parts/buyers/parts/sidebar', '', []); ?>

</div>
