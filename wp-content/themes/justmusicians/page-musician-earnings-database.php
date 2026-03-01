<?php
/**
 * The template for displaying the musician earnings database page
 *
 * @package JustMusicians
 */

get_header();

$current_user_id = get_current_user_id();
$has_comp_report = false;
if ($current_user_id) {
    $has_comp_report = has_comp_report($current_user_id);
}

?>
<header class="bg-yellow-light pt-12 md:pt-24 pb-8 md:pb-16 relative overflow-hidden">
    <div class="container flex flex-col gap-16 grid grid-cols-1 sm:grid-cols-7">
        <h1 class="font-bold text-32 md:text-36 lg:text-40 sm:col-span-5"><?php the_title(); ?></h1>
        <p class="text-18 sm:col-span-5">The Musician Earnings Database is a community project where musicians anonymously share how much they are earning from live music gigs. In return, contributors benefit from seeing data about how much venues are paying musicians.</p>
        <a class="text-18 sm:col-span-5" href="<?php echo site_url('/compensation-report-form'); ?>">
            <button type="button" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-16 px-5 py-3">Contribute</button>
        </a>
    </div>
</header>

<div class="container gap-24 py-8 min-h-[500px]">
    <div class="col article-body mb-8 lg:mb-0">
        <?php if (!$has_comp_report) :
            echo get_template_part('template-parts/musician-earnings-database/access-denied', '', []);
        else :

            $comp_reports_by_venue = get_comp_report_data_by_venue();
            usort($comp_reports_by_venue, function($a, $b) {
                return $b['total_reports'] - $a['total_reports'];
            }); ?>

            <!-- Venue Table -->
            <?php if (empty($comp_reports_by_venue)) : ?>
                <p class="text-18">No earnings data available yet. Be the first to contribute!</p>
            <?php else : ?>
                <?php echo get_template_part('template-parts/musician-earnings-database/venue-table/venue-table', '', [ 'venues' => $comp_reports_by_venue ] ); ?>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</div>


<?php
get_footer();
