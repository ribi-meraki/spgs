<?php
/**
 * Academics Section (Separate from Gallery)
 */

/**
 * Meta Box
 */
add_filter( 'rwmb_meta_boxes', 'spgs_academics_meta_boxes' );

function spgs_academics_meta_boxes( $meta_boxes ){

    $meta_boxes[] = [
        'title'      => 'Academics Section',
        'id'         => 'spgs-academics-meta-box',
        'post_types' => ['page'],
        
        'include'    => [
            'ID' => [10644],
        ],

        'context'    => 'normal',
        'priority'   => 'high',

        'fields' => [
            [
                'id'           => 'academics_items',
                'type'         => 'group',
                'name'         => 'Academics Items',
                'clone'        => true,
                'sort_clone'   => true,
                'collapsible'  => true,
                'group_title'  => ['field' => 'title'],
                'add_button'   => 'Add Item',
                'max_clone'    => 4, // LIMIT TO 4 ITEMS

                'fields' => [

                    [
                        'id'   => 'title',
                        'name' => 'Title',
                        'type' => 'text',
                    ],

                    [
                        'id'   => 'subtitle',
                        'name' => 'Subtitle',
                        'type' => 'text',
                        'desc' => 'Example: (Pre-KG to Grade 1)',
                    ],

                    [
                        'id'   => 'description',
                        'name' => 'Description',
                        'type' => 'textarea',
                    ],

                    [
                        'id'   => 'image',
                        'name' => 'Background Image',
                        'type' => 'image_advanced',
                        'max_file_uploads' => 1,
                    ],

                    [
                        'id'   => 'link',
                        'name' => 'Learn More URL',
                        //'type' => 'url',
                        'type' => 'text',
                    ],

                ],
            ],
        ],
    ];

    return $meta_boxes;
}


/**
 * Shortcode
 */
function spgs_academics_shortcode($atts){

    $atts = shortcode_atts([
        'id' => 10644,
    ], $atts);

    if ($atts['id'] != 10644) return '';

    $items = rwmb_meta('academics_items', '', $atts['id']);

    if (empty($items)) return '';

    ob_start();
    ?>

    <section class="spgs-academics-section rts-section-padding">
        <div class="container">
            <div class="row g-5">

                <?php foreach ($items as $item): ?>

                    <?php 
                    $img_url = '';
                    if (!empty($item['image'])) {
                        $img = wp_get_attachment_image_src($item['image'][0], 'full');
                        $img_url = $img[0] ?? '';
                    }
                    ?>

                    <div class="col-lg-3 col-md-6 col-sm-6 col-6">

                        <div class="spgs-academics-item rts__program--item"
                             style="background-image: url('<?php echo esc_url($img_url); ?>');">

                            <?php if (!empty($item['description'])): ?>
                                <p class="spgs-academics-desc rts__program--item--description">
                                    <?php echo esc_html($item['description']); ?>
                                </p>
                            <?php endif; ?>

                            <h5 class="spgs-academics-title rts__program--item--title h-abselute">
                                <?php echo esc_html($item['subtitle']); ?><br>
                                <small><?php echo esc_html($item['title']); ?></small>
                            </h5>

                            <?php if (!empty($item['link'])): ?>
                                <a href="<?php echo esc_url($item['link']); ?>" 
                                   class="spgs-academics-btn rts-nbg-btn btn-arrow">
                                    LEARN MORE
                                    <span>
                                        <i class="fas fa-arrow-right"></i>
                                    </span>
                                </a>
                            <?php endif; ?>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <?php
    return ob_get_clean();
}
add_shortcode('spgs_academics', 'spgs_academics_shortcode');