<?php
/**
 * Add Instructor Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2019, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.1.0
 */
namespace Ensemble\Components\People\Instructors\Admin;

use Ensemble\Components\People\Instructors\Database;
use Ensemble\Components\Units\Setup as Units;
use function Ensemble\{html, clean_admin_url};

?>
<h1 class="wp-heading-inline"><?php esc_html_e( 'Add a Unit Instructors', 'ensemble' ); ?></h1>
<a href="<?php echo esc_url( add_query_arg( array( 'ensbl-view' => 'overview' ), clean_admin_url() ) ); ?>" class="page-title-action" role="button">
	<?php esc_html_e( 'Return to All Instructors', 'ensemble' ); ?>
</a>

<hr class="wp-header-end" />

<?php
/** This action is documented in includes/core/admin/views/overview.php */
do_action( 'ensemble_admin_notices' );
?>

<div class="row">
	<div class="col-12 col-xl-8">
		<?php if ( 0 === ( new Database )->count() ) : ?>
			<div class="alert alert-info" role="alert">
				<h5><?php esc_html_e( 'Howdy!', 'ensemble' ); ?></h5>
				<?php esc_html_e( 'It looks like you&#8217;re adding your first unit instructor. To get started, just choose a username, enter an email, select a unit and that&#8217;s it. If you haven&#8217;t created any units yet, not to worry, you can always come back and set them later.', 'ensemble' ); ?>
			</div>
		<?php endif; ?>

		<form method="post" data-parsley-validate>
			<div class="card mb-3 md-md-5 pt-4">
				<div class="form-group">
					<?php
					html()->text( array(
						'id'    => 'instructor-name',
						'label' => __( 'Name', 'ensemble' ),
						'class' => array( 'form-control' ),
						'data'  => array(
							'parsley-required'         => true,
							'parsley-required-message' => __( 'A name is required.', 'ensemble' ),
						),
					) );
					?>
				</div>

				<div class="form-row form-group">
					<div class="col">
						<?php
						html()->input( 'email', array(
							'id'    => 'instructor-email',
							'label' => __( 'Email', 'ensemble' ),
							'class' => array( 'form-control' ),
							'data'  => array(
								'parsley-required'         => true,
								'parsley-required-message' => __( 'An email address is required.', 'ensemble' ),
							),
						) );
						?>
					</div>
					<div class="col">
						<?php
						$units = get_terms( array(
							'taxonomy'   => ( new Units )->get_taxonomy_slug(),
							'hide_empty' => false,
							'fields'     => 'id=>name',
							'number'     => 500,
						) );

						html()->select( array(
							'id'               => 'instructor-units',
							'name'             => 'instructor-units[]',
							'label'            => __( 'Competing Unit(s)', 'ensemble' ),
							'class'            => array( 'form-control' ),
							'multiple'         => true,
							'options'          => $units,
							'show_option_all'  => false,
							'show_option_none' => false,
						) );
						?>
					</div>
				</div>

			</div>

			<div class="pb-5">
				<?php
				wp_nonce_field( 'ensemble-add-instructor-nonce', 'ensemble-add-instructor-nonce' );

				// Add Instructor button
				html()->input( 'submit', array(
					'name'  => 'ensemble-add-instructor',
					'value' => __( 'Add Unit Instructor', 'ensemble' ),
					'class' => array( 'btn-dark', 'btn', 'btn-primary' )
				) );
				?>
			</div>
		</form>
	</div>
</div>
