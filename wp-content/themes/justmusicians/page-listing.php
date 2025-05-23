<?php
/**
 * The template for displaying the listing form
 *
 * @package JustMusicians
 */

get_header();

$availability_html = '<div class="bg-navy text-white rounded-full font-bold py-1 px-3 uppercase text-14 w-fit">Available</div>';

?>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {

// Sidebar toggle
  document.querySelectorAll('[data-toggle]').forEach(function (toggleBtn) {
    toggleBtn.addEventListener('click', function () {
      const targetSelector = this.getAttribute('data-toggle');
      const target = document.querySelector(`[data-toggle-target="${targetSelector}"]`);
      
      if (target) {
        target.classList.toggle('hidden');
      }

      const img = this.querySelector('img');
      if (img) {
        img.classList.toggle('rotate-180');
      }
    });
  });
  // Tabs
  document.querySelectorAll('[data-tab]').forEach(function (tabBtn) {
    tabBtn.addEventListener('click', function () {
        const targetSelector = this.getAttribute('data-tab');
        const target = document.querySelector(`[data-tab-target="${targetSelector}"]`);

        // Hide all tab contents
        document.querySelectorAll('[data-tab-target]').forEach(function (tab) {
        tab.classList.add('hidden');
        });

        // Show the selected tab content
        if (target) {
        target.classList.remove('hidden');
        }

        // Remove 'active' class from all tab buttons
        document.querySelectorAll('[data-tab]').forEach(function (btn) {
        btn.classList.remove('active');
        });

        // Add 'active' class to the clicked tab button
        this.classList.add('active');
    });
    });

});
</script>

<section class="my-4 lg:my-16">

    <div class="container grid lg:grid-cols-2">
        <div class="bg-yellow w-full aspect-4/3 shadow-black-offset border-4 border-black relative">
            <img class="w-full h-full object-cover" src="<?php echo get_template_directory_uri() . '/lib/images/placeholder/indoor-creature.jpg' ;?>">
            <div class="block lg:hidden absolute top-2 right-2 sm:top-4 sm:right-4">
                <?php echo $availability_html; ?>
            </div>
        </div>

        <!-- Content -->
        <div class="flex flex-col gap-12 lg:px-16 py-4 lg:py-10 items-end">
            <div class="hidden lg:block">
                <?php echo $availability_html; ?>
            </div>
            <div class="flex flex-col gap-5 w-full">
                <div class="flex items-center gap-2">
                    <h1 class="text-32 font-bold">Indoor Creature</h1>
                    <img class="h-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/verified.svg'; ?>" />
                </div>
                <p class="text-18">Indoor Creature’s music can make your mom cry. It’s happened, and they’re flattered but would also like to apologize. </p>
                <div class="flex gap-2 items-center">
                    <img class="h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
                    <span>Austin, TX</span>
                </div>
                <div class="flex items-center gap-1">
                    <?php $tag_classes = 'bg-yellow-light cursor-pointer hover:bg-yellow px-2 py-0.5 rounded-full font-bold text-12'; ?>
                    <span class="<?php echo $tag_classes;?>">pop</span>
                    <span class="<?php echo $tag_classes;?>">rock</span>
                    <span class="<?php echo $tag_classes;?>">soul</span>
                </div>
            </div>
        </div>

    </div>

</section>

