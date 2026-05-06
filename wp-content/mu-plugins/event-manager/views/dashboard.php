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
    </style>

    <div class="card" style="max-width: 100%; margin-top: 20px;">
        <h2>Staged Transactions Overview</h2>
        <?php if ( ! empty( $error_msg ) ) : ?>
            <div class="notice notice-error inline" style="margin: 10px 0;">
                <p><strong>Error:</strong> <?php echo esc_html( $error_msg ); ?></p>
            </div>
        <?php else : ?>
            <p style="font-size: 1.2em; font-weight: bold; color: #2271b1;">
                Total: <?php echo esc_html( $total_count ); ?>
            </p>
        <?php endif; ?>
    </div>

    <?php include plugin_dir_path( __FILE__ ) . 'components/dashboard/staged-transactions-table.php'; ?>

    <?php if ( ! empty( $error_msg ) ) : ?>
    <?php elseif ( $total_pages > 1 ) : ?>
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
</div>
