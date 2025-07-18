<!-- Favorites buttons -->

<span id="favorite-button-<?php echo $args['post_id']; ?>" class="flex items-center ml-2 min-w-6 min-h-6"
    x-data="{
        showCollectionsPopup: false,
        showCreateCollectionInput: false,
        collectionSearchQuery: '',
        _addCollection(postId, name, listings, permalink) { return addCollection(this, postId, name, listings, permalink); },
        _addToCollection(collectionId, listingId)         { return addToCollection(this, collectionId, listingId); },
        _removeFromCollection(collectionId, listingId)    { return removeFromCollection(this, collectionId, listingId); },
        _resetCollectionsPopup()                          { return resetCollectionsPopup(this, '<?php echo $args['post_id']; ?>'); },
    }"
    x-on:add-collection="_addCollection($event.detail.post_id, $event.detail.name, $event.detail.listings, $event.detail.permalink)"
    x-on:add-listing="_addToCollection($event.detail.collection_id, $event.detail.listing_id)"
    x-on:remove-listing="_removeFromCollection($event.detail.collection_id, $event.detail.listing_id)"
>
    <button type="button" class="opacity-60 hover:opacity-100 hover:scale-105"
        <?php if (is_user_logged_in()) { ?>
            x-show="_showEmptyFavoriteButton('<?php echo $args['post_id']; ?>')" x-cloak
            hx-post="<?php echo site_url('/wp-html/v1/collections/0/listings/' . $args['post_id']); ?>"
            hx-target="#favorites-result-<?php echo $args['post_id']; ?>"
            hx-trigger="click"
            hx-indicator="#decoy-indicator"
            hx-vals='{"listing_id": "<?php echo $args['post_id']; ?>"}'
        <?php } else { ?>
            x-on:click="showSignupModal = true; signupModalMessage = 'Sign up for an account to save listings'"
        <?php } ?>
    >
    <svg class="w-4 h-auto fill-navy opacity-60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
        <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
        <path d="M0 48C0 21.5 21.5 0 48 0l0 48 0 393.4 130.1-92.9c8.3-6 19.6-6 27.9 0L336 441.4 336 48 48 48 48 0 336 0c26.5 0 48 21.5 48 48l0 440c0 9-5 17.2-13 21.3s-17.6 3.4-24.9-1.8L192 397.5 37.9 507.5c-7.3 5.2-16.9 5.9-24.9 1.8S0 497 0 488L0 48z"/>
    </svg>
    </button>
    <button type="button" class="opacity-60 hover:opacity-100 hover:scale-105"
        x-show="_showFilledFavoriteButton('<?php echo $args['post_id']; ?>')" x-cloak
        x-on:click="showCollectionsPopup = true"
        hx-delete="<?php echo site_url('/wp-html/v1/collections/0/listings/' . $args['post_id']); ?>"
        hx-target="#favorites-result-<?php echo $args['post_id']; ?>"
        hx-trigger="remove-from-favorites"
        hx-indicator="#decoy-indicator"
    >
         <svg class="w-4 h-auto fill-navy" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
            <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
            <path d="M0 48V487.7C0 501.1 10.9 512 24.3 512c5 0 9.9-1.5 14-4.4L192 400 345.7 507.6c4.1 2.9 9 4.4 14 4.4c13.4 0 24.3-10.9 24.3-24.3V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48z"/>
        </svg>
    </button>
    <span id="favorites-result-<?php echo $args['post_id']; ?>"></span>

    <!-- Collections -->
    <div class="relative inline-block text-left">

        <!-- Dropdown Panel -->
        <div class="absolute top-2 right-0 z-10 mt-2 w-64 origin-top-right bg-white border rounded-lg shadow-lg p-4 space-y-3"
            x-show="showCollectionsPopup" x-cloak
            x-transition
            x-on:mouseenter="showCollectionsPopup = true"
            x-on:mouseleave="_resetCollectionsPopup()"
            x-on:click.away="_resetCollectionsPopup()"
            x-intersect:leave="_resetCollectionsPopup()"
        >

            <div class="flex items-center justify-between pr-3">
                <span class="font-bold text-20">Add to collection</span>
                <svg class="w-4 opacity-40 cursor-pointer hover:opacity-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z"/></svg>
            </div>
            <!-- Search Bar -->
            <input type="text" placeholder="Search collections..." class="w-full px-3 py-1.5 border rounded focus:outline-none focus:ring focus:border-black text-sm"
                x-model="collectionSearchQuery"
            />

            <!-- Scrollable List of Collections -->
            <div x-ref="collectionsList<?php echo $args['post_id']; ?>" class="max-h-40 overflow-y-auto space-y-2">
                <template x-for="collection in sortedCollections" :key="collection.post_id">
                    <div class="flex items-center justify-between px-2 py-1 rounded cursor-pointer"
                        x-show="collection.name.toLowerCase().includes(collectionSearchQuery)" x-cloak
                        x-init="$nextTick(() => htmx.process($el))";
                    >
                        <a class="w-full" x-bind:href="collection.permalink"><span x-text="collection.name"></span></a>
                        <!-- Empty bookmark state -->
                        <button type="button" class="w-6 flex items-center"
                            x-show="_showEmptyCollectionButton(collection.post_id, '<?php echo $args['post_id']; ?>')" x-cloak
                            x-bind:hx-post="'<?php echo site_url(); ?>/wp-html/v1/collections/' + collection.post_id + '/listings/<?php echo $args['post_id']; ?>'"
                            hx-target="#favorites-result-<?php echo $args['post_id']; ?>"
                            hx-trigger="click"
                            hx-indicator="#decoy-indicator"
                            hx-vals='{"listing_id": "<?php echo $args['post_id']; ?>"}'
                        >
                            <svg class="w-3 opacity-40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/>
                            </svg>

                        </button>
                        <!-- Filled bookmark state -->
                        <button type="button" class="w-6 flex items-center"
                            x-show="_showFilledCollectionButton(collection.post_id, '<?php echo $args['post_id']; ?>')" x-cloak
                            x-bind:hx-delete="'<?php echo site_url(); ?>/wp-html/v1/collections/' + collection.post_id + '/listings/<?php echo $args['post_id']; ?>'"
                            hx-target="#favorites-result-<?php echo $args['post_id']; ?>"
                            hx-trigger="click"
                            hx-indicator="#decoy-indicator"
                        >
                        <svg class="w-6 fill-yellow" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M382-208 122-468l90-90 170 170 366-366 90 90-456 456Z"/></svg>
                        </button>
                    </div>
                </template>
            </div>

            <!-- Create New Collection -->
            <div class="pt-3 border-t">
                <button type="button" class="text-sm text-black font-medium w-full"
                    x-show="!showCreateCollectionInput"
                    x-on:click="showCreateCollectionInput = true; $nextTick(() => $refs.newCollectionInput<?php echo $args['post_id']; ?>.focus());"
                >
                    <span class="w-full flex items-center justify-between pr-4">
                        <span>Create new collection</span>
                        <span>+</span>
                    </span>
                </button>

                <div x-show="showCreateCollectionInput" class="space-y-2">
                    <form
                        hx-post="<?php echo site_url('/wp-html/v1/collections/'); ?>"
                        hx-target="#create-collection-result-<?php echo $args['post_id']; ?>"
                        hx-indicator="#decoy-indicator"
                    >
                        <input type="text" name="collection_name" class="w-full mb-2 px-3 py-1.5 border rounded focus:outline-none focus:ring focus:border-black" placeholder="Collection name" x-ref="newCollectionInput<?php echo $args['post_id']; ?>" />
                        <input type="hidden" name="listing_id" value="<?php echo $args['post_id']; ?>" />
                        <button type="submit" class="w-full text-center text-sm text-white bg-black py-1.5 rounded">Create</button>
                        <span id="create-collection-result-<?php echo $args['post_id']; ?>"></span>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <span id="decoy-indicator"></span>

</span>

