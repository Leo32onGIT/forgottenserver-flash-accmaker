<?php
if(!defined('INITIALIZED'))
	exit;

$main_content .= '<div class="panel panel-default">
	<div class="panel-heading"><h3 class="panel-title">Page not found</h3></div>
	<div class="panel-body">
		<div class="error-404">
			<h2 class="font-bold">Oops 404! That page can’t be found.</h2>
			<div class="error-desc">
				<p>Sorry, but the page you are looking for was either not found or does not exist. <br/>
				Try refreshing the page or click the button below to go back to the Homepage.</p><br>
				<div class="text-center">
					<a href="?view=account" class="btn btn-default">Back</a>
				</div>
			</div>
		</div>
	</div>
</div>';
