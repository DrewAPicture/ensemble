<?php
/**
 * People Overview Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core;

use function Ensemble\{load_view};
?>
<div class="bootstrap-iso">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'People', 'ensemble' ); ?></h1>

	<div class="d-flex flex-row h-100 mt-2">
		<ul class="nav nav-tabs nav-tabs--vertical nav-tabs--left mr-0 ml-0 w-15 text-left" role="navigation">
			<li class="nav-item">
				<a href="#directors" class="nav-link btn text-left bg-light active" data-toggle="tab" role="tab" aria-controls="directors">
					<?php esc_html_e( 'Unit Directors', 'ensemble' ); ?>
				</a>
			</li>
			<li class="nav-item">
				<a href="#circuit-staff" class="nav-link btn text-left bg-light btn-light" data-toggle="tab" role="tab" aria-controls="circuit-staff">
					<?php esc_html_e( 'Circut Staff', 'ensemble' ); ?>
				</a>
			</li>
			<li class="nav-item">
				<a href="#settings" class="nav-link btn text-left bg-light" data-toggle="tab" role="tab" aria-controls="settings">
					<?php esc_html_e( 'Settings', 'ensemble' ); ?>
				</a>
			</li>
		</ul>
		<div class="tab-content bg-white d-flex flex-fill h-100">
			<div class="tab-pane fade show active p-4" id="directors" role="tabpanel">
				<h2 class="sr-only"><?php esc_html_e( 'Unit Directors', 'ensemble' ); ?></h2>
			</div>
			<div class="tab-pane fade p-4" id="circuit-staff" role="tabpanel">
				<h2 class="sr-only"><?php esc_html_e( 'Circut Staff', 'ensemble' ); ?></h2>
			</div>
			<div class="tab-pane fade p-4" id="settings" role="tabpanel">
				<h2 class="sr-only"><?php esc_html_e( 'Settings', 'ensemble' ); ?></h2>
			</div>
		</div>
	</div>

</div>
