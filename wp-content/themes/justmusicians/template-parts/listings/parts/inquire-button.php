<!-- Request a quote buttons -->


<?php if (!empty($args['disabled']) and $args['disabled']) { ?>

    <!-- For previews and any time the button should do nothing -->
    <span class="sm:absolute sm:right-0 sm:bottom-3 w-full sm:w-fit">
        <button type="button" class="hover:bg-yellow-light bg-yellow px-3 py-3 sm:py-2 rounded-sm font-sun-motter text-14 inline-block w-full sm:w-fit" >Send Inquiry</button>
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
        <button type="button" class="hover:bg-yellow-light bg-yellow px-3 py-3 sm:py-2 rounded-sm font-sun-motter text-14 inline-block w-full sm:w-fit"
            x-show="!loggedIn" x-cloak
            x-on:click="showSignupModal = true; signupModalMessage = 'Sign up to request quotes from musicians'"
        >Send Inquiry</button>

        <!-- For logged in users with existing inquiries; opens inquiry dropdown menu -->
        <button type="button" class="hover:bg-yellow-light bg-yellow px-3 py-3 sm:py-2 rounded-sm font-sun-motter text-14 inline-block w-full sm:w-fit"
            x-show="loggedIn && Object.keys(inquiriesMap).length > 0" x-cloak
            x-on:click="showInquiriesMenu = true;"
        >Send Inquiry</button>

        <!-- For logged in users with no existing inquiries; opens inquiry modal -->
        <button type="button" class="hover:bg-yellow-light bg-yellow px-3 py-3 sm:py-2 rounded-sm font-sun-motter text-14 inline-block w-full sm:w-fit"
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
                <!-- heading -->
                <!-- <h3 class="font-bold text-18 pt-4 pb-2 border-b">Add to an existing request</h3> -->

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
                            <button type="button" class="mr-2 ml-4 border border-navy text-navy bg-white text-sm px-3 py-1 rounded-full hover:bg-navy hover:text-white hover:opacity-50"
                                x-show="_showAddListingToInquiryButton(inquiry.post_id, '<?php echo $args['post_id']; ?>')" x-cloak
                                x-bind:hx-post="'/wp-html/v1/inquiries/' + inquiry.post_id + '/listings/<?php echo $args['post_id']; ?>'"
                                hx-target="#inquiry-menu-result-<?php echo $args['post_id']; ?>"
                                hx-trigger="click"
                                hx-indicator="#decoy-indicator<?php echo $args['post_id']; ?>"
                                hx-vals='{"listing_id": "<?php echo $args['post_id']; ?>"}'
                            >Send</button>

                            <!-- Already requested state -->
                            <button type="button" class="mr-2 ml-4 border border-2 border-navy text-white bg-navy text-sm px-3 py-1 rounded-full"
                                x-show="_showListingInInquiry(inquiry.post_id, '<?php echo $args['post_id']; ?>')" x-cloak
                            >Sent</button>

                        </div>
                    </template>
                </div>

                <!-- Create New Inquiry -->
                <div class="pt-3 border-t flex justify-center">
                    <button type="button" class="w-full border rounded-full py-2 text-center text-sm text-black font-medium transition-all duration-300 hover:scale-[1.02] hover:shadow-sm"
                        x-on:click="_clearInquiryForm(); _openInquiryModal('<?php echo $args['post_id']; ?>', '<?php echo $args['name']; ?>');"
                    >+ Create new inquiry</button>
                </div>
            </div>
        </div>
        <span id="decoy-indicator<?php echo $args['post_id']; ?>"></span>

    </span>

<?php } ?>
