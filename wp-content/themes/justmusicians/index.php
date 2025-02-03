<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package JustMusicians
 */
get_header();
?>

<header class="bg-yellow-light pt-12 md:pt-24 pb-8 md:pb-16 relative overflow-hidden <?php echo $header_padding; ?>">
    <img class="w-32 absolute top-0 right-0 z-10" src="<?php echo get_template_directory_uri() . '/lib/images/other/violator-blog.svg'; ?>" />
    <div class="container grid grid-cols-1 sm:grid-cols-7 gap-x-8 md:gap-x-24 gap-y-10 relative"> 


    <div class="sm:col-span-3 flex flex-col justify-center">
        <div class="w-full">
            <div class="w-full aspect-4/3 shadow-black-offset border-2 border-black relative">
                <img class="h-full w-full object-cover" src="<?php echo get_template_directory_uri(); ?>/lib/images/placeholder/eric-tessmer.jpg" />
            </div>
        </div>
    </div>  
    <div class="sm:col-span-4 flex flex-col gap-y-6 justify-center">
        <div class="text-16 px-2 py-0.5 rounded-full bg-yellow inline-block w-fit font-bold">Nov 11, 2024</div>
        <h1 class="font-bold text-32 md:text-36 lg:text-40">Beyond the Hits: 10 Under-the-Radar Bands You Need to Hear This Month</h1>
        <div class="text-20 uppercase text-brown-dark-1 opacity-50 font-bold">John Filippone</div>
        <a class="font-bold text-36 font-sans text-yellow inline-block hover:text-black" href="#">Read &rarr;</a>
    </div>


    </div>
</header>

<div class="pt-12 pb-0 md:pb-12 container">
    <h3 class="text-28 opacity-60 font-bold mb-16">More articles</h3>

    <div class="grid grid-cols-4 lg:grid-cols-6 xl:grid-cols-7 gap-8 md:gap-20 xl:gap-24">
        <div class="col-span-4 xl:col-span-5 grid md:grid-cols-2 gap-20 xl:gap-x-24 gap-y-8">
        <?php 
            if ( have_posts() ) {
                $count = 0;
                global $wp_query;
                $post_count = $wp_query->post_count;
                while ( have_posts() ) {
                    the_post(); 
                    $count++;
                if ($count == $post_count - 1) {
                    $border = 'border-b border-black/20 pb-8 md:border-0';
                } elseif ($count == $post_count) {
                    $border = '';
                } else {
                    $border = 'border-b border-black/20 pb-8';
                }
        ?>
                 <div class="col <?php echo $border; ?>">
                    <div class="text-16 font-bold mb-3">Nov 11, 2024</div>
                    <h3 class="font-bold text-25 mb-6">
                        <a href="<?php echo esc_url(get_the_permalink()); ?>">
                            <?php echo esc_html(get_the_title()); ?>
                        </a>
                    </h3>
                    <div class="text-18 uppercase text-brown-dark-1 opacity-50 font-bold mb-6">John Filippone</div>
                    <a class="font-bold text-25 font-sans text-yellow inline-block hover:scale-105" href="<?php echo esc_url(get_the_permalink()); ?>">Read &rarr;</a>

                 </div>
        <?php 
                } 
            } 
        ?>

        </div>
        <div class="col-span-4 lg:col-span-2 relative pb-8">
            <div class="sticky top-24">
                <?php echo get_template_part('template-parts/global/form-quote', '', array(
                    'button_color' => 'bg-navy text-white hover:bg-yellow hover:text-black',
                    'responsive' => 'lg:border-none lg:p-0'
                )); ?> 
            </div>
        </div>
    </div>
    
</div>

<?php
un_simple_pagination();

get_footer();
