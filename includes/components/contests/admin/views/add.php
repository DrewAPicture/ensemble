<?php
/**
 * Add Contest Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests\Admin;

use function Ensemble\Components\Contests\{get_allowed_types, get_allowed_statuses};
use function Ensemble\{html};
?>
<div class="wrap bootstrap-iso">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Add Contest', 'ensemble' ); ?></h1>

	<div class="row">
		<div class="col-12 col-xl-8">
			<form method="post">
				<div class="card mb-3 md-md-5">
					<div class="card-body">
						<div class="form-group">
							<?php
							html()->text( array(
								'id'    => 'contest-name',
								'label' => __( 'Name', 'ensemble' ),
								'class' => array( 'form-control' ),
							) );
							?>
						</div>

						<div class="form-group">
							<?php
							html()->editor( array(
								'id'      => 'contest-desc',
								'label'   => __( 'Description', 'ensemble' ),
								'context' => 'add',
								'class'   => array( 'form-control' ),
							) );
							?>
						</div>
					</div>
				</div>

				<div class="card mb-3 md-md-5">
					<div class="card-body">
						<div class="form-group">
							<?php
							html()->select( array(
								'id'               => 'contest-venues',
								'label'            => __( 'Venue(s)', 'ensemble' ),
								'class'            => array( 'form-control' ),
								'options'          => array(),
								'show_option_all'  => false,
								'show_option_none' => false,
							) );
							?>
						</div>

						<div class="form-row form-group">
							<div class="col">
								<?php
								html()->text( array(
									'id'    => 'contest-start-date',
									'label' => __( 'Start Date', 'ensemble' ),
									'class' => array( 'form-control' ),
								) );
								?>
							</div>
							<div class="col">
								<?php
								html()->text( array(
									'id'    => 'contest-end-date',
									'label' => __( 'End Date', 'ensemble' ),
									'class' => array( 'form-control' ),
								) );
								?>
							</div>
						</div>
					</div>
				</div>

				<div class="card mb-3 md-md-5">
					<div class="card-body">
						<div class="form-row form-group">
							<div class="col">
								<?php
								html()->select( array(
									'id'               => 'contest-type',
									'label'            => __( 'Type', 'ensemble' ),
									'class'            => array( 'form-control' ),
									'selected'         => 'regular',
									'options'          => get_allowed_types(),
									'show_option_all'  => false,
									'show_option_none' => false,
								) );
								?>
							</div>
							<div class="col">
								<?php
								html()->select( array(
									'id'               => 'contest-status',
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

						<div class="form-row">
							<?php
							html()->input( 'url', array(
								'id'    => 'contest-external',
								'label' => __( 'External Contest URL', 'ensemble' ),
								'class' => array( 'form-control' ),
							) );
							?>
						</div>
					</div>
				</div>

				<div class="pb-5">
					<?php
					wp_nonce_field( 'ensemble-add-contest-nonce', 'ensemble-add-contest-nonce' );

					// Add Contest button
					html()->input( 'submit', array(
						'name'  => 'ensemble-add-contest',
						'value' => 'Add Contest',
						'class' => array( 'btn-dark', 'btn', 'btn-primary' )
					) );
					?>
				</div>
			</form>
		</div>
	</div>
</div>
