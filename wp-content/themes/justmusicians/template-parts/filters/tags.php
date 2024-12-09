<div class="border-b border-black/20 mb-6 pb-6 last:mb-0 last:pb-0 last:border-b-0">
    <h3 class="font-bold text-18 mb-3"><?php echo $args['title']; ?></h3>
    <div class="flex items-center gap-1 flex-wrap">
        <span class="text-12 font-bold px-2 py-0.5 rounded-full border border-black/20"><?php echo $args['tag_1']; ?></span>
        <span class="text-12 font-bold px-2 py-0.5 rounded-full border border-black/20"><?php echo $args['tag_2']; ?></span>
        <span class="text-12 font-bold px-2 py-0.5 rounded-full border border-black/20"><?php echo $args['tag_3']; ?></span>
        <span class="text-12 font-bold px-2 py-0.5 rounded-full border border-black/20"><?php echo $args['tag_4']; ?></span>
    </div>
    <?php if ($args['see_all'] == true) { ?>
        <button class="underline mt-3 inline-block text-14">see all</button>
    <?php } ?>
</div>