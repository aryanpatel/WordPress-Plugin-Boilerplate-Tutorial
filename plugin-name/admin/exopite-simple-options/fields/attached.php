<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Attached
 *
 */
if( ! class_exists( 'Exopite_Simple_Options_Framework_Field_attached' ) ) {

    class Exopite_Simple_Options_Framework_Field_attached extends Exopite_Simple_Options_Framework_Fields {

        public function __construct( $field, $value = '', $unique = '', $where = '' ) {

            parent::__construct( $field, $value, $unique, $where );

            $defaults = array(
              'type' => 'image',
            );

            $options = ( ! empty( $this->field['options'] ) ) ? $this->field['options'] : array();

            $this->field['options'] = wp_parse_args( $options, $defaults );

        }

        public function output(){

            echo $this->element_before();

            if ( $this->where != 'metabox') {

                echo 'This item only available in metabox!<br>';

            } else {

                $attachment_type = ( isset( $this->field['options'] ) && isset( $this->field['options']['type'] ) ) ? $this->field['options']['type'] : '';

                $images = get_attached_media( $attachment_type, get_the_ID() );

                $post_type = get_post_type_object( get_post_type( get_the_ID() ) );

                if ( count( $images ) == 0 ) {

                    printf( esc_html__( 'There is no attachment with type %s for this %s.', 'exopite-sof' ), $this->field['options']['type'], esc_html( $post_type->labels->singular_name ) );

                } else {

                    echo '<div class="exopite-sof-attachment-container" data-ajaxurl="' . site_url( 'wp-admin/admin-ajax.php' ) . '">';

                    foreach( $images as $image ) { ?>
                        <span class="exopite-sof-attachment-media exopite-sof-attachment-media-js" data-media-id="<?php echo $image->ID; ?>"><span class="exopite-sof-attachment-media-delete-overlay"></span><span class="exopite-sof-attachment-media-delete exopite-sof-attachment-media-delete-js"><i class="fa fa-times" aria-hidden="true"></i></span><img src="<?php echo wp_get_attachment_image_src($image->ID,'thumbnail')[0]; ?>" /></span>
                    <?php }

                    echo '</div>';

                }

            }

            echo $this->element_after();

        }

        public static function enqueue( $plugin_sof_url, $plugin_sof_path ) {

            wp_enqueue_script( 'jquery-finderselect', $plugin_sof_url . 'assets/jquery.finderSelect.min.js', array( 'jquery' ), '0.7.0', true );

            $script_file = 'loader-jquery-finderselect.min.js';
            $script_name = 'exopite-sof-jquery-finderselect-loader';

            wp_enqueue_script( $script_name, $plugin_sof_url . 'assets/' . $script_file, array( 'jquery-finderselect' ), filemtime( join( DIRECTORY_SEPARATOR, array( $plugin_sof_path . 'assets', $script_file ) ) ), true );

        }

    }

}
