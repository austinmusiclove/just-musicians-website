<section class="overflow-hidden bg-white py-16 md:py-24">
    <div class="container relative isolate">
        <div class="max-w-6xl">

            <div class="max-w-2xl">
                <?php if (!empty($args['eyebrow'])) { ?>
                    <p class="font-sun-motter text-13 uppercase tracking-wide text-brown-dark-3 mb-3"><?php echo esc_html($args['eyebrow']); ?></p>
                <?php } ?>
                <h2 class="font-sun-motter text-navy text-28 md:text-40 mb-4"><?php echo esc_html($args['heading']); ?></h2>
                <p class="text-16 md:text-18 text-brown-dark-3 mb-8 md:mb-10"><?php echo esc_html($args['sub_heading']); ?></p>
            </div>

            <dl class="grid grid-cols-1 gap-x-8 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($args['features'] as $feature) { ?>
                    <div>
                        <dt class="font-sun-motter text-20 text-navy mb-2"><?php echo esc_html($feature['name']); ?></dt>
                        <dd class="text-16 text-brown-dark-3"><?php echo esc_html($feature['description']); ?></dd>
                    </div>
                <?php } ?>
            </dl>

        </div>
    </div>
</section>
