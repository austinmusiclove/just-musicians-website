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
        <img class="h-6 w-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/favorite.svg'; ?>" />
    </button>
    <button type="button" class="opacity-60 hover:opacity-100 hover:scale-105"
        x-show="_showFilledFavoriteButton('<?php echo $args['post_id']; ?>')" x-cloak
        x-on:click="showCollectionsPopup = true"
        hx-delete="<?php echo site_url('/wp-html/v1/collections/0/listings/' . $args['post_id']); ?>"
        hx-target="#favorites-result-<?php echo $args['post_id']; ?>"
        hx-trigger="remove-from-favorites"
        hx-indicator="#decoy-indicator"
    >
        <img class="h-6 w-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/favorite-red.svg'; ?>" />
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
                        <button type="button" class="mr-2"
                            x-show="_showEmptyCollectionButton(collection.post_id, '<?php echo $args['post_id']; ?>')" x-cloak
                            x-bind:hx-post="'<?php echo site_url(); ?>/wp-html/v1/collections/' + collection.post_id + '/listings/<?php echo $args['post_id']; ?>'"
                            hx-target="#favorites-result-<?php echo $args['post_id']; ?>"
                            hx-trigger="click"
                            hx-indicator="#decoy-indicator"
                            hx-vals='{"listing_id": "<?php echo $args['post_id']; ?>"}'
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5 5v14l7-5 7 5V5a2 2 0 00-2-2H7a2 2 0 00-2 2z" />
                            </svg>
                        </button>
                        <!-- Filled bookmark state -->
                        <button type="button" class="mr-2"
                            x-show="_showFilledCollectionButton(collection.post_id, '<?php echo $args['post_id']; ?>')" x-cloak
                            x-bind:hx-delete="'<?php echo site_url(); ?>/wp-html/v1/collections/' + collection.post_id + '/listings/<?php echo $args['post_id']; ?>'"
                            hx-target="#favorites-result-<?php echo $args['post_id']; ?>"
                            hx-trigger="click"
                            hx-indicator="#decoy-indicator"
                        >
                            <svg class="w-4 h-4 fill-yellow" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M5 3a2 2 0 00-2 2v16l9-6 9 6V5a2 2 0 00-2-2H5z" />
                            </svg>
                        </button>
                    </div>
                </template>
            </div>

            <!-- Create New Collection -->
            <div class="pt-3 border-t">
                <button type="button" class="text-sm text-black font-medium hover:underline"
                    x-show="!showCreateCollectionInput"
                    x-on:click="showCreateCollectionInput = true; $nextTick(() => $refs.newCollectionInput<?php echo $args['post_id']; ?>.focus());"
                >
                    + Create new collection
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

