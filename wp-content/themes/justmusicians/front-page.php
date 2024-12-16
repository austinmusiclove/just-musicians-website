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
 * @package BarnRaiser
 */
get_header();
?>

<div class="container grid grid-cols-12 gap-12">
    <div class="col-span-3 border-r border-black/20 pr-8">
        <div class="sticky top-24 pt-20 pb-8">

        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-sun-motter text-25">Filter</h2>
                <button class="underline opacity-40 hover:opacity-100 inline-block text-14">clear all</button>
            </div>
            <div class="text-14 opacity-60">
                Producer | Gospel Choir | Solo/Duo | Acoustic
            </div>
        </div>

            <?php                 
                echo get_template_part('template-parts/filters/elements/tags', '', array(
                    'title' => 'Genre',
                    'tag_1' => 'folk',
                    'tag_2' => 'indie rock',
                    'tag_3' => 'latin',
                    'tag_4' => 'psychedelic',
                    'see_all' => false
                ));  
                echo get_template_part('template-parts/filters/elements/tags', '', array(
                    'title' => 'Type',
                    'tag_1' => 'band',
                    'tag_2' => 'DJ',
                    'tag_3' => 'musician',
                    'tag_4' => 'solo/duo',
                    'see_all' => false
                )); 
                echo get_template_part('template-parts/filters/elements/tags', '', array(
                    'title' => 'Category',
                    'tag_1' => 'acoustic',
                    'tag_2' => 'cover band',
                    'tag_3' => 'producer',
                    'tag_4' => 'wedding band',
                    'see_all' => true
                )); 
                echo get_template_part('template-parts/filters/location', '', array()); 
            ?>
            
        </div>
    </div>
    <div class="col-span-6 py-4">
        <div class="text-16 flex items-center gap-2 py-2">
                <img id="info-sort" class="opacity-40 h-4 cursor-pointer hover:opacity-100" src="<?php echo get_template_directory_uri() . '/lib/images/icons/circle-info.svg'; ?>" />
                <div class="flex items-center gap-1.5">
                Sort:
                <span class="font-bold flex items-center">
                    Default
                    <img class="ml-1.5" src="<?php echo get_template_directory_uri() . '/lib/images/icons/caret-down.svg'; ?>" />
                </span>
            </div>
        </div>
        <?php 
            echo get_template_part('template-parts/search/profile', '', array(
                'name' => 'Chastity',
                'location' => 'Whitby, Ontario, Canada',
                'description' => 'An indie rock band led by Brandon Williams​',
                'genre_1' => 'indie rock',
                'genre_2' => 'country',
                'slug' => 'chastity.jpg'
            ));  
            get_template_part('template-parts/search/profile', '', array(
                'name' => 'Y La Bamba',
                'description' => 'Latin band with roots in Mexican folk and indie​',
                'location' => 'Portland, Oregon, USA',
                'genre_1' => 'latin',
                'genre_2' => 'folk',
                'slug' => 'ylabamba.jpeg'
            )); 
            get_template_part('template-parts/search/profile', '', array(
                'name' => 'Quickly, Quickly',
                'description' => 'Producer of soulful and dreamy indie music​​',
                'location' => 'Denver, Colorado, USA',
                'genre_1' => 'indie rock',
                'genre_2' => 'shoegaze',
                'slug' => 'quickly-quickly.jpg'
            )); 
            get_template_part('template-parts/search/profile', '', array(
                'name' => 'Kiltro',
                'description' => 'Influenced by Chilean music and psychedelic rock​​',
                'location' => 'Austin, Texas, USA',
                'genre_1' => 'psychedelic rock',
                'genre_2' => 'folk',
                'slug' => 'kiltro.jpeg'
            )); 
            get_template_part('template-parts/search/profile', '', array(
                'name' => 'Calliope Musicals',
                'description' => 'The familial warmth of folk and the elation of dance pop​​',
                'location' => 'Portland, Oregon, USA',
                'genre_1' => 'dance pop',
                'genre_2' => 'folk',
                'slug' => 'calliope-musicals.jpg'
            ));  
            get_template_part('template-parts/search/profile', '', array(
                'name' => 'Riders Against the Storm',
                'description' => 'Husband-wife duo that blends hip-hop, soul, and funk and are regulars in Austin’s music scene.​​',
                'location' => 'Austin, Texas, USA',
                'genre_1' => 'hip hop',
                'genre_2' => 'soul',
                'slug' => 'riders-against-the-storm.jpg'
            ));   
            get_template_part('template-parts/search/profile', '', array(
                'name' => 'Eric Tessmer',
                'description' => 'A blues-rock guitarist known for his powerful performances​​',
                'location' => 'Austin, Texas, USA',
                'genre_1' => 'blues',
                'genre_2' => 'rock',
                'slug' => 'eric-tessmer.jpg'
            ));     
        ?>
    </div>
    <div class="col-span-3 relative py-8">
        <div class="sticky top-24">
            <?php echo get_template_part('template-parts/global/form-quote', '', array()); ?> 
        </div>
    </div>
</div>



<?php
get_footer();
