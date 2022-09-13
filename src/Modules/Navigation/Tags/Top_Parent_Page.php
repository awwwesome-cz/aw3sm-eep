<?php

namespace AwwwesomeEEP\Modules\Navigation\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use ElementorPro\Modules\DynamicTags\ACF\Module as ACFModule;
use ElementorPro\Modules\DynamicTags\Module as DynamicTagsModule;

class Top_Parent_Page extends Tag {
	const TagName = "top-parent-page";

	/**
	 * Get dynamic tag name.
	 *
	 * Retrieve the name of the random number tag.
	 *
	 * @return string Dynamic tag name.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_name() {
		return self::TagName;
	}

	/**
	 * Get dynamic tag title.
	 *
	 * Returns the title of the random number tag.
	 *
	 * @return string Dynamic tag title.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_title() {
		return esc_html__( 'Top parent page', 'aw3sm-eep' );
	}

	/**
	 * Get dynamic tag groups.
	 *
	 * Retrieve the list of groups the random number tag belongs to.
	 *
	 * @return array Dynamic tag groups.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_group() {
		return [ DynamicTagsModule::POST_GROUP ];
	}

	/**
	 * Get dynamic tag categories.
	 *
	 * Retrieve the list of categories the random number tag belongs to.
	 *
	 * @return array Dynamic tag categories.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_categories() {
		return [
			\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
			// \Elementor\Modules\DynamicTags\Module::BASE_GROUP,
		];
	}

	public function render() {
		global $post;
		// Get the current page's ancestors either from existing value or by executing function.
		$post_ancestors = get_post_ancestors( $post );
		// Get the top page id.
		$top_page = $post_ancestors ? end( $post_ancestors ) : $post->ID;

		echo get_the_title( $top_page );
	}
}