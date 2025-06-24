<div class="flex flex-col h-full w-full p-2">
    <div class="relative -mx-2 sm:mx-0 pt-1">
        <h3 class="font-bold text-18">YouTube Videos</h3>
            <!-- Buttons - screen 3 -->
            <div class="hidden flex gap-2 items-center absolute right-0 top-0">
                <button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Bulk delete</button>
                <button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white" x-on:click="showYoutubeLinkPopup = true">Upload +</button>
            </div>
    </div>

    <!-- Screen 1 -->
    <div class="flex flex-col items-center justify-center grow">
        <div class="text-center">No YouTube videos yet.</div>
        <button type="button" class="w-fit rounded text-14 mt-4 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white" x-on:click="showYoutubeLinkPopup = true">Upload +</button>
    </div>

    <!-- Screen 3 -->
    <div class="hidden flex flex-col grow -mx-2 mt-6">
       <?php
        $items = [
            [
                'image' => 'calliope-musicals.jpg',
                'url' => 'https://www.youtube.com/watch?v=S3VOtKRNybg',
                //'tags' => ['live looper', 'orchestra', 'world music']
            ],
            [
                'image' => 'chastity.jpg',
                'url' => 'https://www.youtube.com/watch?v=S3VOtKRNybg'
            ],
            [
                'image' => 'kiltro.jpeg',
                'url' => 'https://www.youtube.com/watch?v=S3VOasdfRNg',
            ],
            [
                'image' => 'riders-against-the-storm.jpg',
                'url' => 'https://www.youtube.com/watch?v=S3asdadgtKR'
            ],
            [
                'image' => 'guitar-lessons.jpg',
                'url' => 'https://www.youtube.com/watch?v=S3VljlkjRNy'
            ],
        ];
        ?>
        <?php foreach ($items as $item): ?>
            <div class="flex items-center justify-between gap-6 sm:pl-3 sm:pr-2 py-2 border-b border-black/20 last:border-none w-full">
                <div class="flex items-center gap-4 grow min-w-0">
                    <label class="custom-checkbox -mt-1">
                        <input type="checkbox"/>
                        <span class="checkmark"></span>
                    </label>
                    <div class="aspect-video w-16">
                        <img class="w-full h-full object-cover" src="<?php echo get_template_directory_uri() . '/lib/images/placeholder/'.esc_attr($item['image']); ?>'">
                    </div>
                    <div class="text-14 text-grey truncate overflow-hidden whitespace-nowrap grow-0 shrink min-w-0">
                        <?php echo esc_html($item['url']); ?>
                    </div>
                </div>
                <button class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Edit</button>
            </div>
        <?php endforeach; ?>

    </div>

</div>
