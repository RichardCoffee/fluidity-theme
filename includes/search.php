<?php
/**
 *  handle search form tasks
 *
 */
defined( 'ABSPATH' ) || exit;
/**
 *  provide common search format
 *
 * @since 20180408
 * @param array $form
 */
if ( ! function_exists( 'fluid_show_search_form' ) ) {
	function fluid_show_search_form( $form ) {
		$form = apply_filters( 'fluid_show_search_form', $form );
		if ( $form['form'] === false ) {
			fluid_show_search_field( $form );
		} else { ?>
			<form <?php fluid()->apply_attrs( $form['form'] ); ?>><?php
				fluid_show_search_field( $form ); ?>
			</form><?php
		}
	}
}

/**
 *  show search input field group
 *
 * @since 20180901
 * @param array $form
 */
if ( ! function_exists( 'fluid_show_search_field' ) ) {
	function fluid_show_search_field( $form ) {
#		$form = apply_filters( 'fluid_show_search_field', $form ); ?>
		<div class="input-group"><?php
			fluid()->element( 'label', $form['label'], $form['text']['label'] );
			if ( isset( $form['hidden'] ) ) {
				fluid()->element( 'input', $form['hidden'] );
			} ?>
			<span class="search-input-group"><?php
				fluid()->element( 'input', $form['search'] ); ?>
				<span class="input-group-btn">
				<button <?php fluid()->apply_attrs( $form['button'] ); ?>>
						<?php fluid()->fawe( 'fa-search' ); ?>&nbsp;
						<span class="screen-reader-text">
							<?php esc_html( $form['text']['button'] ); ?>
						</span>
					</button>
				</span>
			</span>
		</div><?php
	}
}
