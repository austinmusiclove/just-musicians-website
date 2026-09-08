<div x-data="{ showShareModal: false, shareEmail: '', shareAccessType: 'view' }">

    <button class="bg-white hover:bg-yellow border border-black/20 px-3 py-2 rounded-sm font-sun-motter text-14 w-fit whitespace-nowrap inline-block" type="button"
        x-on:click="showShareModal = true; shareEmail = ''; $dispatch('load-share-access')"
    >
        <?php echo esc_html($args['label']); ?>
    </button>

    <?php echo get_template_part('template-parts/access/share-modal', '', [
        'heading'      => $args['heading'],
        'subject_id'   => $args['subject_id'],
        'subject_type' => $args['subject_type'],
        'permalink'    => isset($args['permalink']) ? $args['permalink'] : '',
    ]); ?>

</div>
