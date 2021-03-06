<?php
/**
 * Defines multi-dimensional logic for loading admin views
 *
 * @package   Ensemble\Core\Traits
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core\Traits;

/**
 * Core trait used for component classes needing to load views for various purposes.
 *
 * @since 1.0.0
 */
trait View_Loader {

	/**
	 * Loads a view.
	 *
	 * @since 1.0.0
	 *
	 * @param string $view View to load (if it exists).
	 */
	public function load_view( $view ) {
		if ( $path = $this->get_view_path( $view ) ) {
			$classes = 'overview' === $view ? 'wrap' : 'wrap bootstrap-iso';
			?>
			<div class="<?php echo esc_attr( $classes ); ?>">
				<?php include $path; ?>
			</div>
			<?php
		}
	}

	/**
	 * Retrieves a given view's path (if it exists).
	 *
	 * @since 1.0.0
	 *
	 * @param string $view View to retrieve the path for (if it exists).
	 * @return string File path if it exists, otherwise an empty string.
	 */
	public function get_view_path( $view ) {
		$views = $this->get_views();

		$file_path = $this->get_views_dir() . "{$view}.php";

		if ( in_array( $view, $views, true ) && file_exists( $file_path ) ) {
			return $file_path;
		} else {
			return '';
		}
	}

	/**
	 * Retrieves views registered to the component.
	 *
	 * @since 1.0.0
	 *
	 * @return array Views registered to the component.
	 */
	abstract public function get_views();

	/**
	 * Retrieves the path to the view templates directory for this component.
	 *
	 * @since 1.0.0
	 *
	 * @return string Path to the view templates directory.
	 */
	abstract public function get_views_dir();

}