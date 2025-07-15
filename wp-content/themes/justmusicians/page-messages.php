<?php
/**
 * The template for the messages landing page
 *
 * @package JustMusicians
 */

if (!is_user_logged_in()) { wp_redirect(site_url()); } // Don't allow non logged in users to see this page
get_header();

?>

<div id="sticky-sidebar" class="hidden lg:block fixed top-0 z-10 left-0 bg-white h-screen dropshadow-md px-3 w-fit pt-40 border-r border-black/20">
    <div class="sidebar">
        <?php echo get_template_part('template-parts/account/sidebar', '', [
            'collapsible' => true
        ]); ?>
    </div>
</div>

<div class="lg:container h-[69vh]">
    <div class="px-4 lg:pr-0 md:pl-12 lg:pl-0 lg:grid lg:grid-cols-12 gap-12 xl:gap-28">

        <!-- Conversations Menu -->
        <div class="col-span-12 lg:col-span-3 z-0 border-r border-black/20">
            <header class="pt-4 sm:pt-20 xl:pt-32 mb-4 sm:mb-12 gap-12 sm:gap-4 flex flex-col-reverse sm:flex-row justify-between sm:items-center">
                <h1 class="font-bold text-22 sm:text-25">Conversations</h1>
            </header>
            <!-- Get Conversations -->
        </div>

        <!-- Messageing App -->
        <div class="hidden pb-4 lg:flex flex-col lg:col-span-9 h-[69vh]">

            <!-- Message Bubbles -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4" x-init="$el.scrollTop = $el.scrollHeight;">
                <!-- Example Bubbles -->
                <div class="w-fit max-w-[75%] bg-yellow-light-50 rounded p-3 text-sm">Hey there! How's your music coming along?</div>
                <div class="w-fit max-w-[75%] bg-yellow-light-50 rounded p-3 text-sm ml-auto">Pretty good! Just finished recording a new track 🔥</div>
                <div class="w-fit max-w-[75%] bg-yellow-light-50 rounded p-3 text-sm">That is awesome. I love the song. I'd like to hire you to write a new song for me. I want to be the performer on the song and I'll credit you as a songwriter and producer and give you 10% of the royalties.</div>
                <div class="w-fit max-w-[75%] bg-yellow-light-50 rounded p-3 text-sm ml-auto">That is awesome. I love the song. I'd like to hire you to write a new song for me. I want to be the performer on the song and I'll credit you as a songwriter and producer and give you 10% of the royalties.</div>
                <div class="w-fit max-w-[75%] bg-yellow-light-50 rounded p-3 text-sm">Hey there! How's your music coming along?</div>
                <div class="w-fit max-w-[75%] bg-yellow-light-50 rounded p-3 text-sm ml-auto">Pretty good! Just finished recording a new track 🔥</div>
                <div class="w-fit max-w-[75%] bg-yellow-light-50 rounded p-3 text-sm">Hey there! How's your music coming along?</div>
                <div class="w-fit max-w-[75%] bg-yellow-light-50 rounded p-3 text-sm ml-auto">Pretty good! Just finished recording a new track 🔥</div>
                <div class="w-fit max-w-[75%] bg-yellow-light-50 rounded p-3 text-sm">That is awesome. I love the song. I'd like to hire you to write a new song for me. I want to be the performer on the song and I'll credit you as a songwriter and producer and give you 10% of the royalties.</div>
                <div class="w-fit max-w-[75%] bg-yellow-light-50 rounded p-3 text-sm ml-auto">That is awesome. I love the song. I'd like to hire you to write a new song for me. I want to be the performer on the song and I'll credit you as a songwriter and producer and give you 10% of the royalties.</div>
                <div class="w-fit max-w-[75%] bg-yellow-light-50 rounded p-3 text-sm">Hey there! How's your music coming along?</div>
                <div class="w-fit max-w-[75%] bg-yellow-light-50 rounded p-3 text-sm ml-auto">Pretty good! Just finished recording a new track 🔥</div>
            </div>

            <!-- Message Input Area -->
            <div class="border-t border-black/20 px-4 pt-2">
                <div class="flex items-end">
                    <textarea class="p-2 w-full border border-black/20 rounded resize-none overflow-hidden focus:outline-none" name="message" rows="1" placeholder="Please enter a message."
                        x-on:input="$el.rows = $el.value.split(/\r\n|\r|\n/).length;"
                    ></textarea>
                    <button type="button" class="ml-2 bg-navy text-white hover:bg-yellow hover:text-black shadow-black-offset border-2 border-black font-sun-motter text-16 px-5 py-3">Send</button>
                </div>
            </div>

        </div>


    </div>
</div>

<?php
get_footer();


