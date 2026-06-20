<div>
    <h3 class="font-bold text-16 mb-2">Compensation Details</h3>
    <div class="flex items-center gap-1">
        <!--<img style="height: 1rem" src="<?php //echo get_template_directory_uri(); ?>/lib/images/icons/money-bill.svg" />-->
        <?php if ($args['budget']) { ?>
            <span class="text-16">Live Music Budget: $<?php echo esc_html(number_format((float) $args['budget'])); ?></span>
        <?php } else { ?>
            <span class="text-16 text-black/50">Budget not specified</span>
        <?php } ?>
    </div>
    <?php if ($args['compensation']) { ?>
        <span class="text-16"><?php echo esc_html($args['compensation']); ?></span>
    <?php } else { ?>
        <span class="text-16 text-black/50">Compensation not specified</span>
    <?php } ?>
</div>
