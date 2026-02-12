<div
    class="flex flex-row items-center gap-2"
    x-data="{
        copied: false,
        copyText() {
            const text = this.$refs.email.innerText.trim();
            navigator.clipboard.writeText(text).then(() => {
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            });
        }
    }"
>
    <span
        x-ref="email"
        class="text-14 whitespace-nowrap overflow-hidden text-ellipsis block"
        <?php if ($args['x-text']) { echo 'x-text="' . $args['x-text'] . '"'; } ?>
    >
        <?php if ($args['text']) { echo $args['text']; } ?>
    </span>

    <div class="relative flex items-center">
        <button type="button" @click="copyText()" class="group">
            <div class="relative inline-flex group">

                <!-- Copy icon -->
                <img
                    class="opacity-40 h-6 cursor-pointer hover:opacity-100"
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
