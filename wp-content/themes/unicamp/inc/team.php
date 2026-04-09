<?php
add_filter( 'rwmb_meta_boxes', 'page__Our_team_multiple' );
function page__Our_team_multiple($meta_boxes){
    $meta_boxes[] = [
        'id'         => 'our_team_multiple',
        'title'      => esc_html__( 'Our Team', 'SPGS' ),
        'post_types' => ['page'],
        'context'    => 'normal',
        'priority'   => 'high',
        'autosave'   => true,
        'include' => ['ID'  => [6692]],
        'fields'  => [
            [
              'id'          => 'mainteam',
              'name'        => '',
              'type'        => 'group',
              'clone'       => true,
              'sort_clone'  => true,
              'collapsible' => true,
              'default_state' => 'collapsed',
              'group_title' => ['field' => 'head_team'],
              'fields' => [
                    [
                        'name'  => 'Team Section',
                        'id'    => 'head_team',
                        'type'  => 'text',
                    ],
                    [
                      'name'   => '',
                      'id'     => 'teamid',
                      'type'   => 'group',
                      'clone'  => true,
                      'sort_clone' => true,
                      'collapsible' => true,
                      'default_state' => 'collapsed',
                      'group_title' => ['field' => 'title_team'],             
                      'fields' => [
                          [
                              'name'  => 'Name',
                              'id'    => 'title_team',
                              'type'  => 'text',
                          ],
                          [
                            'name'  => 'Designation',
                            'id'    => 'des_team',
                            'type'  => 'text',
                            'clone'  => true
                          ],
                          [
                              'name'    => 'Image',
                              'id'      => 'pic_team',
                              'type'    => 'file_advanced',
                              'force_delete' => false,
                              'max_status'  => 'false',
                              'max_file_uploads' => 1,
                          ],
                          [
                            'name' => 'Line Break ',
                            'id'   => 'team_break',
                            'type' => 'checkbox',
                            'std'  => 0, // 0 or 1
                          ],
                          [
                            'name'    => 'Description',
                            'id'      => 'des',
                            'type'    => 'wysiwyg',
                            'raw'     => false,
                            'options' => [
                                'textarea_rows' => 4,
                                'teeny'         => true,
                                ],
                            ], 
                      ],   
                    ],                    
              ]

            ],  
        ],
    ];           
    return $meta_boxes;
}

