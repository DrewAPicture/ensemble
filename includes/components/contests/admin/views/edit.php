<?php
/**
 * Edit Contest Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests\Admin;

use function Ensemble\Components\Contests\{get_contest, get_allowed_statuses, get_allowed_types};
use function Ensemble\{html};

$contest_id = $_REQUEST['contest_id'] ?? 0;
$contest    = get_contest( $contest_id );
?>
<div class="wrap bootstrap-iso">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Edit Contest', 'ensemble' ); ?></h1>

	<div class="row">
		<div class="col-12 col-xl-8">

			<?php if ( is_wp_error( $contest ) ) : ?>

				<?php foreach ( $contest->get_error_messages() as $message ) : ?>
					<p><?php echo esc_html( $message ); ?></p>
				<?php endforeach; ?>

			<?php else : ?>

				<form method="post">
					<div class="card mb-3 md-md-5">
						<div class="card-body">
							<div class="form-group">
								<?php
								html()->text( array(
									'id'    => 'contest-name',
									'label' => __( 'Name', 'ensemble' ),
									'class' => array( 'form-control' ),
									'value' => $contest->name,
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
									'value'   => $contest->description,
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
									'selected'         => $contest->venues,
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
										'value' => $contest->start_date,
									) );
									?>
								</div>
								<div class="col">
									<?php
									html()->text( array(
										'id'    => 'contest-end-date',
										'label' => __( 'End Date', 'ensemble' ),
										'class' => array( 'form-control' ),
										'value' => $contest->end_date,
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
										'selected'         => $contest->type,
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
										'selected'         => $contest->status,
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
									'value' => $contest->external,
								) );
								?>
							</div>
						</div>
					</div>
				</form>

			<?php endif; ?>
		</div>
	</div>
</div>
