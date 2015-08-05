<?php

    $class_file = __DIR__.'/admin_class.php';
    
    if(file_exists($class_file)){
        include_once($class_file);
    }
    
    class singsys_gallery_code extends singsys_gallery{
        
        public function __construct(){
            
            add_shortcode('singsys_gallery',array($this,'do_action'));
            add_action('wp_enqueue_scripts', array($this, 'script'));
			//add_action('wp_footer',array($this,'action_in_footer'),10000);
            
        }
        
        function do_action($arg=null){
            global $wpdb;
            ob_start();
            $prefix = $wpdb->prefix;
			
			if(empty($arg['id'])){
				$arg['id'] = $this->get_defaultID();
			}
            if(isset($arg['id'])){
                // Check Slider Is exists
				$id = $arg['id'];
				// $sql = "select";
          
				$this->gallery_id = $id;
               
				$gallery = $this->get_gallery();
				//echo $gallery->title;
				$items = $this->get_items();
				
				$option = unserialize($gallery->option);
				//isset($option['slider_status']) && $option['slider_status']=='y' 
				if(1){
					if(count($items)){
						//$this->script_in_footer();
						$title = explode(" ", $gallery->title);
						$first = $title[0];unset($title[0]);
						echo '<div class="row container-fluid">
							<header class="custom-header center">
								<h1 class="custom-title">
									'.$first.' <span>'.implode(" ",$title).'</span>'.'
								</h1>
							</header>
						</div>';
						echo '<div id="gallery-'.$id.'" class="singsys-gallery gallery-true gallery-'.$id.'">';
							foreach($items as $item){ ?>
								<div class="gallery col-md-3 col-sm-6">
									<div class="gallery_image">
										<?php echo  wp_get_attachment_image($item->media_id,'full') ;  ?>
										<div class="post-hover text-center">
											<div class="inside">
												<i class="fa fa-plus"></i>
												<span class="date"><?php echo date('j M Y',strtotime($item->time)); ?></span>
												<?php if(filter_var($item->link, FILTER_VALIDATE_URL)): ?>
													<h4><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></h4>
												<?php else:?>
													<h4><a href="javascript:;"><?php echo $item->title; ?></a></h4>
												<?php endif;?>
												<p><?php echo $item->description; ?></p>
											</div>
										</div>
									</div>
								</div> 
								<?php
							}
						echo '</div><div class="clear"></div>';
					}
				} 
                $html = ob_get_contents();
                ob_clean();
                ob_end_flush();
                return $html;
            }
        }
        
        function script(){
            global $post;
            wp_enqueue_script('jquery');
            if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'singsys_gallery') ) {
                wp_enqueue_style( 'singsys-gallery-style', plugins_url('/css/gallery-style.css',__FILE__), false,null,'all',true );
				wp_enqueue_script('singsys-gallery-plugin',plugins_url('/script/plugins.min.js',__FILE__), false,null,true);
				wp_enqueue_script('singsys-gallery-main',plugins_url('/script/main.min.js',__FILE__), false,null,true);
            }
        }
        
        function script_in_footer(){
            wp_enqueue_style( 'singsys-gallery-style', plugins_url('/css/gallery-style.css',__FILE__), false,null,'all',true );
            wp_enqueue_script('singsys-gallery-script',plugins_url('/script/main.min.js',__FILE__), false,null,true);
			wp_enqueue_script('singsys-gallery-script',plugins_url('/script/plugins.min.js',__FILE__), false,null,true);
        }
    }