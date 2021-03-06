<?php
/**
 * Delete Instructor Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2019, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.1.0
 */
namespace Ensemble\Components\People\Instructors\Admin;

use Ensemble\Components\People\Instructors;
use Ensemble\Util\Date;
use function Ensemble\{html, clean_admin_url};

$user_id  = absint( $_REQUEST['user_id'] ?? 0 );
$instructor = get_userdata( $user_id );
?>
<h1 class="wp-heading-inline"><?php esc_html_e( 'Delete Unit Instructor', 'ensemble' ); ?></h1>
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
		<form id="ensemble-delete-contest" method="post">

			<?php if ( ! $instructor ) : ?>

				<?php esc_html_e( 'Invalid unit instructor. Please try again.', 'ensemble' ); ?>

			<?php else : ?>

				<div class="card mb-3 md-md-5 pt-4">
					<h5><?php esc_html_e( 'Instructor Information', 'ensemble' ); ?></h5>

					<div class="table-responsive">
						<table class="table">
							<thead>
							<tr>
								<th><?php esc_html_e( 'Name', 'ensemble' ); ?></th>
								<th><?php esc_html_e( 'Email', 'ensemble' ); ?></th>
								<th><?php esc_html_e( 'Unit(s)', 'ensemble' ); ?></th>
								<th><?php esc_html_e( 'Date Created', 'ensemble' ); ?></th>
							</tr>
							</thead>
							<tbody>

							<tr>
								<td><?php echo esc_html( $instructor->display_name ); ?></td>
								<td><?php echo esc_html( $instructor->user_email ); ?></td>
								<td>
									<?php
									$units = wp_get_object_terms( $instructor->ID, 'ensemble_unit', array( 'fields' => 'names' ) );

									if ( ! empty( $units ) ) {
										echo implode( ', ', $units );
									}
									?>
								</td>
								<td><?php echo Date::UTC_to_WP( $instructor->user_registered, get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ); ?></td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div class="card mb-3 md-md-5 pt-4">
					<h5><?php esc_html_e( 'Are you sure?', 'ensemble' ); ?></h5>

					<p><?php esc_html_e( 'Note: No units associated with the instructor will be affected.', 'ensemble' ); ?></p>

					<div class="form-group">
						<div class="form-check mb-3">
							<?php
							// Radio button: Yes.
							html()->radio( array(
								'id'          => 'instructor-delete-yes',
								'name'        => 'instructor-delete',
								'label'       => __( 'Yes, delete', 'ensemble' ),
								'label_class' => 'form-check-label',
								'value'       => 'yes',
								'class'       => array( 'form-check-input' ),
							) );
							?>
						</div>

						<div class="form-check">
							<?php
							// Radio button: No
							html()->radio( array(
								'id'          => 'instructor-delete-no',
								'name'        => 'instructor-delete',
								'label'       => __( 'No, cancel', 'ensemble' ),
								'label_class' => 'form-check-label',
								'class'       => array( 'form-check-input' ),
								'value'       => 'no',
								'checked'     => true,
							) );
							?>
						</div>
					</div>
				</div>
				<div class="pb-5">
					<?php
					// User ID (hidden).
					html()->hidden( array(
						'name'  => 'user-id',
						'value' => $instructor->ID,
					) );

					wp_nonce_field( 'ensemble-delete-instructor-nonce', 'ensemble-delete-instructor-nonce' );

					// Submit button.
					html()->input( 'submit', array(
						'name'  => 'ensemble-delete-instructor',
						'value' => 'Submit',
						'class' => array( 'btn-dark', 'btn', 'btn-primary' ),
					) );
					?>
				</div>
			<?php endif; ?>
		</form>
	</div>
</div>
