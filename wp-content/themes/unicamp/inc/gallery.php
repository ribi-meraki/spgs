<?php
/**
 * Gallery Meta Box Configuration
 * Add this to your theme's functions.php or a custom plugin
 */

add_filter( 'rwmb_meta_boxes', 'spgs_gallery_meta_boxes' );

function spgs_gallery_meta_boxes( $meta_boxes ){
    $meta_boxes[] = [
        'title'      => 'Gallery Management',
        'id'         => 'spgs-gallery-meta-box',
        'context'    => 'normal',
        'priority'   => 'high',
        'post_types' => ['page'],
        // 'include'    => [
        //     'ID' => [8238], 
        // ],
        'fields' => [
            // [
            //     'id'   => 'gallery_video_section',
            //     'name' => 'Video Section',
            //     'type' => 'group',
            //     'clone' => false,
            //     'fields' => [
            //         [
            //             'id'   => 'video_url',
            //             'name' => 'Video URL',
            //             'type' => 'url',
            //             'desc' => 'YouTube or Vimeo video URL',
            //             'std'  => 'https://www.youtube.com/watch?v=-5d5V0oiCD0',
            //         ],
            //         [
            //             'id'   => 'video_thumbnail',
            //             'name' => 'Video Thumbnail',
            //             'type' => 'image_advanced',
            //             'max_file_uploads' => 1,
            //             'desc' => 'Upload video thumbnail image',
            //         ],
            //     ],
            // ],
            [
                'id'   => 'gallery_categories',
                'name' => 'Gallery Categories',
                'type' => 'group',
                'clone' => true,
                'sort_clone' => true,
                'collapsible' => true,
                'default_state' => 'collapsed',
                'add_button' => 'Add Gallery Category',
                'group_title' => array('field' => 'category_title'),
                'fields' => [
                    [
                        'id'   => 'category_title',
                        'name' => 'Category Title',
                        'type' => 'text',
                        'placeholder' => 'e.g., Entrance, First School, etc.',
                    ],
                    [
                        'id'   => 'category_slug',
                        'name' => 'Category Slug',
                        'type' => 'text',
                        'desc' => 'Used for CSS classes and JavaScript (no spaces, lowercase)',
                        'placeholder' => 'e.g., entrance, first-school, etc.',
                    ],
                    [
                        'id'   => 'category_thumbnail',
                        'name' => 'Category Thumbnail',
                        'type' => 'image_advanced',
                        'max_file_uploads' => 1,
                        'desc' => 'Main thumbnail for this category',
                    ],
                    [
                        'id'   => 'category_images',
                        'name' => 'Gallery Images',
                        'type' => 'image_advanced',
                        'desc' => 'Upload all images for this gallery category',
                    ],
                ],
            ],
        ],
    ];

    return $meta_boxes;
}

/**
 * Register Gallery Post Type
 * Add this if you haven't already registered the gallery post type
 */
function spgs_register_gallery_post_type() {
    $args = array(
        'public'    => true,
        'label'     => 'Galleries',
        'labels'    => array(
            'name'          => 'Galleries',
            'singular_name' => 'Gallery',
            'add_new'       => 'Add New Gallery',
            'add_new_item'  => 'Add New Gallery',
            'edit_item'     => 'Edit Gallery',
            'new_item'      => 'New Gallery',
            'view_item'     => 'View Gallery',
            'search_items'  => 'Search Galleries',
        ),
        'supports'  => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-format-gallery',
        'show_in_rest' => true,
    );
    register_post_type('gallery', $args);
}
add_action('init', 'spgs_register_gallery_post_type');

/**
 * Enqueue LC Lightbox assets
 */
function spgs_enqueue_lightbox_assets() {
    // Register the assets
    wp_register_style(
        'lc-lightbox', 
        get_template_directory_uri() . '/assets/css/lc_lightbox.css',
        array(),
        '1.0.0'
    );
    
    wp_register_script(
        'lc-lightbox', 
        get_template_directory_uri() . '/assets/js/lc_lightbox.lite.js',
        array('jquery'),
        '1.0.0',
        true // Load in footer
    );
}
add_action('wp_enqueue_scripts', 'spgs_enqueue_lightbox_assets');

/**
 * Global variable to track if gallery shortcode is used
 */
$spgs_gallery_shortcode_used = false;
$spgs_gallery_current_id = null;

/**
 * Gallery Shortcode
 */
