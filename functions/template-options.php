<?php
add_action('admin_enqueue_scripts', 'admin_template_view');
function admin_template_view( $hook ) {
    // Only run on the post editor screens
    if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
        return;
    }

    // Only for Pages
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if ( ! $screen || $screen->post_type !== 'page' ) {
        return;
    }

    // Load your admin JS
    wp_enqueue_script(
        'show_hide_bill_view',
        get_stylesheet_directory_uri() . '/js/template-option.js',
        array( 'jquery' ),
        null,
        true
    );

    // Robust post ID detection (works on new/edit screens)
    $post_id = 0;
    if ( isset( $GLOBALS['post'] ) && $GLOBALS['post'] instanceof WP_Post ) {
        $post_id = (int) $GLOBALS['post']->ID;
    } elseif ( isset( $_GET['post'] ) ) {
        $post_id = (int) $_GET['post'];
    } elseif ( isset( $_POST['post_ID'] ) ) {
        $post_id = (int) $_POST['post_ID'];
    }

    wp_localize_script(
        'show_hide_bill_view',
        'data',
        array(
            'postID'      => $post_id,
            'isFrontPage' => (int) get_option( 'page_on_front' ),
        )
    );
}


// Settings: Calendar ID
add_action('admin_init', 'add_calendar_id');
function add_calendar_id() {
    register_setting('general', 'calendar_id', 'esc_attr');
    add_settings_field(
        'calendar_id',
        '<label for="calendar_id">' . esc_html__('Calendar ID', 'calendar_id') . '</label>',
        'calendar_id_setting_html',
        'general'
    );
}
function calendar_id_setting_html() {
    $value = get_option('calendar_id', '');
    echo '<input type="text" id="calendar_id" name="calendar_id" value="' . esc_attr($value) . '" />';
}

// Settings: Breaker ID
add_action('admin_init', 'add_breaker_id');
function add_breaker_id() {
    register_setting('general', 'breaker_id', 'esc_attr');
    add_settings_field(
        'breaker_id',
        '<label for="breaker_id">' . esc_html__('Breaker ID', 'breaker_id') . '</label>',
        'breaker_id_setting_html',
        'general'
    );
}
function breaker_id_setting_html() {
    $value = get_option('breaker_id', '');
    echo '<input type="text" id="breaker_id" name="breaker_id" value="' . esc_attr($value) . '" />';
}

// Settings: Main Site URL
add_action('admin_init', 'add_main_site_url');
function add_main_site_url() {
    register_setting('general', 'main_site_url', 'esc_attr');
    add_settings_field(
        'main_site_url',
        '<label for="main_site_url">' . esc_html__('Main URL', 'main_site_url') . '</label>',
        'main_site_url_setting_html',
        'general'
    );
}
function main_site_url_setting_html() {
    $value = get_option('main_site_url', '');
    echo '<input type="text" id="main_site_url" name="main_site_url" value="' . esc_attr($value) . '" />';
}

// Settings: Knightbot Token
add_action('admin_init', 'knightbot');
function knightbot() {
    register_setting('general', 'knightbot', 'esc_attr');
    add_settings_field(
        'knightbot',
        '<label for="knightbot">' . esc_html__('Knightbot Token', 'knightbot') . '</label>',
        'knightbot_setting_html',
        'general'
    );
}
function knightbot_setting_html() {
    $value = get_option('knightbot', '');
    echo '<input type="text" id="knightbot" name="knightbot" value="' . esc_attr($value) . '" />';
}