add_shortcode( 'our_team', 'render_our_team' );
function render_our_team() {

    $team_sections = rwmb_meta('mainteam');
    if ( empty( $team_sections ) ) return '';

    ob_start();
    ?>

    <section class="rts-faculty rts-section-padding">
        <div class="container">

        <?php foreach ( $team_sections as $section_index => $section ) : ?>

            <?php if ( ! empty( $section['head_team'] ) ) : ?>
                <div class="row justify-content-md-center">
                    <div class="col-lg-12 col-md-11">
                        <div class="rts-section mb--40 mt--50">
                            <h3 class="rts-section-title">
                                <?php echo esc_html( $section['head_team'] ); ?>
                            </h3>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $section['teamid'] ) ) : ?>
            <div class="row justify-content-md-left mb--40 g-5">

                <?php foreach ( $section['teamid'] as $member_index => $member ) :

                    // Image
                    $image_url = get_template_directory_uri() . '/assets/images/faculty/placeholder.jpg';
                    if ( ! empty( $member['pic_team'][0] ) ) {
                        $image_url = wp_get_attachment_image_url( $member['pic_team'][0], 'full' );
                    }

                    // Create unique modal ID
                    $modal_id = 'teamMember' . $section_index . $member_index;
                ?>

                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="single-staff">
                        <div class="single-staff__content">
                            <div class="staf-image">
                                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $member['title_team'] ); ?>">
                            </div>
                            <div class="staf-info">
                                <h5 class="name"><?php echo esc_html( $member['title_team'] ); ?></h5>

                                <?php
                                if ( ! empty( $member['des_team'] ) && is_array( $member['des_team'] ) ) {
                                    echo '<div class="designation">';
                                    foreach ( $member['des_team'] as $designation ) {
                                        echo esc_html( $designation ) . '<br>';
                                    }
                                    echo '</div>';
                                }
                                ?>

                                <?php if ( ! empty( $member['des'] ) ) : ?>
                                    <button type="button" class="rts-theme-btn border-btn"
                                       data-bs-toggle="modal"
                                       data-bs-target="#<?php echo esc_attr( $modal_id ); ?>">
                                        More Details
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php 
                // Check for line break
                if ( ! empty( $member['team_break'] ) ) {
                    echo '</div><div class="row justify-content-md-left mb--40 g-5">';
                }
                ?>

                <?php endforeach; ?>

            </div>
            <?php endif; ?>

        <?php endforeach; ?>

        </div>

        <!-- All Modals -->
        <?php foreach ( $team_sections as $section_index => $section ) : ?>
            <?php if ( ! empty( $section['teamid'] ) ) : ?>
                <?php foreach ( $section['teamid'] as $member_index => $member ) : ?>
                    <?php if ( ! empty( $member['des'] ) ) : 
                        $image_url = get_template_directory_uri() . '/assets/images/faculty/placeholder.jpg';
                        if ( ! empty( $member['pic_team'][0] ) ) {
                            $image_url = wp_get_attachment_image_url( $member['pic_team'][0], 'full' );
                        }
                        $modal_id = 'teamMember' . $section_index . $member_index;
                    ?>
                    <div class="modal fade" id="<?php echo esc_attr( $modal_id ); ?>" tabindex="-1" aria-labelledby="<?php echo esc_attr( $modal_id ); ?>Label" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="<?php echo esc_attr( $modal_id ); ?>Label">
                                        <?php echo esc_html( $member['title_team'] ); ?>
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="team-modal-content">
                                        <img src="<?php echo esc_url( $image_url ); ?>"
                                             alt="<?php echo esc_attr( $member['title_team'] ); ?>"
                                             class="team-modal-image">
                                        <div class="team-modal-text">
                                            <?php echo wpautop( $member['des'] ); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>

    </section>

    <style>
    /* Team Card Styles */
    .single-staff__content {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .staf-image {
        flex-shrink: 0;
        width: 180px;
    }

    .staf-image img {
        width: 100%;
        height: auto;
        display: block;
    }

    .staf-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .staf-info .name {
        margin: 0;
        font-size: 24px;
        color: #4169B1;
        font-weight: 600;
    }

    .staf-info .designation {
        color: #666;
        font-size: 14px;
        line-height: 1.6;
    }

    .rts-theme-btn.border-btn {
        display: inline-block;
        padding: 8px 24px;
        border: 2px solid #4169B1;
        color: #4169B1;
        background: transparent;
        text-decoration: none;
        border-radius: 4px;
        transition: all 0.3s ease;
        align-self: flex-start;
        margin-top: 10px;
        cursor: pointer;
        font-size: 14px;
    }

    .rts-theme-btn.border-btn:hover {
        background: #4169B1;
        color: #fff;
    }

    /* Modal Styles */
    .team-modal-content {
        display: flex;
        gap: 25px;
        align-items: flex-start;
    }

    .team-modal-image {
        width: 195px;
        flex-shrink: 0;
        border-radius: 4px;
    }

    .team-modal-text {
        flex: 1;
        color: #666;
        line-height: 1.8;
    }

    .team-modal-text p {
        margin-bottom: 15px;
    }

    .team-modal-text p:last-child {
        margin-bottom: 0;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .single-staff__content {
            flex-direction: column;
        }

        .staf-image {
            width: 100%;
        }

        .team-modal-content {
            flex-direction: column;
        }

        .team-modal-image {
            width: 100%;
        }
    }
    </style>

    <?php
    return ob_get_clean();
}