function spgs_gallery_shortcode($atts) {
    global $spgs_gallery_shortcode_used, $spgs_gallery_current_id;
    
    // Mark that the shortcode is being used
    $spgs_gallery_shortcode_used = true;
    
    // Enqueue the lightbox assets since shortcode is used
    wp_enqueue_style('lc-lightbox');
    wp_enqueue_script('lc-lightbox');
    
    $atts = shortcode_atts(array(
        'id' => 8238, // Default page ID
    ), $atts);
    
    // Store the gallery ID globally for the footer script
    $spgs_gallery_current_id = $atts['id'];
    
    // Get the page/post directly
    $post = get_post($atts['id']);
    
    if (!$post) {
        return '<p>Page not found.</p>';
    }
    
    // Get meta data
    $video_section = rwmb_meta('gallery_video_section', '', $atts['id']);
    $gallery_categories = rwmb_meta('gallery_categories', '', $atts['id']);
    
    ob_start();
    ?>
    
    <!-- Gallery Loader (deferred) -->
    <style>
    /* Gallery deferred loader (scoped to this template) */
    #gallery{display:none;}
    #gallery-loader{position:relative;width:100%;height:4px;background:rgba(0,0,0,0.08);overflow:hidden;}
    #gallery-loader .bar{position:absolute;left:-40%;top:0;width:40%;height:100%;background:#1e3a8a;animation:ytbar 1.2s cubic-bezier(0.4,0.0,0.2,1) infinite;}
    @keyframes ytbar{0%{left:-40%;}100%{left:100%;}}
    #gallery-loader.hidden{opacity:0;visibility:hidden;transition:opacity .3s ease;}
    </style>
    <div class="container" id="gallery-loader-container">
        <div class="row">
            <div class="col-12">
                <div id="gallery-loader" aria-label="Loading gallery" role="status">
                    <div class="bar"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Gallery Section -->
    <section class="rts-about-university rts-section-padding" id="gallery">
        <div class="container">
<!--            <div class="row">-->
<!--                <div class="rts-section">-->
<!--                    <div class="col-lg-4 col-md-5">-->
<!--                        <h3 class="rts-section-title"><?php //echo esc_html($post->post_title); ?></h3>-->
<!--                    </div>-->
<!--                    <div class="col-lg-8 col-md-7">-->
<!--                        <div class="rts-section-description">-->
<!--                            <p>Here we'll share visual material that helps bring the Harbour Drive vision to life:-->
<!--Please note: The renderings shared are initial design impressions. Interior designs and other elements may change as we incorporate feedback from stakeholders and consultants, but the overall quality and vision of the campus will remain uncompromised.-->
<!--                            </p>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
            <div class="row g-5">
                <?php  if (!empty($video_section['video_url'])): ?>
                <div class="col-12">
                    <div class="rts-video-section height-500 mb--50">
                        <a href="<?php echo esc_url($video_section['video_url']); ?>" class="rts-video-section-player popup-video video-btn">
                            <i class="fa-sharp fa-solid fa-play"></i>
                        </a>
                        <?php if (!empty($video_section['video_thumbnail'])): ?>
                            <?php $video_thumb = wp_get_attachment_image_src($video_section['video_thumbnail'][0], 'full'); ?>
                            <img src="<?php echo esc_url($video_thumb[0]); ?>" alt="video-bg">
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif;  ?>
                
                <?php if (!empty($gallery_categories)): ?>
                    <?php foreach ($gallery_categories as $category): ?>
                        <?php if (!empty($category['category_thumbnail'])): ?>
                            <?php $thumb = wp_get_attachment_image_src($category['category_thumbnail'][0], 'full'); ?>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="rts__program--item" style="background-image: url('<?php echo esc_url($thumb[0]); ?>');">
                                    <h5 class="rts__program--item--title"> <?php echo esc_attr($category['category_title']); ?></h5>
                                    <a href="#" class="rts-nbg-btn btn-arrow <?php echo esc_attr($category['category_slug']); ?>-gallery">
                                        View Gallery<span><i class="fas fa-arrow-right"></i></span>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <script>
    (function(){
        function revealGallery(){
            var loader = document.getElementById('gallery-loader');
            var loaderContainer = document.getElementById('gallery-loader-container');
            var gallery = document.getElementById('gallery');
            if (gallery){ gallery.style.display = 'block'; }
            if (loader){ loader.classList.add('hidden'); }
            setTimeout(function(){
                if (loaderContainer && loaderContainer.parentNode){ loaderContainer.parentNode.removeChild(loaderContainer); }
            }, 350);
        }
        // Run immediately if already loaded
        if (document.readyState === 'complete'){
            revealGallery();
        } else if (window.addEventListener){
            window.addEventListener('load', revealGallery);
        }
        // Safety fallback in case load never fires
        setTimeout(revealGallery, 5000);
    })();
    </script>
    <!-- End Gallery -->
    <!-- Hidden Gallery Images (off-screen but present for lightbox binding) -->
    <style>
    #gallery-hidden{position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;}
    </style>
    <div id="gallery-hidden">
        <?php if (!empty($gallery_categories)): ?>
            <?php foreach ($gallery_categories as $category): ?>
                <?php if (!empty($category['category_images'])): ?>
                    <?php foreach ($category['category_images'] as $image_id): ?>
                        <?php $image = wp_get_attachment_image_src($image_id, 'full'); ?>
                        <?php $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true); ?>
                        <a href="<?php echo esc_url($image[0]); ?>" 
                           class="elem <?php echo esc_attr($category['category_slug']); ?>" 
                           title="<?php echo esc_attr($image_alt); ?>" 
                           data-lcl-txt="<?php /* echo esc_attr(get_the_title($image_id)); */ ?>" 
                           data-lcl-author="SPGSI (Singapore)">
                            <img src="<?php echo esc_url($image[0]); ?>" alt="<?php echo esc_attr($image_alt); ?>">
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <?php
    return ob_get_clean();
}
add_shortcode('spgs_gallery', 'spgs_gallery_shortcode');

