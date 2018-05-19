<?php
/**
 * Ensemble Overview Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core;

use Ensemble\Components\Contests;
use Ensemble\Components\Venues;
use function Ensemble\load_view;
?>
<h1 class="wp-heading-inline"><?php esc_html_e( 'Ensemble', 'ensemble' ); ?></h1>

<?php
/**
 * Fires in the head of custom Ensemble views to display admin notices.
 *
 * This would be the preferred method for showing notices vs using the
 * standard 'admin_notices' hook because of the wonky placement above
 * the header.
 *
 * @since 1.0.0
 */
do_action( 'ensemble_admin_notices' );
?>

<div class="bootstrap-iso">

	<hr>

	<div class="progress">
		<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="5" style="width: 20%;">
			Step 1 of 5
		</div>
	</div>

	<div class="navbar pl-0 ensemble-wizard">
		<ul class="nav nav-pills" role="tablist">
			<li class="nav-item btn btn-light mr-2">
				<a href="#step1" data-toggle="tab" role="tab" data-step="1">1. Add a Venue</a>
			</li>
			<li class="nav-item btn btn-light ml-2 mr-2">
				<a href="#step2" data-toggle="tab" role="tab" data-step="2">2. Add a Contest</a>
			</li>
			<li class="nav-item btn btn-light ml-2 mr-2">
				<a href="#step3" data-toggle="tab" role="tab" data-step="3">3. Step 3</a>
			</li>
			<li class="nav-item btn btn-light ml-2 mr-2">
				<a href="#step4" data-toggle="tab" role="tab" data-step="4">4. Step 4</a>
			</li>
			<li class="nav-item btn btn-light ml-2">
				<a href="#step5" data-toggle="tab" role="tab" data-step="5">5. Step 5</a>
			</li>
		</ul>
	</div>

	<div class="tab-content">
		<div class="tab-pane fade in active show" id="step1">

			<div class="well">

				<?php load_view( new Venues\Admin\Actions, 'add-wizard' ); ?>

			</div>

			<a class="btn btn-default btn-lg next" href="#">Continue</a>
		</div>
		<div class="tab-pane fade" id="step2">
			<div class="well">

				<?php load_view( new Contests\Admin\Actions, 'add-wizard' ); ?>

			</div>
			<a class="btn btn-default next" href="#">Continue</a>
		</div>
		<div class="tab-pane fade" id="step3">
			<div class="well">

				<?php ?>

			</div>
			<a class="btn btn-default next" href="#">Continue</a>
		</div>
		<div class="tab-pane fade" id="step4">
			<div class="well">

				<?php ?>

			</div>
			<a class="btn btn-default next" href="#">Continue</a>
		</div>
		<div class="tab-pane fade" id="step5">
			<div class="well">

				<?php ?>

			</div>
			<a class="btn btn-success first" href="#">Start over</a>
		</div>
	</div>

</div>
