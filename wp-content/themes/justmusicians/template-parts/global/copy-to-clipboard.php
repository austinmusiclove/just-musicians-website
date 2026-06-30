<?php
$text              = $args['text'] ?? '';
$text_var          = $args['text_var'] ?? null;
$show_text         = $args['show_text'] ?? false;
$external_link     = $args['external_link'] ?? null;
$icon_size_classes = $args['icon_size_classes'] ?? 'h-6';
?>

<div
    class="flex flex-row items-center gap-2"
    x-data="{
        copied: false,
        copyText() {
            const text = '<?php echo clean_str_for_doublequotes($text); ?>';
            navigator.clipboard.writeText(text).then(() => {
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            });
        }
    }"
>

    <!-- Copy text -->
    <?php if ($show_text) { ?>
    <span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis block"
        <?php if ($text_var) { echo 'x-text="' . $text_var . '"'; } ?>
    >
        <?php echo $text; ?>
    </span>
    <?php } ?>

    <!-- External link -->
    <?php if ($external_link) { ?>
    <a href="<?php echo esc_url($external_link); ?>" target="_blank">
        <img class="opacity-40 hover:opacity-100 <?php echo $icon_size_classes; ?>" src="<?php echo get_template_directory_uri() . '/lib/images/icons/up-right-from-square.svg';?>" />
    </a>
    <?php } ?>

    <div class="relative flex items-center">
        <button type="button" x-on:click="copyText()" class="group">
            <div class="relative flex group">

                <!-- Copy icon -->
                <img
                    class="opacity-40 cursor-pointer hover:opacity-100 <?php echo $icon_size_classes; ?>"
                    src="<?php echo get_template_directory_uri() . '/lib/images/icons/copy.svg'; ?>"
                />

                <!-- Copy tooltip -->
                <div class="z-50 absolute bottom-full left-1/2 -translate-x-1/2 hidden group-hover:block hover:block">
                    <div class="mb-2 w-56 text-white bg-black px-4 py-3 text-14 rounded" x-text="copied ? 'Copied!' : 'Click to copy'"></div>
                </div>

            </div>
        </button>

    </div>

</div>
