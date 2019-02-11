<?php
/**
 * Edit Instructor Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2019, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.1.0
 */
namespace Ensemble\Components\People\Instructors\Admin;

use Ensemble\Components\Units\Setup as Units;
use function Ensemble\{html, clean_admin_url};

$user_id    = absint( $_REQUEST['user_id'] ?? 0 );
$instructor = get_userdata( $user_id );
?>
<h1 class="wp-heading-inline"><?php esc_html_e( 'Edit Unit Instructor', 'ensemble' ); ?></h1>
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

		<?php if ( is_wp_error( $instructor ) ) : ?>

			<?php foreach ( $instructor->get_error_messages() as $message ) : ?>
				<p><?php echo esc_html( $message ); ?></p>
			<?php endforeach; ?>

		<?php else : ?>

			<form method="post" data-parsley-validate>
				<div class="card mb-3 md-md-5 pt-4">
					<div class="form-group">
						<?php
						html()->text( array(
							'id'    => 'instructor-name',
							'label' => __( 'Name', 'ensemble' ),
							'class' => array( 'form-control' ),
							'value' => $instructor->display_name,
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
								'value' => $instructor->user_email,
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

							$selected = wp_get_object_terms( $instructor->ID, 'ensemble_unit', array( 'fields' => 'ids' ) );

							html()->select( array(
								'id'               => 'instructor-units',
								'name'             => 'instructor-units[]',
								'label'            => __( 'Competing Unit(s)', 'ensemble' ),
								'class'            => array( 'form-control' ),
								'multiple'         => true,
								'selected'         => $selected,
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
					wp_nonce_field( 'ensemble-update-instructor-nonce', 'ensemble-update-instructor-nonce' );

					// User ID (hidden).
					html()->hidden( array(
						'name'  => 'user-id',
						'value' => $instructor->id,
					) );
					?>
					<div class="pb-5 d-flex justify-content-between">

						<?php
						// Update Unit Instructor button
						html()->input( 'submit', array(
							'name'  => 'ensemble-update-instructor',
							'value' => 'Update Unit Instructor',
							'class' => array( 'btn-dark', 'btn', 'btn-primary' )
						) );

						$base_url = add_query_arg( 'page', 'ensemble-admin-people-instructors', admin_url( 'admin.php' ) );

						// Delete link.
						html()->button( array(
							'url'   => add_query_arg( array( 'ensbl-view' => 'delete', 'user_id' => $instructor->ID ), $base_url ),
							'class' => array( 'btn', 'btn-link', 'btn-link-delete', 'text-danger' ),
							'value' => _x( 'Delete', 'instructor', 'ensemble' ),
							'aria'  => array(
								'label' => __( 'Delete Instructor', 'ensemble' ),
							),
						) );
						?>
					</div>
				</div>
			</form>

		<?php endif; ?>
	</div>
</div>
