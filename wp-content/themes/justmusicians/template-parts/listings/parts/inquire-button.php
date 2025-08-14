
<?php if (!empty($args['disabled']) and $args['disabled']) { ?>

    <!-- For previews and any time the button should do nothing -->
    <span class="sm:absolute sm:right-0 sm:bottom-3 w-full sm:w-fit">
        <button type="button" class="hover:bg-yellow-light bg-yellow px-3 py-3 sm:py-2 rounded-sm font-sun-motter text-14 sm:text-12 inline-block w-full sm:w-fit" >Send Inquiry</button>
    </span>

<?php } else { ?>

    <span id="request-quote-button-<?php echo $args['post_id']; ?>" class="sm:absolute sm:right-0 sm:bottom-3 w-full sm:w-fit"
        x-data="{
            showInquiriesMenu: false,
            inquirySearchQuery: '',
            _addToInquiry(inquiryId, listingId) { return addToInquiry(this, inquiryId, listingId); },
            _resetInquiriesMenu()               { return resetInquiriesMenu(this, '<?php echo $args['post_id']; ?>'); },
        }"
        x-on:add-listing-to-inquiry="_addToInquiry($event.detail.inquiry_id, $event.detail.listing_id)"
    >

        <!-- For not logged in users; button directs users to create an account -->
        <button type="button" class="hover:bg-yellow-light bg-yellow px-3 py-3 sm:py-2 rounded-sm font-sun-motter text-14 sm:text-12 inline-block w-full sm:w-fit"
            x-show="!loggedIn" x-cloak
            x-on:click="showSignupModal = true; signupModalMessage = 'Sign up to send inquiries to musicians'"
        >Send Inquiry</button>

        <!-- For logged in users with existing inquiries; opens inquiry dropdown menu -->
        <button type="button" class="hover:bg-yellow-light bg-yellow px-3 py-3 sm:py-2 rounded-sm font-sun-motter text-14 sm:text-12 inline-block w-full sm:w-fit"
            x-show="loggedIn && Object.keys(inquiriesMap).length > 0" x-cloak
            x-on:click="showInquiriesMenu = true;"
        >Send Inquiry</button>

        <!-- For logged in users with no existing inquiries; opens inquiry modal -->
        <button type="button" class="hover:bg-yellow-light bg-yellow px-3 py-3 sm:py-2 rounded-sm font-sun-motter text-14 sm:text-12 inline-block w-full sm:w-fit"
            x-show="loggedIn && Object.keys(inquiriesMap).length == 0" x-cloak
            x-on:click="_clearInquiryForm(); _openInquiryModal('<?php echo $args['post_id']; ?>', '<?php echo $args['name']; ?>');"
        >Send Inquiry</button>

        <!-- Results from inquiries menu -->
        <span id="inquiry-menu-result-<?php echo $args['post_id']; ?>"></span>

        <!-- Inquiries Menu -->
        <div class="relative inline-block text-left">

            <!-- Dropdown Panel -->
            <div class="absolute origin-top-right sm:right-0 sm:top-2 w-80 sm:w-96 z-10 mt-2 bg-white border rounded-lg shadow-lg p-4 space-y-3"
                x-show="showInquiriesMenu" x-cloak
                x-transition
                x-on:mouseenter="showInquiriesMenu = true"
                x-on:mouseleave="_resetInquiriesMenu()"
                x-on:click.away="_resetInquiriesMenu()"
                x-intersect:leave="_resetInquiriesMenu()"
            >

                <!-- Title -->
                <div class="flex items-center justify-between pr-3">
                    <span class="font-bold text-20">Send an existing inquiry</span>
                    <a href="<?php echo site_url('/inquiries/'); ?>">
                        <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                        <svg class="w-4 opacity-40 cursor-pointer hover:opacity-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z"/>
                        </svg>
                    </a>
                </div>

                <!-- Search Bar -->
                <input type="text" placeholder="Search my existing inquiries..." class="w-full px-3 py-1.5 border rounded focus:outline-none focus:ring focus:border-black text-sm"
                    x-model="inquirySearchQuery"
                />


                <!-- Scrollable List of Inquiries -->
                <div x-ref="inquiriesList<?php echo $args['post_id']; ?>" class="my-4 min-h-40 max-h-60 overflow-y-auto space-y-2">
                    <template x-for="(inquiry, index) in sortedInquiries" :key="inquiry.post_id">
                        <div class="flex items-center justify-between px-2 py-1 rounded cursor-pointer"
                            :class="{ 'border-b border-black/20': index < sortedInquiries.length-1 }"
                            x-show="inquiry.subject.toLowerCase().includes(inquirySearchQuery)" x-cloak
                            x-init="$nextTick(() => htmx.process($el))";
                        >
                            <a class="flex-1 min-w-0 text-16" x-bind:href="inquiry.permalink"><span x-text="inquiry.subject.length > 50 ? inquiry.subject.slice(0,50) + '...' : inquiry.subject" x-bind:title="inquiry.subject"></span></a>

                            <!-- Add listing to inquiry button -->
                            <button type="button" class="ml-4 border border-navy text-navy bg-white text-sm px-3 py-1 rounded-full hover:bg-navy hover:text-white hover:opacity-50"
                                x-show="_showAddListingToInquiryButton(inquiry.post_id, '<?php echo $args['post_id']; ?>')" x-cloak
                                x-bind:hx-post="'<?php echo site_url(); ?>' + '/wp-html/v1/inquiries/' + inquiry.post_id + '/listings/<?php echo $args['post_id']; ?>'"
                                hx-target="#inquiry-menu-result-<?php echo $args['post_id']; ?>"
                                hx-trigger="click"
                                hx-indicator="#decoy-indicator<?php echo $args['post_id']; ?>"
                                hx-vals='{"listing_id": "<?php echo $args['post_id']; ?>"}'
                            >Send</button>

                            <!-- Already sent state -->
                            <button type="button" class="ml-4 border border-2 border-navy text-white bg-navy text-sm px-3 py-1 rounded-full"
                                x-show="_showListingInInquiry(inquiry.post_id, '<?php echo $args['post_id']; ?>')" x-cloak
                            >Sent</button>

                        </div>
                    </template>
                </div>

                <!-- Create New Inquiry -->
                <div class="pt-3 border-t flex justify-center">
                    <!--<button type="button" class="w-full border rounded-full py-2 text-center text-sm text-black font-medium transition-all duration-300 hover:scale-[1.02] hover:shadow-sm"-->
                    <button type="button" class="text-sm text-black font-medium w-full hover:underline"
                        x-on:click="_clearInquiryForm(); _openInquiryModal('<?php echo $args['post_id']; ?>', '<?php echo $args['name']; ?>');"
                    >Create new inquiry +</button>
                </div>
            </div>
        </div>
        <span id="decoy-indicator<?php echo $args['post_id']; ?>"></span>

    </span>

<?php } ?>
