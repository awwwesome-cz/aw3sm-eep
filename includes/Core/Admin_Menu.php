<?php

namespace AwwwesomeEEP\Includes\Core;

use AwwwesomeEEP\AwwwesomeEEP;

class Admin_Menu {
	/**
	 * Hold all pages for register
	 *
	 * @var array
	 */
	protected static array $pages = [];
	protected static array $sub_pages = [];
	private static ?string $slug_prefix = null;

	/**
	 * Add new page to admin
	 *
	 * @param $page_title
	 * @param $menu_title
	 * @param $slug
	 * @param $capability
	 * @param string $callback
	 * @param string $icon_url
	 * @param null $position
	 * @param bool $remove_from_submenu
	 *
	 * @return void
	 */
	static function add_admin_page(
		$page_title,
		$menu_title,
		$slug,
		$capability,
		string $callback = '',
		string $icon_url = '',
		$position = null,
		bool $remove_from_submenu = true
	) {
		self::$pages[ $slug ] = [
			"slug"                => $slug,
			"capability"          => $capability,
			"menu_title"          => $menu_title,
			"page_title"          => $page_title,
			"callback"            => $callback,
			"icon_url"            => $icon_url,
			"position"            => $position,
			"remove_from_submenu" => $remove_from_submenu
		];
	}

	/**
	 * Add new sub-page to admin
	 *
	 * @param $page_title
	 * @param $menu_title
	 * @param $slug
	 * @param $parent_slug
	 * @param $capability
	 * @param string $callback
	 * @param null $position
	 *
	 * @return void
	 */
	static function add_sub_page(
		$page_title,
		$menu_title,
		$slug,
		$parent_slug,
		$capability,
		$callback = '',
		$position = null
	) {
		self::$sub_pages[ $slug ] = [
			"slug"        => $slug,
			"parent_slug" => $parent_slug,
			"capability"  => $capability,
			"menu_title"  => $menu_title,
			"page_title"  => $page_title,
			"callback"    => $callback,
			"position"    => $position,
		];
	}


	/**
	 * Adding menu items
	 *
	 * @return void
	 */
	static function add_menu_pages() {
		foreach ( self::$pages as $page ) {
			$slug = self::$slug_prefix ? self::$slug_prefix . '-' . $page['slug'] : $page['slug'];
			add_menu_page(
				$page['page_title'],
				$page['menu_title'],
				$page['capability'],
				$slug,
				$page['callback'],
				$page['icon_url'],
				$page['position']
			);
		}

		// add submenu
		foreach ( self::$sub_pages as $page ) {
			add_submenu_page(
				self::$slug_prefix ? self::$slug_prefix . '-' . $page['parent_slug'] : $page['parent_slug'],
				$page['page_title'],
				$page['menu_title'],
				$page['capability'],
				self::$slug_prefix ? self::$slug_prefix . '-' . $page['slug'] : $page['slug'],
				$page['callback'],
				$page['position']
			);
		}

		// remove submenu
		foreach ( self::$pages as $page ) {
			$slug = self::$slug_prefix ? self::$slug_prefix . '-' . $page['slug'] : $page['slug'];

			// remove main pages from submenu
			if ( isset( $page['remove_from_submenu'] ) && $page['remove_from_submenu'] ) {
				remove_submenu_page( $slug, $slug );
			}
		}
	}

	static function init_menu_pages() {
		add_action( 'admin_menu', [ self::class, 'add_menu_pages' ] );
	}

	/**
	 * Set slug prefix
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	public static function slug_prefix( string $value ) {
		self::$slug_prefix = $value;
	}
}