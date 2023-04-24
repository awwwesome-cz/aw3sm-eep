<?php

namespace AwwwesomeEEP\Admin\Controllers;

use AwwwesomeEEP\AwwwesomeEEP;
use AwwwesomeEEP\Includes\Core\Admin_Controller;

class Dashboard extends Admin_Controller {
	/**
	 * Show dashboard
	 *
	 * @return void
	 */
	function index() {
		echo $this->view( "dashboard.index", [
			"version" => AwwwesomeEEP::version(),
		] );
	}
}