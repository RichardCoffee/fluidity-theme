<?php

/**
 * Multiple checkbox customize control class.
 *
 * @since  201805420
 * @link http://justintadlock.com/archives/2015/05/26/multiple-checkbox-customizer-control
 * @access public
 */

class TCC_Form_Control_MultipleCB extends TCC_Form_Control_Control {

    /**
     * The type of customize control being rendered.
     *
     * @since  1.0.0
     * @access public
     * @var    string
     */
    public $type = 'checkbox-multiple';

    /**
     * Enqueue scripts/styles.
     *
     * @since  1.0.0
     * @access public
     * @return void
     *//*
    public function enqueue() {
        wp_enqueue_script( 'jt-customize-controls', get_theme_file_uri( 'js/customize-controls.js' ), array( 'jquery' ), FLUIDITY_VERSION, true );
    } //*/

    /**
     * Displays the control content.
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function render_content() {

        if ( empty( $this->choices ) )
            return;

        if ( !empty( $this->label ) ) :
            $this->element( 'span', [ 'class' => 'customize-control-title' ], $this->label );
        endif;

        if ( !empty( $this->description ) ) :
            $this->element( 'span', [ 'class' => 'description customize-control-description' ], $this->description );
        endif;

        $multi_values = !is_array( $this->value() ) ? explode( ',', $this->value() ) : $this->value(); ?>

        <ul>
            <?php foreach ( $this->choices as $value => $label ) : ?>

                <li>
                    <label><?php
                        $attrs = [ 'type' => 'checkbox', 'value' => $value ];
                        $this->checked( $attrs, in_array( $value, $multi_values ) );
                        $this->element( 'input', $attrs ); ?>

                        <?php echo esc_html( $label ); ?>
                    </label>
                </li>

            <?php endforeach; ?>
        </ul><?php

        $attrs = [ 'type' => 'hidden', 'value' => implode( ',', $multi_values ) ];
        $attrs = $this->setting_link ( $attrs );
        $this->element( 'input', $attrs );

    }


}

/* Example from above @link

add_action( 'customize_register', 'jt_customize_register' );

function jt_customize_register( $wp_customize ) {

    $wp_customize->add_setting(
        'favorite_fruit',
        array(
            'default'           => array( 'apple', 'orange' ),
            'sanitize_callback' => 'jt_sanitize_favorite_fruit'
        )
    );

    $wp_customize->add_control(
        new JT_Customize_Control_Checkbox_Multiple(
            $wp_customize,
            'favorite_fruit',
            array(
                'section' => 'title_tagline',
                'label'   => __( 'Favorite Fruit', 'jt' ),
                'choices' => array(
                    'apple'      => __( 'Apple',      'jt' ),
                    'banana'     => __( 'Banana',     'jt' ),
                    'date'       => __( 'Date',       'jt' ),
                    'orange'     => __( 'Orange',     'jt' ),
                    'watermelon' => __( 'Watermelon', 'jt' )
                )
            )
        )
    );
}

function jt_sanitize_favorite_fruit( $values ) {

    $multi_values = !is_array( $values ) ? explode( ',', $values ) : $values;

    return !empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
}

*/
