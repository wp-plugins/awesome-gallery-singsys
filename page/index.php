<?php
    $dir = plugin_dir_path(__DIR__);
    $class_file = $dir . 'admin_class.php';
    if (file_exists($class_file)) {
        include_once($class_file);

        $galleryObj = new singsys_gallery;
        $gallery = $galleryObj->get_gallery_list();
    }
    ?>
    
    <div class="wrap">
        <h2>
            All Images
            <a class="add-new-h2" href="?page=singsys_new_gallery">Add New</a>
        </h2>
        <ul class="subsubsub">
            <li class="all"><a class="current" href="admin.php?page=singsys_gallery">All <span class="count">(<?php echo count($gallery); ?>)</span></a></li>
        </ul>
        <?php 
            $columns = '<tr>
                <th class="manage-column column-cb check-column"><input type="checkbox" id="cb-select-all-1"></th>
                <th>Name</th>
                <th>Shortcode</th>
                <th>No. of Slides</th>
            </tr>';
        ?>
        <table class="wp-list-table widefat fixed striped sliders">
            <thead><?php _e($columns); ?></thead>
            <tbody>
                <?php if(!empty($gallery)): ?>
                    <?php foreach ($gallery as $list): ?>
                        <tr>
                            <td></td>
                            <td class="slider-title slider-title column-title">
                                <strong><a title="Edit “<?php echo $list->title; ?>”" href="?page=singsys_new_gallery&id=<?php echo $list->id; ?>" class="row-title"><?php echo $list->title; ?></a></strong>
                                <div class="locked-info">
                                    <span class="locked-avatar"></span> 
                                    <span class="locked-text"></span>
                                </div>
                                <div class="row-actions">
                                    <span class="edit">
                                        <a title="Edit this item" href="?page=singsys_new_gallery&id=<?php echo $list->id; ?>">Edit</a> | 
                                    </span>
                                    <span class="trash">
                                        <a href="?page=singsys_gallery&delete=<?php echo $list->id; ?>" title="Move this item to the Trash" class="submitdelete">Trash</a>
                                    </span>
                                </div>
                            </td>
                            <th>[singsys_gallery id="<?php echo $list->id; ?>"]</th>
                            <td>(<?php echo $galleryObj->count_items($list->id); ?>) <i>slides</i></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot><?php _e($columns); ?></tfoot>
        </table>

    </div>

