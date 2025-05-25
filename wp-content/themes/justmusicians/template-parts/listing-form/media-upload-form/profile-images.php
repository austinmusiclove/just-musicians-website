<div data-media-tab="profile-images" class="hidden flex flex-col h-full p-2">
    <div class="relative">
        <h3 class="font-bold text-18">Profile Images</h3>
        <!-- Buttons - screen 2 -->
        <div data-parent-tab="profile-images" data-screen="2" class="hidden flex gap-2 items-center absolute right-0 top-0">
                <button data-show="profile-images-1" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Bulk delete</button>
                <button data-show="profile-images-2" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Upload +</button>
        </div>
    </div>

    <!-- Screen 1 -->
    <div data-parent-tab="profile-images" data-screen="1" class="flex flex-col items-center justify-center grow text-center px-8">
        <div>These images will be added to the media gallery on your listing page.</div>
        <button data-show="profile-images-2" class="w-fit rounded text-14 mt-4 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Upload +</button>
    </div>

    <!-- Screen 2 -->
    <div data-parent-tab="profile-images" data-screen="2" class="hidden flex flex-col grow -mx-2 mt-8">
       <?php
        $items = [
            [
                'image' => 'calliope-musicals.jpg',
            ],
            [
                'image' => 'chastity.jpg',
            ],
            [
                'image' => 'kiltro.jpeg',
            ],
            [
                'image' => 'riders-against-the-storm.jpg',
            ],
            [
                'image' => 'guitar-lessons.jpg',
            ],
        ];
        ?>
        <?php foreach ($items as $item): ?>
            <div class="flex items-center justify-between gap-6 pl-3 pr-2 py-2 border-b border-black/20 last:border-none w-full">
                <div class="flex items-center gap-4">
                    <label class="custom-checkbox -mt-1">
                        <input type="checkbox"/>
                        <span class="checkmark"></span>
                    </label>
                    <div class="w-16">
                        <img class="w-full h-full object-cover" src="<?php echo get_template_directory_uri() . '/lib/images/placeholder/'.esc_attr($item['image']); ?>'">
                    </div>
                    <div class="text-14 text-grey">
                        <?php echo esc_html($item['image']); ?>
                    </div>
                </div>
                <button data-show="profile-images-2" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Delete</button>
            </div>
        <?php endforeach; ?>

    </div>

</div>