<?php
/**
 * Testimonial Slider Section 
 */

/**
 * META BOX
 */
add_filter( 'rwmb_meta_boxes', 'spgs_testimonial_meta_box' );

function spgs_testimonial_meta_box($meta_boxes){

    $meta_boxes[] = [
        'title'      => 'Testimonial Slider',
        'id'         => 'spgs-testimonial-meta-box',
        'post_types' => ['page'],

        'include' => [
            'ID' => [10644],
        ],

        'fields' => [
            [
                'id'           => 'testimonial_items',
                'type'         => 'group',
                'clone'        => true,
                'sort_clone'   => true,
                'collapsible'  => true,
                'group_title'  => ['field' => 'quote'],
                'add_button'   => 'Add Slide',

                'fields' => [

                    [
                        'id'   => 'image',
                        'name' => 'Logo Image',
                        'type' => 'image_advanced',
                        'max_file_uploads' => 1,
                    ],

                    [
                        'id'   => 'quote',
                        'name' => 'Quote Text',
                        'type' => 'textarea',
                    ],

                ],
            ],
        ],
    ];

    return $meta_boxes;
}


/**
 * SHORTCODE
 */
function spgs_testimonial_slider_shortcode($atts){

    $atts = shortcode_atts([
        'id' => 10644,
    ], $atts);

    if ($atts['id'] != 10644) return '';

    $items = rwmb_get_value('testimonial_items', $atts['id']);

    if (empty($items)) return '';

    ob_start();
?>

<div class="spgs-testimonial-slider swiper spgs-swiper">
    <div class="swiper-wrapper">

        <?php foreach($items as $item): ?>

            <?php
            // Safe image handling
            $image_id = $item['image'] ?? '';

            if (is_array($image_id)) {
                $image_id = $image_id[0] ?? '';
            }

            $img_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
            ?>

            <div class="swiper-slide">

                <div class="c-testimonial-single">
                    <div class="o-wrapper">
                        <div class="c-testimonial-single__grid-container">

                            <!-- LEFT IMAGE -->
                            <div class="c-testimonial-single__left-container">
                                <div class="c-testimonial-single__img">
                                    <?php if($img_url): ?>
                                        <img src="<?php echo esc_url($img_url); ?>" alt="">
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- RIGHT QUOTE -->
                            <!--<div class="c-testimonial-single__right-container">-->
                            <!--    <blockquote class="c-testimonial-single__quote">-->
                            <!--        <p class="c-testimonial-single__quote-text">-->
                            <!--            <?php echo esc_html($item['quote']); ?>-->
                            <!--        </p>-->
                            <!--    </blockquote>-->
                            <!--</div>-->
                            <!-- RIGHT QUOTE -->
                            <div class="c-testimonial-single__right-container">
                                <blockquote class="c-testimonial-single__quote" style="border-left: none; border: none;">
                                    <span class="c-testimonial-single__quote-open">&ldquo;</span>
                                    <p class="c-testimonial-single__quote-text">
                                        <?php echo esc_html($item['quote']); ?>
                                    </p>
                                    <span class="c-testimonial-single__quote-close">&rdquo;</span>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        <?php endforeach; ?>

    </div>

    <!-- Scoped Pagination -->
    <div class="swiper-pagination spgs-testimonial-pagination"></div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function(){

    // Prevent conflict
    if (typeof Swiper === 'undefined') return;

    const el = document.querySelector('.spgs-testimonial-slider');

    if (!el) return;

    new Swiper(el, {
        loop: true,
        autoplay: {
            delay: 4000,
        },
        pagination: {
            el: '.spgs-testimonial-pagination',
            clickable: true,
        },
    });

});
</script>

<?php
    return ob_get_clean();
}
add_shortcode('spgs_testimonial_slider', 'spgs_testimonial_slider_shortcode');