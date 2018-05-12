<?php
/**
 * Plugin Name: Ensemble
 * Plugin URI: https://werdswords.com
 * Description: Easily manage a color guard circuit (or similar sport/activity organization) with WordPress.
 * Version: 1.0.0
 * Author: Drew Jaynes (DrewAPicture)
 * Author URI: http://werdswords.com
 * License: GPLv2
 * Text Domain: ensemble
 */

// Bail if this file is called directly.
defined( 'ABSPATH' ) || exit;

/**
 * Utility class to check requirements before bootstrapping Ensemble.
 *
 * @since 1.0.0
 */
final class Ensemble_Requirements_Check {

	/**
	 * Plugin file path.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	private $file = '';

	/**
	 * Plugin basename.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	private $base = '';

	/**
	 * Requirements array.
	 *
	 * @since 1.0.0
	 * @var   array
	 */
	private $requirements = array(

		// PHP.
		'php' => array(
			'minimum' => '5.5.0',
			'name'    => 'PHP',
			'exists'  => true,
			'current' => false,
			'checked' => false,
			'met'     => false
		),

		// WordPress.
		'wp' => array(
			'minimum' => '4.9.0',
			'name'    => 'WordPress',
			'exists'  => true,
			'current' => false,
			'checked' => false,
			'met'     => false
		)

	);

	/**
	 * Sets up plugin requirements.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// File path and base.
		$this->file = __FILE__;
		$this->base = plugin_basename( $this->get_file() );

		// Load or quit.
		$this->requirements_met() ? $this->load() : $this->quit();
	}

	/**
	 * Retrieves the base plugin file path.
	 *
	 * @since 1.0.0
	 *
	 * @return string Plugin file.
	 */
	public function get_file() {
		return $this->file;
	}

	/**
	 * Retrieves the plugin basename.
	 *
	 * @since 1.0.0
	 *
	 * @return string Plugin basename.
	 */
	public function get_base() {
		return $this->base;
	}

	/**
	 * Quits without fully loading the plugin.
	 *
	 * @since 1.0.0
	 */
	private function quit() {
		$base = $this->get_base();

		add_action( 'admin_head',                  array( $this, 'admin_head'        ) );
		add_filter( "plugin_action_links_{$base}", array( $this, 'plugin_row_links'  ) );
		add_action( "after_plugin_row_{$base}",    array( $this, 'plugin_row_notice' ) );
	}

	/**
	 * Fully loads the plugin normally.
	 *
	 * @since 1.0.0
	 */
	private function load() {
		require_once dirname( $this->get_file() ) . '/includes/class-ensemble.php';

		if ( class_exists( 'Ensemble' ) ) {
			Ensemble::get_instance( $this->get_file() );
		}

		/**
		 * Fires once Ensemble has loaded.
		 *
		 * @since 1.0.0
		 */
		do_action( 'ensemble_loaded' );
	}

	/**
	 * Plugin specific URL for an external requirements page.
	 *
	 * @since 1.0.0
	 *
	 * @return string Unmet requirements URL.
	 */
	private function unmet_requirements_url() {
		return 'https://github.com/DrewAPicture/ensemble/wiki/Minimum-Requirements';
	}

	/**
	 * Plugin specific text to quickly explain what's wrong.
	 *
	 * @since 1.0.0
	 *
	 * @return string Unmet requirements text.
	 */
	private function unmet_requirements_text() {
		esc_html_e( 'This plugin is not fully active.', 'ensemble' );
	}

	/**
	 * Plugin specific text to describe a single unmet requirement.
	 *
	 * @since 1.0.0
	 *
	 * @return string Unmet requirements description text.
	 */
	private function unmet_requirements_description_text() {
		return esc_html__( 'Requires %s (%s), but (%s) is installed.', 'ensemble' );
	}

	/**
	 * Plugin specific text to describe a single missing requirement.
	 *
	 * @since 1.0.0
	 *
	 * @return string Unmet missing requirements text.
	 */
	private function unmet_requirements_missing_text() {
		return esc_html__( 'Requires %s (%s), but it appears to be missing.', 'ensemble' );
	}

	/**
	 * Plugin specific text used to link to an external requirements page.
	 *
	 * @since  1.0.0
	 *
	 * @return string Unmet requirements link.
	 */
	private function unmet_requirements_link() {
		return esc_html__( 'Requirements', 'ensemble' );
	}

	/**
	 * Plugin specific aria label text to describe the requirements link.
	 *
	 * @since 1.0.0
	 *
	 * @return string Unmet requirements label.
	 */
	private function unmet_requirements_label() {
		return esc_html__( 'Ensemble Requirements', 'ensemble' );
	}

	/**
	 * Plugin specific text used in CSS to identify attribute IDs and classes.
	 *
	 * @since 1.0.0
	 *
	 * @return string Unmet requirements name.
	 */
	private function unmet_requirements_name() {
		return 'ensemble-reqs';
	}

