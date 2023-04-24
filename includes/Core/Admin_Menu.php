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
	private static ?string $slug_prefix = null;

	/**
	 * Add new page to admin
	 *
	 * @param $page_title
	 * @param $menu_title
	 * @param $slug
	 * @param $capability
	 * @param $callback
	 * @param $icon_url
	 * @param $position
	 *
	 * @return void
	 */
	static function add_admin_page(
		$page_title,
		$menu_title,
		$slug,
		$capability,
		$callback = '',
		$icon_url = '',
		$position = null
	) {
		self::$pages[ $slug ] = [
			"slug"       => $slug,
			"capability" => $capability,
			"menu_title" => $menu_title,
			"page_title" => $page_title,
			"callback"   => $callback,
			"icon_url"   => $icon_url,
			"position"   => $position,
		];
	}


	static function add_menu_pages() {
		foreach ( self::$pages as $page ) {
			add_menu_page(
				$page['page_title'],
				$page['menu_title'],
				$page['capability'],
				self::$slug_prefix ? self::$slug_prefix . '-' . $page['slug'] : $page['slug'],
				$page['callback'],
				$page['icon_url'],
				$page['position']
			);
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