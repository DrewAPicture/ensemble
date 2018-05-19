<?php
/**
 * Add Venue Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Venues\Admin;

use Ensemble\Components\Venues\Database;
use function Ensemble\Components\Venues\{get_allowed_statuses, get_allowed_types};
use function Ensemble\{html};
?>
<h1 class="wp-heading-inline"><?php esc_html_e( 'Add Your First Venue', 'ensemble' ); ?></h1>

<div class="row">
	<div class="col-12 col-xl-8">
		<div class="alert alert-info" role="alert">
			<?php esc_html_e( 'Getting started is super simple, just choose a name, enter an address, and assign a venue type.', 'ensemble' ); ?>
		</div>

		<form method="post" data-parsley-validate>
			<div class="card mb-3 md-md-5 pt-4">
				<div class="form-group">
					<?php
					// Name.
					html()->text( array(
						'id'    => 'venue-name',
						'label' => __( 'Name', 'ensemble' ),
						'class' => array( 'form-control' ),
						'data'  => array(
							'parsley-required'         => true,
							'parsley-required-message' => __( 'A venue name is required.', 'ensemble' ),
						),
					) );
					?>
				</div>

				<div class="form-row form-group">
					<div class="col">
						<?php
						$types = get_allowed_types();

						// If only one type, don't bother with a select.
						if ( 1 === count( $types ) ) :
							$first = key( $types );
							html()->text( array(
								'id'       => 'venue-type',
								'label'    => _x( 'Type', 'venue', 'ensemble' ),
								'class'    => array( 'form-control', 'form-control-plaintext' ),
								'value'    => $types[ $first ],
								'readonly' => true,
							) );
						else :
							html()->select( array(
								'id'               => 'venue-type',
								'label'            => _x( 'Type', 'venue', 'ensemble' ),
								'class'            => array( 'form-control' ),
								'selected'         => 'regular',
								'options'          => $types,
								'show_option_all'  => false,
								'show_option_none' => false,
							) );
						endif;
						?>
					</div>
					<div class="col">
						<?php
						html()->select( array(
							'id'               => 'venue-status',
							'label'            => __( 'Status', 'ensemble' ),
							'class'            => array( 'form-control' ),
							'selected'         => 'draft',
							'options'          => get_allowed_statuses(),
							'show_option_all'  => false,
							'show_option_none' => false,
						) );
						?>
					</div>
				</div>

				<div class="form-group">
					<?php
					// Address.
					html()->textarea( array(
						'id'    => 'venue-address',
						'label' => __( 'Address', 'ensemble' ),
						'class' => array( 'form-control', 'form-textarea' ),
						'data'  => array(
							'parsley-required'         => true,
							'parsley-required-message' => __( 'A venue address is required.', 'ensemble' ),
						),
					) );
					?>
				</div>
			</div>

			<div class="pb-5">
				<?php
				wp_nonce_field( 'ensemble-add-venue-nonce', 'ensemble-add-venue-nonce' );

				// Add Venue button
				html()->input( 'submit', array(
					'name'  => 'ensemble-add-venue',
					'value' => 'Add Venue',
					'class' => array( 'btn-dark', 'btn', 'btn-primary' )
				) );
				?>
			</div>
		</form>
	</div>
</div>