<section class="container flex flex-col-reverse lg:grid grid-cols-8 gap-8 xl:gap-24 mb-20">
    <div class="col-span-5 flex flex-col gap-8 items-start">
        <div id="biography">
            <h2 class="text-25 font-bold mb-5">Biography</h2>
            <p class="mb-4">Formed in 2015, Indoor Creature has grown from a duo to a pack of six, and together the Austin-based band has played over 100 shows and two national tours. Their distinctly dreamy, jazz-inspired sound artfully contrasts lyrics that tackle topics most pop songs don’t dare to wrestle with. They explore feelings associated with growing up and up, respond to current events, and delve into their dreams, all things you’d do with a good friend while lying on the floor, staring upward. In this room, the floor and the ceiling are Indoor Creature, grounding you to reality while simultaneously inviting you to think of what could be.</p>
            <p>Indoor Creature is currently working on their third album “Living in Darkness” which is set to be released next year. In the meantime, you can find them around the city, online, and newly in trading card form.</p>
        </div>
        <div id="venues"> <!-- Start venues -->
            <h2 class="text-22 font-bold mb-5">Venues played</h2>
            <div class="flex items-center gap-2 flex-wrap">
                <?php $venue_classes = 'bg-yellow-light p-2 rounded text-16 flex flex-col items-start gap-0.5'; ?>
                <div class="<?php echo $venue_classes;?>">
                   <span class="font-bold">Emo's Austin</span>
                   <span>2015 E Riverside Dr<br/>Austin, TX 78741</span>
                </div>
                <div class="<?php echo $venue_classes;?>">
                   <span class="font-bold">Skylark Lounge</span>
                   <span>2039 Airport Blvd<br />Austin, TX 78722</span>
                </div>
            </div>  
        </div> <!-- End venues -->
        <!-- Start media -->
        <div class="w-full">
            <h2 class="text-25 font-bold mb-5">Media</h2>
            <div class="flex items-start gap-4 media-tabs mb-2.5">
                <?php $button_class = 'text-14 sm:text-16 tab-heading pb-1 cursor-pointer'; ?>
                <div data-tab="images" class="<?php echo $button_class; ?> active">Images</div>
                <div data-tab="videos" class="<?php echo $button_class; ?>">Videos</div>
                <div data-tab="stage-plots" class="<?php echo $button_class; ?>">Stage Plots</div>
            </div>
            <!-- Image -->
            <div data-tab-target="images" class="bg-black aspect-video flex items-center justify-center relative"> 
               <img class="h-full z-0" src="<?php echo get_template_directory_uri() . '/lib/images/placeholder/indoor-creature-2.jpg'; ?>" />
               <!-- Left Arrow -->
               <div class="absolute top-1/2 transform -translate-y-1/2 left-4 transition-all duration-100 ease-in-out"
                    x-transition:enter-start="-translate-x-full opacity-0"
                    x-transition:enter-end="translate-x-0 opacity-100"
                    x-transition:leave-start="translate-x-0 opacity-100"
                    x-transition:leave-end="-translate-x-full opacity-0" >
                    <img class="rotate-180" src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" />
                    </span>
                </div>
                <!-- Right Arrow -->
                <div class="absolute top-1/2 transform -translate-y-1/2 right-4 transition-all duration-100 ease-in-out"
                    x-transition:enter-start="translate-x-full opacity-0"
                    x-transition:enter-end="translate-x-0 opacity-100"
                    x-transition:leave-start="translate-x-0 opacity-100"
                    x-transition:leave-end="translate-x-full opacity-0" >
                    <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" />
                </div>
                <!-- Gallery Count -->
                 <div class="bg-white/90 py-0.5 px-2 rounded-sm absolute top-2 right-2 text-12">1/6</div>
            </div>
            <!-- Video -->
            <div data-tab-target="videos" class="bg-black aspect-video flex items-center justify-center relative hidden"> 
                <iframe width="100%" height="100%" src="https://www.youtube.com/embed/S3VOtKRNybg?si=wNTtAB3c-ZpUl0NT" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
               <!-- Left Arrow -->
               <div class="absolute top-1/2 transform -translate-y-1/2 left-4 transition-all duration-100 ease-in-out"
                    x-transition:enter-start="-translate-x-full opacity-0"
                    x-transition:enter-end="translate-x-0 opacity-100"
                    x-transition:leave-start="translate-x-0 opacity-100"
                    x-transition:leave-end="-translate-x-full opacity-0" >
                    <img class="rotate-180" src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" />
                    </span>
                </div>
                <!-- Right Arrow -->
                <div class="absolute top-1/2 transform -translate-y-1/2 right-4 transition-all duration-100 ease-in-out"
                    x-transition:enter-start="translate-x-full opacity-0"
                    x-transition:enter-end="translate-x-0 opacity-100"
                    x-transition:leave-start="translate-x-0 opacity-100"
                    x-transition:leave-end="translate-x-full opacity-0" >
                    <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" />
                </div>
            </div>
            <!-- Stage Plots -->
            <div data-tab-target="stage-plots" class="flex flex-col gap-1 hidden"> 
                <div class="border border-black/20 overflow-hidden rounded-sm aspect-video flex items-center justify-center relative">
                    <img class="h-full z-0" src="<?php echo get_template_directory_uri() . '/lib/images/placeholder/stage-plot.jpg'; ?>" />
                    <!-- Left Arrow -->
                    <div class="absolute top-1/2 transform -translate-y-1/2 left-4 transition-all duration-100 ease-in-out"
                            x-transition:enter-start="-translate-x-full opacity-0"
                            x-transition:enter-end="translate-x-0 opacity-100"
                            x-transition:leave-start="translate-x-0 opacity-100"
                            x-transition:leave-end="-translate-x-full opacity-0" >
                            <img class="rotate-180" src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow-inverted.svg'; ?>" />
                            </span>
                        </div>
                        <!-- Right Arrow -->
                        <div class="absolute top-1/2 transform -translate-y-1/2 right-4 transition-all duration-100 ease-in-out"
                            x-transition:enter-start="translate-x-full opacity-0"
                            x-transition:enter-end="translate-x-0 opacity-100"
                            x-transition:leave-start="translate-x-0 opacity-100"
                            x-transition:leave-end="translate-x-full opacity-0" >
                            <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow-inverted.svg'; ?>" />
                        </div>
                        <!-- Gallery Count -->
                        <div class="bg-white/90 py-0.5 px-2 rounded-sm absolute top-2 right-2 text-12">1/6</div>
                 </div>
                 <div class="text-14">Stage plot image  1</div>
            </div>
        </div>
        <div class="w-full"> <!-- Start calendar -->
            <h2 class="text-25 font-bold mb-5">Calendar</h2>
            <div class="border border-black/40 rounded w-full"> 
                <div class="flex justify-between items-center border-b border-black/40 pl-4 pr-2">
                    <div class="pt-3 sm:pt-4 flex gap-4 sm:gap-6 items-start">
                        <?php 
                            $button_class = 'calendar-tab pb-2 flex items-center gap-2 text-14 sm:text-16'; 
                            $dot_class = 'h-2 w-2 sm:h-2.5 sm:w-2.5 rounded-full mx-1 md:mx-1.5';
                        ?>
                        <button class="<?php echo $button_class; ?> active">Show all</button>
                        <button class="<?php echo $button_class; ?>">
                            Available
                            <div class="<?php echo $dot_class; ?>" style="background-color: #F4E5CB"></div>
                        </button>
                        <button class="<?php echo $button_class; ?>">
                            Gig
                            <div class="<?php echo $dot_class; ?>" style="background-color: #D29429"></div>
                        </button>
                        <button class="<?php echo $button_class; ?>">
                            Unavailable
                            <div class="<?php echo $dot_class; ?>" style="background-color: #CCCCCC"></div>
                        </button>
                    </div>
                    <button class="hidden sm:block px-2.5 py-2 border border-black/20 rounded font-bold text-14">Today</button>
                </div>
                <div class="flex flex-row p-8 gap-8">
                    <?php echo get_template_part('template-parts/global/calendar', '', array(
                        'month' => 'April',
                        'year' => '2025',
                        'order' => 1,
                        'responsive' => '',
                        'event_day' => 20
                    )); ?>
                    <?php echo get_template_part('template-parts/global/calendar', '', array(
                        'month' => 'May',
                        'year' => '2025',
                        'order' => 2,
                        'responsive' => 'hidden sm:block',
                        'event_day' => null
                    )); ?>
                </div>
            </div>
        </div> <!-- End calendar -->
    </div>

    <div class="col-span-3">
        <div class="sticky top-20 flex flex-col gap-8">
            <button x-on:click="showLoginModal = true;" type="button" data-trigger="quote" class="bg-yellow w-full shadow-black-offset border-2 border-black font-sun-motter text-18 px-2 py-2">Request a Quote</button>
            <div class="flex flex-col gap-4">

            <div class="sidebar-module border border-black/40 rounded overflow-hidden">
                    <h3 class="bg-yellow-light-50 font-bold py-2 px-3">Contact Information</h3>
                    <div class="p-4 flex flex-col gap-4">
                        <?php $taxonomy_tag_classes = 'bg-yellow-light cursor-pointer hover:bg-yellow px-2 py-0.5 rounded-full text-12'; ?>
                        <div class="grid grid-cols-[auto_auto] gap-x-12 gap-y-4 w-fit"> <!-- Start contact info -->
                            <div>
                                <div class="flex items-center gap-1">
                                    <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/phone.svg'; ?>" />
                                    <h4 class="text-16 font-semibold">Phone</h4>
                                </div>
                                <span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis">214-235-1129</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-1">
                                    <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/email.svg'; ?>" />
                                    <h4 class="text-16 font-semibold">Email</h4>
                                </div>
                                <span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis">hello@indoorcreature.co</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-1">
                                    <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/website.svg'; ?>" />
                                    <h4 class="text-16 font-semibold">Website</h4>
                                </div>
                                <span class="text-14 text-yellow underline cursor-pointer whitespace-nowrap overflow-hidden text-ellipsis">indoorcreature.co</span>
                            </div>
                            <div>
                             <div class="flex items-center gap-1">
                                    <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/people.svg'; ?>" />
                                    <h4 class="text-16 font-semibold">Ensemble size</h4>
                                </div>
                                <span class="text-14 v">3, 4, 10+</span>
                            </div>
                        </div> <!-- End contact info -->
                        <div class="w-full bg-black/20 h-px"></div>
                        <div>
                            <h4 class="text-16 mb-3 font-bold">Social Media</h4>
                            <div class="grid grid-cols-[auto_auto] gap-x-6 gap-y-3 w-fit text-14">
                                <div class="flex items-center gap-2">
                                    <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Instagram.svg'; ?>" />
                                    <span class="whitespace-nowrap overflow-hidden text-ellipsis">@indoorcreature</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Spotify.svg'; ?>" />
                                    <span class="whitespace-nowrap overflow-hidden text-ellipsis">Indoor Creature</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_TikTok.svg'; ?>" />
                                    <span class="whitespace-nowrap overflow-hidden text-ellipsis">@indoorcreature</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_YouTube.svg'; ?>" />
                                    <span class="whitespace-nowrap overflow-hidden text-ellipsis">youtube.com/indoorcreature</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Facebook.svg'; ?>" />
                                    <span class="whitespace-nowrap overflow-hidden text-ellipsis">Indoor Creature</span>
                                </div>
                            </div>
                        </div> <!-- End social media -->
                    </div>
                </div> <!-- End sidebar module -->

                <div class="sidebar-module border border-black/40 rounded overflow-hidden">
                    <div data-toggle="taxonomies" class="flex items-center justify-between bg-yellow-light-50 font-bold py-2 px-3 cursor-pointer">
                        <h3>Taxonomies</h3>
                        <img class="h-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/chevron.svg'; ?>" />
                    </div>
                    <div data-toggle-target="taxonomies" class="p-4 flex flex-col gap-4 hidden">
                        <?php $taxonomy_tag_classes = 'bg-yellow-light px-2 py-0.5 rounded-full text-12'; ?>
                        <div>
                            <h4 class="text-16 mb-3">Genres</h4>
                            <div class="flex items-center gap-1">
                                <span class="<?php echo $taxonomy_tag_classes;?>">pop</span>
                                <span class="<?php echo $taxonomy_tag_classes;?>">rock</span>
                                <span class="<?php echo $taxonomy_tag_classes;?>">soul</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-16 mb-3">Sub-genres</h4>
                            <div class="flex items-center gap-1">
                                <span class="<?php echo $taxonomy_tag_classes;?>">lo-fi</span>
                                <span class="<?php echo $taxonomy_tag_classes;?>">nu funk</span>
                                <span class="<?php echo $taxonomy_tag_classes;?>">nerd rock</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-16 mb-3">Instrumentation</h4>
                            <div class="flex items-center gap-1">
                                <span class="<?php echo $taxonomy_tag_classes;?>">clarniet</span>
                                <span class="<?php echo $taxonomy_tag_classes;?>">vocals</span>
                                <span class="<?php echo $taxonomy_tag_classes;?>">keyboard</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-16 mb-3">Settings</h4>
                            <div class="flex items-center gap-1">
                                <span class="<?php echo $taxonomy_tag_classes;?>">amphitheatre</span>
                                <span class="<?php echo $taxonomy_tag_classes;?>">cocktail hour</span>
                                <span class="<?php echo $taxonomy_tag_classes;?>">metal bar</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-16 mb-3">More keywords</h4>
                            <div class="flex items-center gap-1">
                                <span class="<?php echo $taxonomy_tag_classes;?>">live looper</span>
                                <span class="<?php echo $taxonomy_tag_classes;?>">psychedelic steam punk</span>
                            </div>
                        </div>
                    </div>
                </div> <!-- End sidebar module -->

            </div>
        </div>
    </div>
    
</section>


<?php
get_footer();
