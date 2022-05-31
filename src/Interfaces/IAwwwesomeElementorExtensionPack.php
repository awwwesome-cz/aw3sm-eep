<?php

namespace AwwwesomeEEP\Interfaces;

interface IAwwwesomeEEP {
	/**
	 * Initialize extension
	 *
	 * Here you can do_actions or do_filter for extension
	 *
	 * @return void
	 */
	public static function init();
}