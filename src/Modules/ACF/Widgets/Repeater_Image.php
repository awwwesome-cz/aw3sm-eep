<?php

namespace AwwwesomeEEP\Modules\ACF\Widgets;

use Elementor\Controls_Manager;

class Repeater_Image extends Repeater {

	public function get_name() {
		return 'acf-repeater-image';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve button widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'ACF Repeater Image', 'aw3sm-eep' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve button widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'eicon-image-box';
	}

	protected function register_controls() {
		parent::register_controls();

		$this->update_control(
			'acf_info_description',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Image must by as value in select picker', 'aw3sm-eep' ),
				'separator'       => 'none',
				'content_classes' => 'elementor-descriptor',
			]
		);
	}

	protected function register_style_controls() {
		$this->add_control(
			'image_background_color',
			[
				'label'     => esc_html__( 'Image background color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .acf-repeater-list .acf-repeater-image' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'image_weight',
			[
				'label'     => esc_html__( 'Image Weight', 'aw3sm-eep' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 400,
					],
				],
				/*'condition' => [
					'separator_style!' => 'none',
				],*/
				'selectors' => [
					'{{WRAPPER}} .acf-repeater-list .acf-repeater-image img' => 'width: {{SIZE}}{{UNIT}}',
				],
				'default'   => [
					'size' => 30,
				],
			]
		);
		$this->add_control(
			'image_padding',
			[
				'label'      => esc_html__( 'Image Padding', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .acf-repeater-list .acf-repeater-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'separator'  => 'after',
			]
		);

		$this->add_control(
			'text_image_gap',
			[
				'label'      => esc_html__( 'Text Image Gap', 'elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'max' => 50,
					],
					'em' => [
						'max'  => 5,
						'step' => 0.1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .acf-repeater-list .acf-repeater-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'default'    => [
					'size' => 10,
				],
			]
		);

		$this->add_control(
			'image_background_border_radius',
			[
				'label'      => esc_html__( 'Image Background Border Radius', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'separator'  => 'after',
				'selectors'  => [
					'{{WRAPPER}} .acf-repeater-list .acf-repeater-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		parent::register_style_controls();

		$this->add_responsive_control(
			'text_align',
			[
				'label'     => esc_html__( 'Text Alignment', 'aw3sm-eep' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'aw3sm-eep' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'aw3sm-eep' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'aw3sm-eep' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .acf-repeater-list' => 'text-align: {{VALUE}}',
				],
			] );
	}

	protected function render() {
		// get settings
		$acf_group = $this->get_settings( 'acf_field' );

		if ( '' === $acf_group ) {
			return;
		}

		$_finalContent = '';

		$field = get_field_object( $acf_group );
		if ( ! $field ) {
			return;
		}
		$_finalContent .= "<div class=\"acf-repeater-list\" style='display: flex'>";
		if ( $field['type'] != 'group' ) {
			foreach ( $field['value'] as $value ) {
				$name      = $field['choices'][ $value ];
				$image_src = $value;

				$_finalContent .= "<div class=\"acf-repeater-item\">";
				$_finalContent .= "<div class=\"acf-repeater-image\">";
				$_finalContent .= sprintf( '<img src="%s" alt="%s" style="display: block;">', $image_src, $name );
				$_finalContent .= "</div>";
				$_finalContent .= "<span>$name</span>";
				$_finalContent .= "</div>";
			}
		} else {
			$acf_sub_field = $this->get_settings( 'acf_sub_field' );
			if ( '' === $acf_sub_field ) {
				return;
			}

			$sub_field_obj = get_field_object( $field['name'] . '_' . $acf_sub_field );
			if ( ! $sub_field_obj['multiple'] ) {
				$name = $sub_field_obj['name'];

				// single value
				$_finalContent .= "<div class=\"acf-repeater-item\">";
				$_finalContent .= "<div class=\"acf-repeater-image\">";
				$_finalContent .= "Single value cannot contain image URL";
				$_finalContent .= "</div>";
				$_finalContent .= "<span>$name</span>";
				$_finalContent .= "</div>";
			} else {
				// array of values
				foreach ( $sub_field_obj['value'] as $value ) {
					$name      = $sub_field_obj['choices'][ $value ]; // Get name
					$image_src = $value;

					$_finalContent .= "<div class=\"acf-repeater-item\">";
					$_finalContent .= "<div class=\"acf-repeater-image\">";
					$_finalContent .= sprintf( '<img src="%s" alt="%s" style="display: block;">', $image_src, $name );
					$_finalContent .= "</div>";
					$_finalContent .= "<span>$name</span>";
					$_finalContent .= "</div>";
				}
			}
		}
		$_finalContent .= "</div>";

		echo $_finalContent;
	}
}