/**
 * Add gallery JavaScript to footer when shortcode is used
 */
function spgs_gallery_footer_script() {
    global $spgs_gallery_shortcode_used, $spgs_gallery_current_id;

    if (!$spgs_gallery_shortcode_used) return;

    $gallery_id = isset($spgs_gallery_current_id) ? $spgs_gallery_current_id : 8238;
    $gallery_categories = rwmb_meta('gallery_categories', '', $gallery_id);
    if (empty($gallery_categories)) return;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function(){

        if (typeof lc_lightbox !== 'function') return;

        const lightboxSettings = {
            wrap_class: 'lcl_fade_oc',
            gallery: true,
            thumb_attr: 'data-lcl-thumb',
            skin: 'minimal',
            radius: 0,
            padding: 0,
            border_w: 0,
            max_width: 900,
            max_height: 700,
            txt_toggle_cmd: true,
            nav_btn_pos: 'middle',
            slideshow_time: 5000,
            download: false,
            fullscreen: true,
            socials: false
        };

        // Initialize only each category separately
        <?php foreach($gallery_categories as $category): 
            $slug = esc_js($category['category_slug']);
        ?>
        lc_lightbox('.elem.<?php echo $slug; ?>', lightboxSettings);
        <?php endforeach; ?>

        // Attach click handlers to buttons
        document.querySelectorAll('.rts-nbg-btn.btn-arrow').forEach(function(button){
            button.addEventListener('click', function(e){
                e.preventDefault();
                const classList = Array.from(this.classList);
                const galleryClass = classList.find(cls => cls.endsWith('-gallery'));
                if (!galleryClass) return;

                const galleryType = galleryClass.replace('-gallery','');
                const target = document.querySelector('.elem.' + galleryType);

                if (target){
                    // Trigger click only on first element of that category
                    target.dispatchEvent(new MouseEvent('click', { bubbles: true, cancelable: true }));
                }
            });
        });

    });
    </script>
    <?php
}
add_action('wp_footer', 'spgs_gallery_footer_script');

/**
 * Helper function to get gallery by page ID
 */
function spgs_get_gallery_by_page_id($page_id = 8238) {
    return spgs_gallery_shortcode(array('id' => $page_id));
}

/**
 * Alternative method: Force enqueue on specific pages/templates
 * Uncomment this if you want to always load on certain pages
 */
/*
function spgs_force_enqueue_on_pages() {
    // Force enqueue on specific page IDs
    $gallery_pages = [8238]; // Add more page IDs as needed
    
    if (is_page($gallery_pages)) {
        wp_enqueue_style('lc-lightbox');
        wp_enqueue_script('lc-lightbox');
    }
}
add_action('wp_enqueue_scripts', 'spgs_force_enqueue_on_pages', 20);
*/
?>