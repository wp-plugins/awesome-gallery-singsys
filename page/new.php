<link href="<?php echo $url = plugins_url('../css/admin_style.css',__FILE__); ?>" rel="stylesheet" media="all">
<script type="text/javascript">var $close_img = '<?php echo plugins_url('../img/close.png',__FILE__); ?>';</script>
<?php
    if (isset($_REQUEST['id'])) {
        $gallery_id = $_REQUEST['id'];
        $class_file = __DIR__.'/../admin_class.php';
        if (file_exists($class_file)) {
            include_once($class_file);
            $galleryObj = new singsys_gallery($_REQUEST['id']);

            $gallery = $galleryObj->get_gallery();
            $gallery_option = unserialize($gallery->option);
        }
        $status = 'Update';
    } else {
        $status = 'Add';
        $gallery_id = false;
    }
?>

<div class="wrap">
    <h2>
        <?php echo $status; ?> Gallery
        <?php 
            if($status == 'Update'){
                echo '<a class="add-new-h2" href="?page=singsys_new_gallery">Add New</a>';
            }
        ?>
    </h2>
    <br>
    <form method="post" action="admin-post.php">
        <input type="hidden" name="action" value="singsys_save_gallery" id="" />
        <div id="poststuff" class="singsys_gallery_container">
            <div class="singsys_wrap">
                <input type="hidden" name="singsys_gallery_id" value="<?php echo ($gallery_id) ? $gallery_id : -1 ?>" />
                <div id="side-sortables" class="singsys_content meta-box-sortables ui-sortable">
                    <div class="sys_head" style="margin-bottom: 15px;">
                        <div id="titlediv">
                            <div id="titlewrap">
                                <label for="title" id="title-prompt-text" class="screen-reader-text">Enter title here</label>
                                <input name="gallery_name" value="<?php echo ($gallery_id) ? $gallery->title : ''; ?>" id="title" spellcheck="true" autocomplete="off" placeholder="Gallery Name" type="text" size="30" />
                            </div>
                            <div class="inside">
                                <div id="edit-slug-box" style="padding: 0 2px;">
                                    <strong>Gallery Name: </strong><i>The name is how it appears on your site.</i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button class="button-secondary" id="upload_button" style="margin-bottom: 5px;"><span class="dashicons dashicons-format-image" style="font-size: 15px; margin-top: 7px;"></span> Add Image</button>
                        <div class="clr"></div>
                    </div>
                    <div class="postbox " id="gallery-items">
                        <div title="Click to toggle" class="handlediv"><br></div>
                        <h3 class="hndle ui-sortable-handle"><span>Images</span></h3>
                        <div class="inside">
                            <?php
                                if ($gallery_id) {
                                    $items = $galleryObj->get_items();
                                    if(!empty($items)){
                                        foreach ($items as $item) { ?>
        
                                            <div class="sys_content">
                                                <img src="<?php echo plugins_url('../img/close.png',__FILE__); ?>" alt="close" class="sys_close" id="remove_gallery_item"/>
                                                <input type="hidden" value="<?php echo $item->id; ?>" name="gallery_item_id[]" id="gallery_item_id">
                                                <input type="hidden" name="gallery_media_id[]" value="<?php echo $item->media_id; ?>" id="gallery_media_id" />
                                                <div class="img_wrap">
                                                    <?php echo wp_get_attachment_image($item->media_id,'thumbnail'); ?>
                                                </div>
                                                <div class="img_field">
                                                    <ul>
                                                        <li><label class="sys-cl-30">Title: </label> <input type="text" value="<?php echo $item->title; ?>"  name="gallery_title[]" id="gallery_title"/></li>
                                                        <li><label class="sys-cl-30">Links: </label> <input type="text" value="<?php echo $item->link; ?>"  name="gallery_item_link[]" id="gallery_item_link"/></li>
                                                        <li><label class="sys-cl-30">Discription:</label><textarea name="gallery_item_description[]" id="gallery_item_description"><?php echo $item->description; ?></textarea></li>
                                                    </ul>
        
                                                </div>
                                                <div class="clr"></div>
                                            </div>
                                            <?php
                                        }
                                    }else{
                                        echo '<p>No image(s) found. Click `Add Images` to add images to your gallery.</p>';
                                    }
                                }else{
                                    echo '<p>No image(s) found. Start adding images to your gallery.</p>';
                                }
                            ?> 
                        </div>
                    </div>
                </div>
                <div id="side-sortables" class="meta-box-sortables ui-sortable singsys_settings">
                    <div class="postbox " id="postimagediv">
                        <div title="Click to toggle" class="handlediv"><br></div>
                        <h3 class="hndle ui-sortable-handle"><span>Publish</span></h3>
                        <?php if(!empty($gallery)){ ?>
                            <div class="inside">
                                <div id="submitgallery" class="submitbox">
                                    <div id="minor-publishing">
                                        <div class="misc-pub-section curtime misc-pub-curtime" style="width: 100%;padding: 0;">
                                            <span id="timestamp" style="width: 100%;">
                                                Published on: <b><?php echo $gallery->time; ?></b>
                                            </span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div id="major-publishing-actions">
                            <?php if(!empty($gallery)){ ?>
                                <div id="delete-action">
                                    <a href="?page=singsys_gallery&delete=<?php echo $gallery_id; ?>" class="submitdelete deletion">Move to Trash</a>
                                </div>
                            <?php } ?>
                            <div id="publishing-action">
                                <span class="spinner"></span>
                                <input type="submit" value="<?php echo $status; ?>" class="button-primary pull-right"/>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <!-- Option widgets -->
                    <!-- End Option -->
                    <?php if(0){ ?>
                        <!-- Option widgets -->
                        <div class="option_wrap">
                            <div class="sys_head"><h3>Gallery Options</h3> </div>
                            <div class="sys_content">
                                <ul class="option_list">
                                    <li><label>Status</label>
                                        <p>Actice/ de-active</p>
                                        <select name="sys_slider[slider_status]" id="slider_status">
                                            <option value="y" <?php echo ($gallery_option) ? ($gallery_option['slider_status'] == 'y') ? '  selected="selected"' : '' : ''; ?> >Yes</option>
                                            <option value="n" <?php echo ($gallery_option) ? ($gallery_option['slider_status'] == 'n') ? '  selected="selected"' : '' : ''; ?>>No</option>
                                        </select>      
                                    </li>
                                    <li><label>Items</label>
                                        <p>This variable allows you to set the maximum amount of items displayed at a time with the widest browser width</p>
                                        <input type="number" id="items" value="<?php echo ($gallery_option) ? $gallery_option['items'] : 5; ?>" name="sys_slider[items]"/>
    
                                    </li>
                                    <li><label>Items Desktop</label>
                                        <p>between 1000px and 901px</p>
                                        <input type="number" id="itemsDesktop" value="<?php echo ($gallery_option) ? $gallery_option['itemsDesktop'] : 5; ?>" name="sys_slider[itemsDesktop]"/>
                                    </li>
                                    <li><label>Items Small Desktop</label>
                                        <p>betweem 900px and 601px</p>
                                        <input type="number" id="itemsDesktopSmall" value="<?php echo ($gallery_option) ? $gallery_option['itemsDesktopSmall'] : 3; ?>" name="sys_slider[itemsDesktopSmall]"/>
    
                                    </li>
                                    <li><label>Items Tablet</label>
                                        <p>between 600 and 479</p>
                                        <input type="number" id="itemsTablet" value="<?php echo ($gallery_option) ? $gallery_option['itemsTablet'] : 3; ?>" name="sys_slider[itemsTablet]"/>
                                    </li>
                                    <li>
                                        <label>Items Mobile</label>
                                        <p>between 480 and 0</p>
                                        <input type="number" id="items" value="<?php echo ($gallery_option) ? $gallery_option['itemsMobile'] : 1; ?>" name="sys_slider[itemsMobile]"/>
                                    </li>
                                    <li>
                                        <label>Single Item</label>
                                        <p>Display only one item</p>
                                        <select name="sys_slider[singleItem]" id="singleItem">
                                            <option value="yes" <?php echo ($gallery_option) ? ($gallery_option['singleItem'] == 'yes') ? '  selected="selected"' : '' : ''; ?> >Yes</option>
                                            <option value="no" <?php echo ($gallery_option) ? ($gallery_option['singleItem'] == 'no') ? '  selected="selected"' : '' : ''; ?>>No</option>
                                        </select>      
                                    </li>
                                    <li>
                                        <label>Slide Speed</label>
                                        <p>Slide speed in milliseconds</p>
                                        <input type="number" id="slideSpeed" value="<?php echo ($gallery_option) ? $gallery_option['slideSpeed'] : 200; ?>" name="sys_slider[slideSpeed]"/>
                                    </li>
                                    <li>
                                        <label>Pagination Speed</label>
                                        <p>Pagination speed in milliseconds</p>
                                        <input type="number" id="paginationSpeed" value="<?php echo ($gallery_option) ? $gallery_option['paginationSpeed'] : 800; ?>" name="sys_slider[paginationSpeed]"/>
                                    </li>
                                    <li>
                                        <label>Rewind Speed</label>
                                        <p>Rewind speed in milliseconds</p>
                                        <input type="number" id="rewindSpeed" value="<?php echo ($gallery_option) ? $gallery_option['rewindSpeed'] : 1000; ?>" name="sys_slider[rewindSpeed]"/>
                                    </li>
                                    <li>
                                        <label>Auto Play</label>
                                        <p>Change to any integrer for example autoPlay : 5000 to play every 5 seconds. If you set autoPlay: true default speed will be 5 seconds.</p>
                                        <input type="text" id="autoPlay" value="<?php echo ($gallery_option) ? $gallery_option['autoPlay'] : 'false'; ?>" name="sys_slider[autoPlay]"/>
                                    </li>
                                    <li>
                                        <label>Stop On Hover</label>
                                        <p>Stop autoplay on mouse hover</p>
                                        <select name="sys_slider[stopOnHover]" id="stopOnHover">                                    
                                            <option value="no" <?php echo ($gallery_option) ? ($gallery_option['stopOnHover'] == 'no') ? '  selected="selected"' : '' : ''; ?>>No</option>
                                            <option value="yes" <?php echo ($gallery_option) ? ($gallery_option['stopOnHover'] == 'yes') ? '  selected="selected"' : '' : ''; ?>>Yes</option>
                                        </select>      
                                    </li>
                                    <li>
                                        <label>Navigation</label>
                                        <p>Display "next" and "prev" buttons.</p>
                                        <select name="sys_slider[navigation]" id="navigation">                                    
                                            <option value="no" <?php echo ($gallery_option) ? ($gallery_option['navigation'] == 'no') ? '  selected="selected"' : '' : ''; ?>>No</option>
                                            <option value="yes" <?php echo ($gallery_option) ? ($gallery_option['navigation'] == 'yes') ? '  selected="selected"' : '' : ''; ?>>Yes</option>
                                        </select>      
                                    </li>
                                    <li>
                                        <label>Pagination</label>
                                        <p>Show pagination.</p>
                                        <select name="sys_slider[pagination]" id="pagination">
                                            <option value="yes" <?php echo ($gallery_option) ? ($gallery_option['pagination'] == 'yes') ? '  selected="selected"' : '' : ''; ?>>Yes</option>
                                            <option value="no" <?php echo ($gallery_option) ? ($gallery_option['pagination'] == 'no') ? '  selected="selected"' : '' : ''; ?>>No</option>
                                        </select>      
                                    </li>
                                    <li>
                                        <label>Pagination Numbers</label>
                                        <p>Show numbers inside pagination buttons</p>
                                        <select name="sys_slider[paginationNumbers]" id="paginationNumbers">
                                            <option value="yes" <?php echo ($gallery_option) ? ($gallery_option['paginationNumbers'] == 'yes') ? '  selected="selected"' : '' : ''; ?>>Yes</option>
                                            <option value="no" <?php echo ($gallery_option) ? ($gallery_option['paginationNumbers'] == 'no') ? '  selected="selected"' : '' : ''; ?>>No</option>
                                        </select>      
                                    </li>
                                    <li>
                                        <label>Responsive</label>
                                        <p>You can use Owl Carousel on desktop-only websites too! Just change that to "false" to disable resposive capabilities</p>
                                        <select name="sys_slider[responsive]" id="responsive">
                                            <option value="yes" <?php echo ($gallery_option) ? ($gallery_option['responsive'] == 'yes') ? '  selected="selected"' : '' : ''; ?>>Yes</option>
                                            <option value="no" <?php echo ($gallery_option) ? ($gallery_option['responsive'] == 'no') ? '  selected="selected"' : '' : ''; ?>>No</option>
                                        </select>      
                                    </li>
                                    <li>
                                        <label>Responsive Base Width</label>
                                        <p>Owl Carousel check window for browser width changes. You can use any other jQuery element to check width changes for example ".owl-demo". Owl will change only if ".owl-demo" get new width.</p>
                                        <input type="text" id="responsiveBaseWidth" value="<?php echo ($gallery_option) ? $gallery_option['responsiveBaseWidth'] : 'window'; ?>" name="sys_slider[responsiveBaseWidth]"/>
                                    </li>
                                    <li>
                                        <label>Base Class</label>
                                        <p>Automaticly added class for base CSS styles. Best not to change it if you don't need to.</p>
                                        <input type="text" id="baseClass" value="<?php echo ($gallery_option) ? $gallery_option['baseClass'] : 'owl-carousel'; ?>" name="sys_slider[baseClass]"/>
                                    </li>
                                </ul>
                                <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                        </div>
                        <!-- End Option -->
                    <?php } ?>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">jQuery(function(){jQuery(".sortable").sortable({ handle: '.handle' });});</script>
<script type="text/javascript" src="<?php echo plugins_url('../script/media.js',__FILE__); ?>"></script>