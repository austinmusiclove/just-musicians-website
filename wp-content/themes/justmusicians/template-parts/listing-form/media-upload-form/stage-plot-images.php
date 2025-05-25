<div data-media-tab="stage-plot-images" class="hidden flex flex-col h-full p-2">
    <div class="relative -mx-2 sm:mx-0 pt-1">
            <h3 class="font-bold text-18 flex items-center gap-1">Stage Plot Images</h3>
            <!-- Buttons - screen 3 -->
            <div data-parent-tab="stage-plot-images" data-screen="3" class="hidden flex gap-2 items-center absolute right-0 top-0">
                <button data-show="stage-plot-images-1" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Bulk delete</button>
                <button data-show="stage-plot-images-2" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Upload +</button>
            </div>
    </div>

    <!-- Screen 1 -->
    <div data-parent-tab="stage-plot-images" data-screen="1" class="flex flex-col items-center justify-center grow text-center px-8">
        <div>A stage plot can help a potential client determine whether they can accomodate your band's setup.</div>
        <button data-show="stage-plot-images-2" class="w-fit rounded text-14 mt-4 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Upload +</button>
    </div>

    <!-- Screen 3 -->
    <div data-parent-tab="stage-plot-images" data-screen="3" class="hidden flex flex-col grow -mx-2 mt-6">
       <?php
        $items = [
            [
                'image' => 'stage-plot.jpg',
                'caption' => 'Layout of the band/’s live setup, including instrument and monitor',
            ],
            [
                'image' => 'stage-plot.jpg',
                'caption' => 'View of mic placements, DI boxes, and stage monitor configurations'
            ],
            [
                'image' => 'stage-plot.jpg',
                'caption' => 'Visual guide for FOH engineers and stage crew',
            ],
            [
                'image' => 'stage-plot.jpg',
                'caption' => 'Placement of equipment, risers, and personnel'
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
                    <div class="aspect-4/3 w-16 border border-black/20">
                        <img class="w-full h-full object-cover" src="<?php echo get_template_directory_uri() . '/lib/images/placeholder/'.esc_attr($item['image']); ?>'">
                    </div>
                    <div class="text-14 text-grey truncate overflow-hidden whitespace-nowrap grow-0 shrink min-w-0">
                        <?php echo esc_html($item['caption']); ?>
                    </div>
                </div>
                <button data-show="stage-plot-images-2" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Edit</button>
            </div>
        <?php endforeach; ?>

    </div>



</div>