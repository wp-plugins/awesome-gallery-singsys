<?php

    class singsys_gallery{
        var $gallery_id = -1;

        function __construct($id=-1) {
            $this->gallery_id = $id;
        }

        function get_defaultID() {
            global $wpdb;
            $tb_pre = $wpdb->prefix;
            $sql = "select id from {$tb_pre}singsys_gallery";
            return $wpdb->get_var($sql);
        }
        
        function get_items() {
            global $wpdb;
            $tb_pre = $wpdb->prefix;
            $sql = "select * from {$tb_pre}singsys_items where gallery_id={$this->gallery_id}";
            return $wpdb->get_results($sql);
        }

        function get_gallery() {
            global $wpdb;
            $tb_pre = $wpdb->prefix;
            $sql = "select * from {$tb_pre}singsys_gallery where id={$this->gallery_id}";
            return $wpdb->get_row($sql);

            //return $this->gallery_id;
        }

        function get_gallery_list() {
            global $wpdb;
            $tb_pre = $wpdb->prefix;
            $sql = "select * from {$tb_pre}singsys_gallery";
            return $wpdb->get_results($sql);
        }

        function count_items($id=-1) {
            global $wpdb;
            $tb_pre = $wpdb->prefix;
            $sql = "select count(id) as total from {$tb_pre}singsys_items where gallery_id={$id}";
            $result = $wpdb->get_row($sql);
            return $result->total;
        }

    }
