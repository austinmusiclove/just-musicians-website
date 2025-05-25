<div data-media-tab="thumbnails" class="flex flex-col h-full p-2">
    <div class="relative">
            <h3 class="font-bold text-18 flex items-center gap-1">
            Thumbnails<span class="text-red">*</span>
            </h3>
            <!-- Buttons - screen 3 -->
            <div data-parent-tab="thumbnails" data-screen="3" class="hidden flex gap-2 items-center absolute right-0 top-0">
                <button data-show="thumbnails-1" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Bulk delete</button>
                <button data-show="thumbnails-2" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Upload +</button>
            </div>
    </div>

    <!-- Screen 1 -->
    <div data-parent-tab="thumbnails" data-screen="1" class="flex flex-col items-center justify-center grow">
        <div class="font-bold">At least one thumbnail is required.</div>
        <div>Add your first.</div>
        <button data-show="thumbnails-2" class="w-fit rounded text-14 mt-4 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Upload +</button>
    </div>

    <!-- Screen 3 -->
    <div data-parent-tab="thumbnails" data-screen="3" class="hidden flex flex-col grow -mx-2 mt-6">
       <?php
        $items = [
            [
                'image' => 'calliope-musicals.jpg',
                'tags' => ['live looper', 'orchestra', 'world music']
            ],
            [
                'image' => 'chastity.jpg',
                'tags' => ['gospel', 'world music']
            ],
            [
                'image' => 'kiltro.jpeg',
                'tags' => ['live looper', 'world music']
            ],
            [
                'image' => 'riders-against-the-storm.jpg',
                'tags' => ['live looper', 'orchestra', 'world music']
            ],
            [
                'image' => 'guitar-lessons.jpg',
                'tags' => ['gospel', 'world music']
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
                    <div class="aspect-4/3 w-16">
                        <img class="w-full h-full object-cover" src="<?php echo get_template_directory_uri() . '/lib/images/placeholder/'.esc_attr($item['image']); ?>'">
                    </div>
                    <div class="tags flex gap-1">
                        <?php foreach ($item['tags'] as $tag): ?>
                            <div class="w-fit flex items-center bg-yellow-20 px-3 h-6 rounded-full">
                                <span class="text-14 w-fit"><?= esc_html($tag); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button data-show="thumbnails-2" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Edit</button>
            </div>
        <?php endforeach; ?>

    </div>

</div>