	/**
	 * Plugin-agnostic method to output the additional plugin row.
	 *
	 * @since 1.0.0
	 */
	public function plugin_row_notice() {
		?><tr class="active <?php echo esc_attr( $this->unmet_requirements_name() ); ?>-row">
		<th class="check-column">
			<span class="dashicons dashicons-warning"></span>
		</th>
		<td class="column-primary">
			<?php $this->unmet_requirements_text(); ?>
		</td>
		<td class="column-description">
			<?php $this->unmet_requirements_description(); ?>
		</td>
		</tr><?php
	}

	/**
	 * Plugin-agnostic method used to output all unmet requirement information
	 *
	 * @since 1.0.0
	 */
	private function unmet_requirements_description() {
		foreach ( $this->requirements as $properties ) {
			if ( empty( $properties['met'] ) ) {
				$this->unmet_requirement_description( $properties );
			}
		}
	}

	/**
	 * Plugin-agnostic method to output specific unmet requirement information
	 *
	 * @since 1.0.0
	 *
	 * @param array $requirement Optional. Array of requirement attributes. Default empty array.
	 */
	private function unmet_requirement_description( $requirement = array() ) {

		// Requirement exists, but is out of date.
		if ( ! empty( $requirement['exists'] ) ) {
			$text = sprintf(
				$this->unmet_requirements_description_text(),
				'<strong>' . esc_html( $requirement['name']    ) . '</strong>',
				'<strong>' . esc_html( $requirement['minimum'] ) . '</strong>',
				'<strong>' . esc_html( $requirement['current'] ) . '</strong>'
			);

		// Requirement could not be found
		} else {
			$text = sprintf(
				$this->unmet_requirements_missing_text(),
				'<strong>' . esc_html( $requirement['name']    ) . '</strong>',
				'<strong>' . esc_html( $requirement['minimum'] ) . '</strong>'
			);
		}

		// Output the description
		printf( '<p>%s</p>', $text );
	}

	/**
	 * Plugin-agnostic method to output unmet requirements styling
	 *
	 * @since 1.0.0
	 */
	public function admin_head() {

		// Get the requirements row name
		$name = $this->unmet_requirements_name(); ?>

		<style id="<?php echo esc_attr( $name ); ?>">
			.plugins tr[data-plugin="<?php echo esc_html( $this->get_base() ); ?>"] th,
			.plugins tr[data-plugin="<?php echo esc_html( $this->get_base() ); ?>"] td,
			.plugins .<?php echo esc_html( $name ); ?>-row th,
			.plugins .<?php echo esc_html( $name ); ?>-row td {
				background: #fff5f5;
			}
			.plugins tr[data-plugin="<?php echo esc_html( $this->get_base() ); ?>"] th {
				box-shadow: none;
			}
			.plugins .<?php echo esc_html( $name ); ?>-row th span {
				margin-left: 6px;
				color: #dc3232;
			}
			.plugins tr[data-plugin="<?php echo esc_html( $this->get_base() ); ?>"] th,
			.plugins .<?php echo esc_html( $name ); ?>-row th.check-column {
				border-left: 4px solid #dc3232 !important;
			}
			.plugins .<?php echo esc_html( $name ); ?>-row .column-description p {
				margin: 0;
				padding: 0;
			}
			.plugins .<?php echo esc_html( $name ); ?>-row .column-description p:not(:last-of-type) {
				margin-bottom: 8px;
			}
		</style>
		<?php
	}

	/**
	 * Plugin-agnostic method to add the "Requirements" link to row actions.
	 *
	 * @since 1.0.0
	 *
	 * @param array $links Existing row links for the current plugin.
	 * @return array Modified row links.
	 */
	public function plugin_row_links( $links ) {

		// Add the Requirements link
		$links['requirements'] =
			'<a href="' . esc_url( $this->unmet_requirements_url() ) . '" aria-label="' . esc_attr( $this->unmet_requirements_label() ) . '">'
			. esc_html( $this->unmet_requirements_link() )
			. '</a>';

		// Return links with Requirements link
		return $links;
	}

	/**
	 * Plugin-specific requirements checker.
	 *
	 * @since 1.0.0
	 */
	private function check() {

		// Loop through requirements
		foreach ( $this->requirements as $dependency => $properties ) {

			switch ( $dependency ) {

				// PHP.
				case 'php' :
					$version = phpversion();
					break;

				// WP.
				case 'wp' :
					$version = get_bloginfo( 'version' );
					break;

				// Unknown.
				default :
					$version = false;
					break;
			}

			if ( ! empty( $version ) ) {
				$this->requirements[ $dependency ] = array_merge( $this->requirements[ $dependency ], array(
					'current' => $version,
					'checked' => true,
					'met'     => version_compare( $version, $properties['minimum'], '>=' )
				) );
			}
		}
	}

	/**
	 * Determine whether all requirements have been met.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if all requirements have been met, otherwise false.
	 */
	public function requirements_met() {

		// Run the check.
		$this->check();

		// Default to true (any false below wins).
		$result  = true;
		$to_meet = wp_list_pluck( $this->requirements, 'met' );

		// Look for unmet dependencies, and exit if so.
		foreach ( $to_meet as $met ) {
			if ( empty( $met ) ) {
				$result = false;
				continue;
			}
		}

		// Return
		return $result;
	}

}
