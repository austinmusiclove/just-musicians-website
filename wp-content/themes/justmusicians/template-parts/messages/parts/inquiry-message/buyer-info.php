<h3 class="text-20 font-bold mb-4">About The Sender</h3>
<div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">


    <!-- Thumbnail (Moved to Left and Made Circular) -->
    <img class="w-8 h-8 rounded-full mt-1" alt="Profile image" x-bind:src="message.sender_profile_image_url">


    <!-- Headings -->
    <div class="text-left">

        <!-- Title -->
        <h1 class="font-bold text-16 mb-2" x-text="message.sender_name"></h1>

        <!-- Position and org -->
        <div class="flex items-center gap-4 font-bold mb-2">
            <!--<span class="text-16 px-2 py-0.5 rounded-full bg-yellow inline-block"></span>-->
            <span class="text-14 uppercase text-brown-dark-1 opacity-50"
                x-show="message.sender_organization && !message.sender_position" x-cloak
                x-text="message.sender_organization"
            ></span>
            <span class="text-14 uppercase text-brown-dark-1 opacity-50"
                x-show="message.sender_organization && message.sender_position" x-cloak
                x-text="`${message.sender_position} at ${message.sender_organization}`"
            ></span>
        </div>

        <!-- Reviews -->
        <?php echo get_template_part('template-parts/messages/parts/inquiry-message/reviews', '', [] ); ?>

    </div>

</div>
