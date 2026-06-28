<?php
/**
 * Template for musician application form
 *
 * @package JustMusicians
 */

$application_id = get_query_var('application-id');
$title = get_post_meta($application_id, 'title', true);
$description = get_post_meta($application_id, 'description', true);

get_header();
?>

<div id="page" class="flex flex-col grow">

    <div id="content" class="grow flex flex-col relative">
        <div class="container pt-20 md:pt-32 pb-6 md:pb-12">

            <?php if (!$application_id || !$title) { ?>

                <p class="text-16 text-black/60">Application not found.</p>

            <?php } else { ?>

                <h1 class="font-bold text-25 mb-4"><?php echo esc_html($title); ?></h1>

                <?php if ($description) { ?>
                    <div class="mb-8 text-16 text-black/80"><?php echo wpautop(esc_html($description)); ?></div>
                <?php } ?>

                <form>
                    <div class="mb-4">
                        <label for="musician-name" class="block font-bold text-16 mb-2">Musician / Band Name</label>
                        <input type="text" id="musician-name" name="musician_name" placeholder="Your name or band name" />
                    </div>

                    <button type="submit" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2">Submit Application</button>
                </form>

            <?php } ?>

        </div>
    </div>
</div>

<?php
get_footer();
