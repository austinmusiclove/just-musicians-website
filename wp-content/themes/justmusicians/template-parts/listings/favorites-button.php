<!-- Favorites buttons -->

<span id="favorite-button-<?php echo $args['post_id']; ?>" class="flex items-center ml-2 min-w-6 min-h-6"
    x-data="{
        showCollectionsOptions: false,
        showCreateCollectionInput: false,
        collectionSearchQuery: '',
        resetCollectionPopup() {
            this.showCollectionsOptions = false;
            this.showCreateCollectionInput = false;
            this.collectionSearchQuery = '';
            $refs.newCollectionInput<?php echo $args['post_id']; ?>.value = '';
        },
        _addCollection(post_id, name, listings) { return addCollection(this, post_id, name, listings); },
    }"
    x-on:add-collection="_addCollection($event.detail.post_id, $event.detail.name, $event.detail.listings)"
    <?php if (!empty($args['allow_hide']) and $args['allow_hide']) { ?>x-on:hide-listing="console.log('hid'); showListing = false;"<?php } ?>
>
    <button type="button" class="opacity-60 hover:opacity-100 hover:scale-105"
        <?php if (is_user_logged_in()) { ?>
            x-on:click="_addToFavorites('<?php echo $args['post_id']; ?>')"
            x-show="_showAddFavoriteButton('<?php echo $args['post_id']; ?>')" x-cloak
            x-on:mouseenter="showCollectionsOptions = true"
            x-on:mouseleave="resetCollectionPopup()"
            hx-post="/wp-html/v1/collections/<?php echo $args['collection_id']; ?>/listings/<?php echo $args['post_id']; ?>"
            hx-target="#favorites-result-<?php echo $args['post_id']; ?>"
            hx-trigger="click"
            hx-vals='{"listing_id": "<?php echo $args['post_id']; ?>"}'
        <?php } else { ?>
            x-on:click="showSignupModal = true; signupModalMessage = 'Sign up for an account to save listings'"
        <?php } ?>
    >
        <img class="h-6 w-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/favorite.svg'; ?>" />
    </button>
    <button type="button" class="opacity-60 hover:opacity-100 hover:scale-105"
        x-show="_showRemoveFavoriteButton('<?php echo $args['post_id']; ?>')" x-cloak
        x-on:mouseenter="showCollectionsOptions = true"
        x-on:mouseleave="resetCollectionPopup()"
        x-on:click="_removeFromFavorites('<?php echo $args['post_id']; ?>')"
        hx-delete="/wp-html/v1/collections/<?php echo $args['collection_id']; ?>/listings/<?php echo $args['post_id']; ?>"
        hx-target="#favorites-result-<?php echo $args['post_id']; ?>"
        hx-trigger="click"
    >
        <img class="h-6 w-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/favorite-red.svg'; ?>" />
    </button>
    <span id="favorites-result-<?php echo $args['post_id']; ?>"></span>

    <!-- Collections -->
    <div class="relative inline-block text-left">

        <!-- Dropdown Panel -->
        <div class="absolute top-2 right-0 z-10 mt-2 w-64 origin-top-right bg-white border border-gray-200 rounded-lg shadow-lg p-4 space-y-3"
            x-show="showCollectionsOptions" x-cloak
            x-transition x-on:click.away="showCollectionsOptions = false"
            x-on:mouseenter="showCollectionsOptions = true"
            x-on:mouseleave="resetCollectionPopup()"
        >

            <!-- Search Bar -->
            <input type="text" x-model="collectionSearchQuery" placeholder="Search collections..."
                class="w-full px-3 py-1.5 border border-gray-300 rounded focus:outline-none focus:ring focus:border-black text-sm" />

            <!-- Scrollable List of Collections -->
            <div class="max-h-40 overflow-y-auto space-y-2">
                <template x-for="collection in collections" :key="collection.post_id">
                    <div class="flex items-center justify-between hover:bg-gray-50 px-2 py-1 rounded cursor-pointer" x-show="collection.name.toLowerCase().includes(collectionSearchQuery)" x-cloak >
                        <span x-text="collection.name" class="text-sm"></span>
                        <button class="text-gray-500 hover:text-black">
                            <!-- You can swap this icon for a filled state -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5 5v14l7-5 7 5V5a2 2 0 00-2-2H7a2 2 0 00-2 2z" />
                            </svg>
                        </button>
                    </div>
                </template>
            </div>

            <!-- Create New Collection -->
            <div class="pt-3 border-t border-gray-200">
                <button type="button" x-on:click="showCreateCollectionInput = true" x-show="!showCreateCollectionInput" class="text-sm text-black font-medium hover:underline">+ Create new collection</button>

                <div x-show="showCreateCollectionInput" class="space-y-2">
                    <form
                        hx-post="/wp-html/v1/collections/"
                        hx-target="#create-collection-result-<?php echo $args['post_id']; ?>"
                    >
                        <input type="text" name="collection_name" class="w-full px-3 py-1.5 border border-gray-300 rounded focus:outline-none focus:ring focus:border-black text-sm" placeholder="Collection name" x-ref="newCollectionInput<?php echo $args['post_id']; ?>" />
                        <input type="hidden" name="listing_id" value="<?php echo $args['post_id']; ?>" />
                        <button type="submit" class="w-full text-center text-sm text-white bg-black py-1.5 rounded hover:bg-gray-800">Create</button>
                        <span id="create-collection-result-<?php echo $args['post_id']; ?>"></span>
                    </form>
                </div>
            </div>
        </div>
    </div>

</span>

