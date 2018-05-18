<?php
/**
 * Front-end Template :oader
 *
 * @package   Ensemble\Core
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core;

use Ensemble\Core\Interfaces\Loader;

/**
 * Core class to load template parts on the front-end.
 *
 * @since 1.0.0
 *
 * @see Loader
 */
class Template_Loader implements Loader {

	/**
	 * Registers callbacks for template loading purposes.
	 *
	 * @since 1.0.0
	 */
	public function load() {

	}

	/**
	 * Retrieves the templates directory path.
	 *
	 * @since 1.0.0
	 *
	 * @return string Templates directory path.
	 */
	public function get_templates_dir() {
		return ENSEMBLE_PLUGIN_DIR . '/templates';
	}

	/**
	 * Retrieves the templates directory URL.
	 *
	 * @since 1.0.0
	 *
	 * @return string Templates directory URL.
	 */
	public function get_templates_url() {
		return ENSEMBLE_PLUGIN_URL . '/templates';
	}

	/**
	 * Retrieves the URL to the theme's Ensemble templates directory.
	 *
	 * @since 1.0.0
	 *
	 * @return string URL to the theme's Ensemble templates director.
	 */
	public function get_theme_template_dir_name() {
		/**
		 * Filters the default Ensemble templates directory name in the theme.
		 *
		 * @since 1.0.0
		 *
		 * @param string $directory Templates directory name to search for.
		 */
		return apply_filters( 'ensemble_theme_template_directory_name', 'ensemble' );
	}

	/**
	 * Retrieves and loads template part.
	 *
	 * h/t bbPress.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug Template part slug.
	 * @param string $name Optional. Filename. Default null.
	 * @param bool   $load Optional. Whether to load the file if it's found. Default false.
	 * @return string Template part path (if found).
	 */
	public function get_template_part( $slug, $name = null, $load = true ) {

		/**
		 * Fires immediately before retrieving a template part.
		 *
		 * The dynamic portion of the hook name, `$slug`, refers to the template part slug.
		 *
		 * @since 1.0.0
		 *
		 * @param string $slug Template part slug.
		 * @param string $name Template part name.
		 */
		do_action( "ensemble_get_template_part_{$slug}", $slug, $name );

		// Setup possible parts
		$templates = array();

		if ( isset( $name ) ) {
			$templates[] =  "{$slug}-{$name}.php";
		}

		$templates[] = "{$slug}.php";

		/**
		 * Filters the templates array for the given file slug and name (if set).
		 *
		 * @since 1.0.0
		 *
		 * @param array       $templates Templates array.
		 * @param string      $slug      Template slug.
		 * @param string|null $name      Template name (if set), otherwise null.
		 */
		$templates = apply_filters( 'ensemble_get_template_part', $templates, $slug, $name );

		// Locate the template and return the path if found.
		return $this->locate_template( $templates, $load, false );
	}

	/**
	 * Retrieve the name of the highest priority template file that exists.
	 *
	 * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
	 * inherit from a parent theme can just overload one file. If the template is
	 * not found in either of those, it looks in the theme-compat folder last.
	 *
	 * h/t bbPress.
	 *
	 * @since 1.0
	 *
	 * @param string|array $templates    Template file(s) to search for, in order.
	 * @param bool         $load         Optional. Whether to load the template file if found. Default false.
	 * @param bool         $require_once Optional. Whether to require_once or not. If false, require will be used.
	 *                                     Has no effect of `$load` is true. Default true.
	 * @return string The template filename if one is located.
	 */
	public function locate_template( $templates, $load = false, $require_once = true ) {
		$located = false;

		if ( ! is_array( $templates ) ) {
			$templates = (array) $templates;
		}

		// Attempt to locate a valid template from the given array.
		foreach ( $templates as $template ) {

			// Skip empty templates.
			if ( empty( $template ) ) {
				continue;
			}

			// Trim off any slashes from the template name
			$template = ltrim( $template, '/' );

			// try locating this template file by looping through the template paths
			foreach( $this->get_theme_template_paths() as $template_path ) {

				if ( file_exists( $template_path . $template ) ) {
					$located = $template_path . $template;
					break;
				}

			}
		}

		if ( ( true === $load ) && ! empty( $located ) ) {
			load_template( $located, $require_once );
		}

		return $located;
	}

	/**
	 * Retrieves a list of paths to check for template locations.
	 *
	 * @since 1.0.0
	 *
	 * @return array Trailingslashed file paths.
	 */
	public function get_theme_template_paths() {

		$template_directory = $this->get_theme_template_dir_name();

		$paths = array(
			1   => trailingslashit( get_stylesheet_directory() ) . $template_directory,
			10  => trailingslashit( get_template_directory() ) . $template_directory,
			100 => $this->get_templates_dir()
		);

		/**
		 * Filters the current set of template paths.
		 *
		 * @since 1.0.0
		 *
		 * @param array $paths Template paths.
		 */
		$paths = apply_filters( 'ensemble_template_paths', $paths );

		ksort( $paths, SORT_NUMERIC );

		// Add trailing slashes to all paths.
		$paths = array_map( 'trailingslashit', $paths );

		return $paths;
	}

}
