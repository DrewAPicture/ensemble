<?php
/**
 * Delete Director Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\People\Directors\Admin;

use Ensemble\Components\People\Directors;
use function Ensemble\create_date;
use function Ensemble\{html};

$user_id  = absint( $_REQUEST['user_id'] ?? 0 );
$director = get_userdata( $user_id );
?>
<h1 class="wp-heading-inline"><?php esc_html_e( 'Delete Unit Director', 'ensemble' ); ?></h1>

<?php do_action( 'admin_notices' ); ?>

<div class="row">
	<div class="col-12 col-xl-8">
		<form id="ensemble-delete-contest" method="post">

			<?php if ( ! $director ) : ?>

				<?php esc_html_e( 'Invalid unit director. Please try again.', 'ensemble' ); ?>

			<?php else : ?>

				<div class="card mb-3 md-md-5 pt-4">
					<h5><?php esc_html_e( 'Director Information', 'ensemble' ); ?></h5>

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
								<td><?php echo esc_html( $director->display_name ); ?></td>
								<td><?php echo esc_html( $director->user_email ); ?></td>
								<td>
									<?php
									$units = wp_get_object_terms( $director->ID, 'ensemble_unit', array( 'fields' => 'names' ) );

									if ( ! empty( $units ) ) {
										echo implode( ', ', $units );
									}
									?>
								</td>
								<td><?php echo create_date( $director->user_registered, 'wp' )->format( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ); ?></td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div class="card mb-3 md-md-5 pt-4">
					<h5><?php esc_html_e( 'Are you sure?', 'ensemble' ); ?></h5>

					<p><?php esc_html_e( 'Note: No units associated with the director will be affected.', 'ensemble' ); ?></p>

					<div class="form-group">
						<div class="form-check mb-3">
							<?php
							// Radio button: Yes.
							html()->radio( array(
								'id'          => 'director-delete-yes',
								'name'        => 'director-delete',
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
								'id'          => 'director-delete-no',
								'name'        => 'director-delete',
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
						'value' => $director->ID,
					) );

					wp_nonce_field( 'ensemble-delete-director-nonce', 'ensemble-delete-director-nonce' );

					// Submit button.
					html()->input( 'submit', array(
						'name'  => 'ensemble-delete-director',
						'value' => 'Submit',
						'class' => array( 'btn-dark', 'btn', 'btn-primary' ),
					) );
					?>
				</div>
			<?php endif; ?>
		</form>
	</div>
</div>
