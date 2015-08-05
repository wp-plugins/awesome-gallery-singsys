<?php

    /*
     * Plugin Name: Wordpress Awesome Gallery - Singsys
     * Plugin URI: http://www.plugin.singsys.com/singsys-awesome-gallery/
     * Description: Responsive Gallery
     * Version: 1.0
     * Author: Singsys Software Services Pte Ltd
     * Author URI: http://www.singsys.com
     * License: License: GPLv2 or later
     * License URI: http://www.gnu.org/licenses/gpl-2.0.html
     */



    global $singsys_gallery_version;
    $singsys_gallery_version = '1.0';

    add_action('admin_menu', 'singsys_gallery_admin_menu');
    
    /*
     * Add singsys to menu
     */
    function singsys_gallery_admin_menu() {
        add_menu_page('Wordpress Awesome Gallery', 'Gallery', 'manage_options', 'singsys_gallery', 'singsys_gallery_dashboard', 'dashicons-camera', 10);
        add_submenu_page('singsys_gallery', 'Add Or Update Slider', 'Add Gallery', 'manage_options', 'singsys_new_gallery', 'singsys_add_new_gallery');
    }

    function singsys_delete_gallery() {
        if (isset($_REQUEST['id'])) {
            // delete Here..
        } else {
            wp_redirect(admin_url() . 'admin.php?page=singsys_gallery');
        }
    }

    /*
     * Save gallery form hooks
     */
    add_action('admin_post_singsys_save_gallery', 'singsys_save_gallery');

    function singsys_save_gallery() {
        
        global $wpdb;
        $tb_pre = $wpdb->prefix;

        // Check Slider is Exists
        $gallery_id = $_POST['singsys_gallery_id'];
        //$gallery_id = 1;
        $sql_check = "select id from {$tb_pre}singsys_gallery where id={$gallery_id}";
        $exist_result = $wpdb->get_row($sql_check);

        // Gallery Data
        $gallery_name = ($_POST['gallery_name'] == '') ? 'Gallery' : $_POST['gallery_name'];
        $gallery_option = (is_array($_POST['singsys_gallery'])) ? serialize($_POST['singsys_gallery']) : $_POST['singsys_gallery'];

        $gallery_data = array('title' => $gallery_name, 'option' => $gallery_option);
        $db_gallery_id = -1;



        if ($exist_result) {
            // Here You can Update
            $db_gallery_id = $gallery_id;
            $wpdb->update("{$tb_pre}singsys_gallery", $gallery_data, array('id' => $db_gallery_id));
        } else {

            $wpdb->insert("{$tb_pre}singsys_gallery", $gallery_data);
            $db_gallery_id = $wpdb->insert_id;

            // Here You can insert
        }

        // Add Slider Items
        if (isset($_POST['gallery_item_id'])) {
            $items = $_POST['gallery_item_id'];
            foreach ($items as $k => $v) {

                $item_data = array(
                    'gallery_id' => $db_gallery_id,
                    'media_id' => isset($_POST['gallery_media_id'][$k]) ? $_POST['gallery_media_id'][$k] : 0,
                    'title' => isset($_POST['gallery_title'][$k]) ? $_POST['gallery_title'][$k] : '',
                    'link' => isset($_POST['gallery_item_link'][$k]) ? $_POST['gallery_item_link'][$k] : '',
                    'description' => isset($_POST['gallery_item_description'][$k]) ? $_POST['gallery_item_description'][$k] : ''
                );
                
                $sql_check = "select id from {$tb_pre}singsys_items where id={$v}";

                $exist_result = $wpdb->get_row($sql_check);
                if ($exist_result) {
                    /*
                     * updating gallery
                     */
                    $item_id = $exist_result->id;
                    $wpdb->update("{$tb_pre}singsys_items", $item_data, array('id' => $item_id));
                } else {
                    /*
                     * inserting new item
                     */
                    $wpdb->insert("{$tb_pre}singsys_items", $item_data);
                }
            }
        }

        wp_redirect(admin_url() . 'admin.php?page=singsys_new_gallery&id=' . $db_gallery_id);
    }

    function singsys_add_new_gallery() {
        wp_enqueue_media();
        include(dirname(__file__).'/page/new.php');
    }

    function singsys_gallery_dashboard() {
        // Delete  slider 
        if (isset($_REQUEST['delete'])) {
            global $wpdb;
            $prefix = $wpdb->prefix;
            $gallery_id = $_REQUEST['delete'];
            $sql = "delete from {$prefix}singsys_gallery where id={$gallery_id}";
            $sql2 = "delete from {$prefix}singsys_items where gallery_id ={$gallery_id}";
            $wpdb->query($sql);
            $wpdb->query($sql2);
        }

        include(dirname(__file__) . '/page/index.php');
        //echo 'Test';
    }

    // On Active Slider
    register_activation_hook(__FILE__, 'singsys_gallery_install');

    // Deactive Slider
    register_deactivation_hook(__FILE__, 'singsys_gallery_deactivation');

    function singsys_gallery_deactivation() {
        // Clear the permalinks to remove our post type's rules
        flush_rewrite_rules();
    }

    function singsys_gallery_install() {
        global $wpdb;
        global $singsys_gallery_version;

        $slider_table = $wpdb->prefix . 'singsys_gallery';
        $slider_item_table = $wpdb->prefix . 'singsys_items';

        $charset_collate = $wpdb->get_charset_collate();



        $t1 = "CREATE TABLE IF NOT EXISTS `" . $slider_table . "` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `title` varchar(100) NOT NULL,
            `option` longtext NOT NULL,
            `status` tinyint(1) NOT NULL,
            `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

        $t2 = "CREATE TABLE IF NOT EXISTS `" . $slider_item_table . "` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `gallery_id` bigint(20) NOT NULL,
            `media_id` bigint(20) NOT NULL,
            `title` varchar(200) NOT NULL,
            `link` varchar(200) NOT NULL,
            `description` text NOT NULL,
            `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
        //$sql = $t1.$t2;

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($t1);
        dbDelta($t2);

        add_option('singsys_gallery_version', $singsys_gallery_version);
    }
    
    // Delete Slider Item Using Ajax Hooks
    add_action('wp_ajax_delete_singsys_gallery_item', 'remove_singsys_gallery_item');

    function remove_singsys_gallery_item() {
        $id = $_POST['id'];
        //exit($id);
        global $wpdb;
        $sql = "delete from  {$wpdb->prefix}singsys_items where id ={$id}";
        if ($wpdb->query($sql)) {

            exit('true');
        } else {

            exit("Database Error." . $id);
        }
    }

    // Add Shortcode File
    include_once('gallery_shortcode.php');
    $singsys_gallery_shortcodes = new singsys_gallery_code;
