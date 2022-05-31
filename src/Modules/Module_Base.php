<?php


namespace AwwwesomeEEP\Modules;

use ElementorPro\Plugin;
use Elementor\Core\Base\Module;
use AwwwesomeEEP\Document;

abstract class Module_Base extends Module {

	public function __construct() {
		parent::__construct();

		// Init custom dynamic tags
		add_action( 'elementor/dynamic_tags/register', [ $this, 'register_tags' ] );

		// Initialize all documents
		add_action( 'elementor/documents/register', [ $this, 'register_documents' ] );
		// Init extension
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_extends' ] );
	}

	/**
	 * Register all Dynamic Tags in module
	 * @return void
	 */
	function register_tags() {

		$module = Plugin::elementor()->dynamic_tags;

		foreach ( $this->get_dynamic_tags() as $tag ) {
			$namespace = substr( self::class_name(), 0, strrpos( ( self::class_name() ), '\\' ) );
			$tag       = $namespace . "\\Tags\\" . $tag;
			$module->register( new $tag() );
		}
	}

	/**
	 * Set documents
	 *
	 * Set all custom templates (documents)
	 * Eg: 'swiper-slider' => Slider::get_class_full_name(),
	 *
	 * @return array
	 */
	public function get_documents() {
		return [];
	}

	/**
	 * Register All new Documents to template
	 *
	 * @param $documents_manager
	 */
	function register_documents( $documents_manager ) {
		// Adding documents
		foreach ( $this->get_documents() as $type => $class_name ) {
			$documents_manager->register_document_type( $type, $class_name );
		}
	}

	/**
	 * Remove All widgets from array by actual Document template type
	 *
	 * Can be used in get_widgets() method
	 *
	 * @param array $widgets
	 * @param array $array
	 *
	 * @return array with complete set of Widgets
	 * @throws \Exception
	 */
	function remove_widget_in_document(
		$widgets, $array = [
		// 'swiper-slider' => ['Swiper'] // document must be same name
		// 'swiper-slider!' => ['Swiper'] // any document, but this document be ingnored
	]
	) {
		$doc          = new Document( [ 'post_id' => get_the_ID() ] );
		$doc_template = $doc->get_template_type();
		foreach ( $array as $document => $_widgets ) {
			foreach ( $_widgets as $widget ) {
				// Get condition by
				$condition = $this->get_document_condition( $document, $doc_template );

				if ( $condition ) {
					$key = array_search( $widget, $widgets );
					if ( $key !== false ) {
						unset( $widgets[ $key ] );
					}
				}
			}
		}


		return $widgets;
	}

	/**
	 * Remove All extension from array by actual Document template type
	 *
	 * Can be used in get_extends() method
	 *
	 * @param array $widgets
	 * @param array $documents
	 *
	 * @return array with complete set of Widgets
	 * @throws \Exception
	 */
	function remove_extension_in_document(
		$extensions, $documents = [
		// 'document-type' => [Extension::class] // document must be same name
		// 'document-type!' => [Extension::class] // any document, but this document be ingnored
	]
	) {
		$doc          = new Document( [ 'post_id' => get_the_ID() ] );
		$doc_template = $doc->get_template_type();

		foreach ( $documents as $document => $_extensions ) {
			foreach ( $_extensions as $extension ) {

				// Get condition by
				$condition = $this->get_document_condition( $document, $doc_template );

				if ( $condition ) {
					$key = array_search( $extension, $extensions );
					if ( $key !== false ) {
						unset( $extensions[ $key ] );
					}
				}
			}
		}


		return $extensions;
	}

	public function init_extends() {
		if ( ! empty( $this->get_extends() ) ) {
			foreach ( $this->get_extends() as $extensions ) {
				$extensions::init();
			}
		}
	}

	public function get_extends() {
		return [];
	}

	protected function ends_with( $haystack, $needle ) {
		$length = strlen( $needle );
		if ( ! $length ) {
			return true;
		}

		return substr( $haystack, - $length ) === $needle;
	}


	/**
	 * Get column condition by string
	 *
	 * @param $document
	 * @param $doc_template
	 *
	 * @return mixed
	 */
	protected function get_document_condition( $document, $doc_template ) {
		if ( $this->ends_with( $document, '!' ) ) {
			$document  = rtrim( $document, '!' );
			$condition = eval( "return \"$doc_template\" != \"$document\";" );
		} else {
			$condition = eval( "return \"$doc_template\" == \"$document\";" );
		}

		return $condition;
	}

	/**
	 * Init dynamic tags
	 *
	 * Set class names (files) in Tags folder
	 *
	 * Class namespace added automaticaly - AwwwesomeEEP\Modules\ACF\DynamicTags\{YourClass}
	 *
	 * @return array
	 */
	protected function get_dynamic_tags() {
		return [];
	}
}