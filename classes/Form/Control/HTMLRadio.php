<?php
/**
 * classes/Form/Control/HTMLRadio.php
 *
 */
/**
 *  display radio, showing raw html choices
 *
 */
class TCC_Form_Control_HTMLRadio extends TCC_Form_Control_Control {

	public $type = 'htmlradio';

	public function render_content() {

		if ( empty( $this->choices ) ) {
			return;
		}

		$description_id = 'description-' . $this->id;

		if ( isset( $this->label ) ) {
			$this->element( 'span', [ 'class' => 'customize-control-title', ], $this->label );
		}

		if ( ! empty( $this->description ) ) {
			$attrs = array(
				'id'    => $description_id,
				'class' => 'description customize-control-description'
			);
			$this->element( 'span', $attrs, $this->description );
		}

		foreach ( $this->choices as $value => $label ) { ?>

			<span class="customize-inside-control-row">
				<label><?php

					$attrs = array(
						'id'    => $this->id . '-radio-' . $value,
						'type'  => 'radio',
						'value' => $value,
						'name'  => $this->id,
					);
					if ( ! empty( $this->description ) ) {
						$attrs['aria-describedby'] = $description_id;
					}
					$attrs = $this->linked( $attrs );
					$attrs = $this->checked( $attrs, $this->value(), $value );

					$this->element( 'input', $attrs );
					echo $label; ?>

				</label>
			</span><?php

		}

	}


}
