<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <p>Welcome to the Event Manager. Use this page to manage event operations.</p>

    <style>
        .tablenav .pagination-links a,
        .tablenav .pagination-links span {
            display: inline-block;
            padding: 3px 6px;
            margin: 0 2px;
            border: 1px solid #c3c4c7;
            border-radius: 3px;
            text-decoration: none;
            min-width: 30px;
            text-align: center;
        }
        .tablenav .pagination-links a:hover {
            background: #f0f0f1;
        }
        .tablenav .pagination-links span.current {
            background: #2271b1;
            border-color: #2271b1;
            color: #fff;
        }
        .tablenav .bulk-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }
    </style>

    <?php if ( ! empty( $error_msg ) ) : ?>
        <div class="notice notice-error" style="margin: 20px 0;">
            <p><strong>Error:</strong> <?php echo esc_html( $error_msg ); ?></p>
        </div>
    <?php elseif ( ! empty( $bulk_result ) ) : ?>
        <?php if ( $bulk_result['type'] === 'success' ) : ?>
            <div class="notice notice-success" style="margin: 20px 0;">
                <p><?php echo esc_html( $bulk_result['rejected'] ); ?> transactions rejected successfully.</p>
            </div>
        <?php elseif ( $bulk_result['type'] === 'partial' ) : ?>
            <div class="notice notice-warning" style="margin: 20px 0;">
                <p><?php echo esc_html( $bulk_result['rejected'] ); ?> of <?php echo esc_html( $bulk_result['total'] ); ?> transactions rejected. <?php echo esc_html( $bulk_result['failed'] ); ?> failed.</p>
            </div>
        <?php endif; ?>
    <?php else : ?>
        <p style="font-size: 1.2em; font-weight: bold; color: #2271b1;">
            Total: <?php echo esc_html( $total_count ); ?>
        </p>
    <?php endif; ?>

    <?php if ( empty( $error_msg ) ) : ?>
        <form method="post" action="">
            <?php wp_nonce_field( 'em_bulk_reject' ); ?>
            <div class="tablenav top" style="margin-bottom: 10px;">
                <div class="alignleft actions bulkactions">
                    <select name="em_bulk_action">
                        <option value="">Bulk Actions</option>
                        <option value="reject">Reject</option>
                    </select>
                    <button type="submit" class="button action">Apply</button>
                </div>
                <?php if ( $total_pages > 0 ) : ?>
                    <div class="tablenav-pages">
                        <span class="displaying-num"><?php echo esc_html( number_format_i18n( $total_count ) ); ?> items</span>
                    </div>
                <?php endif; ?>
            </div>

            <?php include plugin_dir_path( __FILE__ ) . 'components/dashboard/staged-transactions-table.php'; ?>

            <?php if ( $total_pages > 1 ) : ?>
                <div class="tablenav bottom" style="margin-top: 20px;">
                    <div class="tablenav-pages">
                        <span class="displaying-num"><?php echo esc_html( number_format_i18n( $total_count ) ); ?> items</span>
                        <span class="pagination-links">
                            <?php
                            $base_url = admin_url( 'admin.php?page=event-manager' );
                            $pagination_args = array(
                                'base'   => add_query_arg( 'paged', '%#%', $base_url ),
                                'format' => '',
                                'current' => $current_page,
                                'total' => $total_pages,
                                'prev_text' => '&lsaquo;',
                                'next_text' => '&rsaquo;',
                                'type' => 'plain',
                            );
                            echo paginate_links( $pagination_args );
                            ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>
        </form>
    <?php endif; ?>
</div>
