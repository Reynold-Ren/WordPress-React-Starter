<?php
/**
 * 創建後台介面
 */

class WRS_Create_Admin_Page
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'create_admin_menu']);
    }

    public function create_admin_menu()
    {
        $capability = 'manage_options';
        $slug = 'wrs-settings';

        add_menu_page(
            __('WordPress React Starter', 'WordPress React Starter'),
            __('WordPress React Starter', 'WordPress React Starter'),
            $capability,
            $slug,
            [$this, 'menu_page_template'],
            'dashicons-editor-code'
        );
    }

    public function menu_page_template()
    {
        echo '<div class="wrap"><div id="wrs-admin-app"></div></div>';
    }
}

new WRS_Create_Admin_Page();
