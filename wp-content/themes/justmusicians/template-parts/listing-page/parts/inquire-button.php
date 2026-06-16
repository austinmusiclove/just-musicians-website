<?php if (!empty($args['disabled']) and $args['disabled']) { ?>

    <!-- For previews and any time the button should do nothing -->
    <button type="button" class="hover:bg-yellow-light bg-yellow w-full shadow-black-offset border-2 border-black font-sun-motter text-18 px-2 py-2">Send Inquiry</button>

<?php } else { ?>

    <span id="request-quote-button-<?php echo $args['post_id']; ?>"
        x-data="{
            showEventsMenu: false,
            eventSearchQuery: '',
            _addListingToEvent(eventId, listingId) { return addListingToEvent(this, eventId, listingId); },
            _resetEventsMenu()                     { return resetEventsMenu(this, '<?php echo $args['post_id']; ?>'); },
        }"
        x-on:add-listing-to-event="_addListingToEvent($event.detail.event_id, $event.detail.listing_id)"
    >

        <!-- For not logged in users; button directs users to create an account -->
        <button type="button" class="hover:bg-yellow-light bg-yellow w-full shadow-black-offset border-2 border-black font-sun-motter text-18 px-2 py-2"
            x-show="!loggedIn" x-cloak
            x-on:click="showSignupModal = true; signupModalMessage = 'Sign up to send inquiries to musicians'"
        >Send Inquiry</button>

        <!-- For logged in users with existing events; opens events dropdown menu -->
        <button type="button" class="hover:bg-yellow-light bg-yellow w-full shadow-black-offset border-2 border-black font-sun-motter text-18 px-2 py-2"
            x-show="loggedIn && Object.keys(eventsMap).length > 0" x-cloak
            x-on:click="showEventsMenu = true;"
        >Send Inquiry</button>

        <!-- For logged in users with no existing events; opens inquiry modal -->
        <button type="button" class="hover:bg-yellow-light bg-yellow w-full shadow-black-offset border-2 border-black font-sun-motter text-18 px-2 py-2"
            x-show="loggedIn && Object.keys(eventsMap).length == 0" x-cloak
            x-on:click="_clearInquiryForm(); _openInquiryModal('<?php echo $args['post_id']; ?>', '<?php echo clean_str_for_doublequotes($args['name']); ?>');"
        >Send Inquiry</button>

        <!-- Results from inquiries menu -->
        <span id="inquiry-menu-result-<?php echo $args['post_id']; ?>"></span>

        <!-- Existing Events Menu -->
        <div class="relative inline-block text-left">

            <!-- Dropdown Panel -->
            <div class="absolute origin-top-right sm:right-0 sm:top-2 w-80 sm:w-96 z-10 mt-2 bg-white border rounded-lg shadow-lg p-4 space-y-3"
                x-show="showEventsMenu" x-cloak
                x-transition
                x-on:mouseenter="showEventsMenu = true"
                x-on:mouseleave="_resetEventsMenu()"
                x-on:click.away="_resetEventsMenu()"
                x-intersect:leave="_resetEventsMenu()"
            >

                <!-- Title -->
                <div class="flex items-center justify-between pr-3">
                    <span class="font-bold text-20">Request proposal for existing event</span>
                </div>

                <!-- Search Bar -->
                <input type="text" placeholder="Search my existing events..." class="w-full px-3 py-1.5 border rounded focus:outline-none focus:ring focus:border-black text-sm"
                    x-model="eventSearchQuery"
                />


                <!-- Scrollable List of Events -->
                <div x-ref="eventsList<?php echo $args['post_id']; ?>" class="my-4 min-h-40 max-h-60 overflow-y-auto space-y-2">
                    <template x-for="(event, index) in sortedEvents" :key="event.post_id">
                        <div class="flex items-center justify-between px-2 py-1 rounded cursor-pointer"
                            :class="{ 'border-b border-black/20': index < sortedEvents.length-1 }"
                            x-show="event.event_name.toLowerCase().includes(eventSearchQuery)" x-cloak
                            x-init="$nextTick(() => htmx.process($el))";
                        >
                            <a class="flex-1 min-w-0 text-16" x-bind:href="event.permalink"><span x-text="event.event_name.length > 50 ? event.event_name.slice(0,50) + '...' : event.event_name" x-bind:title="event.event_name"></span></a>

                            <!-- Request proposal button -->
                            <button type="button" class="ml-4 border border-navy text-navy bg-white text-sm px-3 py-1 rounded-full hover:bg-navy hover:text-white hover:opacity-50"
                                x-show="_showRequestProposalButton(event.post_id, '<?php echo $args['post_id']; ?>')" x-cloak
                                x-bind:hx-post="'<?php echo site_url(); ?>' + '/wp-html/v1/events/' + event.post_id + '/listings/<?php echo $args['post_id']; ?>/request-proposal/'"
                                hx-target="#inquiry-menu-result-<?php echo $args['post_id']; ?>"
                                hx-trigger="click"
                                x-bind:hx-indicator="'#request-proposal-button-content-<?php echo $args['post_id']; ?>-' + event.post_id"
                            >
                                <span x-bind:id="'request-proposal-button-content-<?php echo $args['post_id']; ?>-' + event.post_id">
                                    <span class="htmx-indicator-component-block-replace">Send</span>
                                    <span class="htmx-indicator-component-block mx-2 my-1">
                                        <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
                                    </span>
                                </span>
                            </button>

                            <!-- Already sent state -->
                            <button type="button" class="ml-4 border border-2 border-navy text-white bg-navy text-sm px-3 py-1 rounded-full"
                                x-show="!_showRequestProposalButton(event.post_id, '<?php echo $args['post_id']; ?>')" x-cloak
                            >Sent</button>

                        </div>
                    </template>
                </div>

                <!-- Create New Event -->
                <div class="pt-3 border-t flex justify-center">
                    <!--<button type="button" class="w-full border rounded-full py-2 text-center text-sm text-black font-medium transition-all duration-300 hover:scale-[1.02] hover:shadow-sm"-->
                    <button type="button" class="text-sm text-black font-medium w-full hover:underline"
                        x-on:click="_clearInquiryForm(); _openInquiryModal('<?php echo $args['post_id']; ?>', '<?php echo clean_str_for_doublequotes($args['name']); ?>');"
                    >Create new event +</button>
                </div>
            </div>
        </div>

    </span>

<?php } ?>
