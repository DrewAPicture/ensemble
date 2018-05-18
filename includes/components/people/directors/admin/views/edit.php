<?php
/**
 * Edit Director Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\People\Directors\Admin;

use Ensemble\Components\Units\Setup as Units;
use function Ensemble\{html};

$user_id  = absint( $_REQUEST['user_id'] ?? 0 );
$director = get_userdata( $user_id );
?>
<h1 class="wp-heading-inline"><?php esc_html_e( 'Edit Unit Director', 'ensemble' ); ?></h1>

<div class="row">
	<div class="col-12 col-xl-8">

		<?php if ( is_wp_error( $director ) ) : ?>

			<?php foreach ( $director->get_error_messages() as $message ) : ?>
				<p><?php echo esc_html( $message ); ?></p>
			<?php endforeach; ?>

		<?php else : ?>

			<form method="post">
				<div class="card mb-3 md-md-5 pt-4">
					<div class="form-group">
						<?php
						html()->text( array(
							'id'    => 'director-name',
							'label' => __( 'Name', 'ensemble' ),
							'class' => array( 'form-control' ),
							'value' => $director->display_name,
						) );
						?>
					</div>

					<div class="form-row form-group">
						<div class="col">
							<?php
							html()->input( 'email', array(
								'id'    => 'director-email',
								'label' => __( 'Email', 'ensemble' ),
								'class' => array( 'form-control' ),
								'value' => $director->user_email,
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

							$selected = get_terms( array(
								'taxonomy'   => ( new Units )->get_taxonomy_slug(),
								'hide_empty' => false,
								'fields'     => 'term_id',
								'object_ids' => $director->ID,
							) );

							log_it( $selected );
							html()->select( array(
								'id'               => 'director-units',
								'name'             => 'director-units[]',
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
					wp_nonce_field( 'ensemble-update-contest-nonce', 'ensemble-update-contest-nonce' );

					// Contest ID (hidden).
					html()->hidden( array(
						'name'  => 'contest-id',
						'value' => $director->id,
					) );
					?>
					<div class="pb-5 d-flex justify-content-between">

						<?php
						// Update Contest button
						html()->input( 'submit', array(
							'name'  => 'ensemble-update-contest',
							'value' => 'Update Contest',
							'class' => array( 'btn-dark', 'btn', 'btn-primary' )
						) );

						$base_url = add_query_arg( 'page', 'ensemble-admin-contests', admin_url( 'admin.php' ) );

						// Delete link.
						html()->button( array(
							'url'   => add_query_arg( array( 'ensbl-view' => 'delete', 'contest_id' => $director->id ), $base_url ),
							'class' => array( 'btn', 'btn-link', 'btn-link-delete', 'text-danger' ),
							'value' => _x( 'Delete', 'contest', 'ensemble' ),
							'aria'  => array(
								'label' => __( 'Delete contest', 'ensemble' ),
							),
						) );
						?>
					</div>
				</div>
			</form>

		<?php endif; ?>
	</div>
</div>
