<div class="col lg:col-span-3 relative sticky top-24 flex flex-col gap-8">

    <!-- Contact and socials -->
    <div class="flex flex-col gap-4">

        <div class="sidebar-module border border-black/20 rounded overflow-hidden bg-white">
            <h3 class="bg-yellow-50 font-bold py-2 px-3">Contact Information</h3>
            <div class="p-4 flex flex-col gap-4">


                <!-- Contact info -->
                <?php echo get_template_part('template-parts/venues/parts/contact-info', '', []); ?>

                <!-- Socials and links -->
                <?php echo get_template_part('template-parts/venues/parts/social-links', '', []); ?>


            </div>
        </div>
    </div>

    <!-- Musician pay -->
    <?php //echo get_template_part('template-parts/venues/parts/musician-pay', '', []); ?>

    <!-- Inquiry Form -->
    <div class="">
        <?php echo get_template_part('template-parts/inquiries/inquiry-sidebar', '', array(
            'button_color' => 'bg-navy text-white hover:bg-yellow hover:text-black',
            'responsive' => 'lg:border-none lg:p-0'
        )); ?>
    </div>

</div>
