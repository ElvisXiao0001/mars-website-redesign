<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<div class="cursor" id="js-cursor">
	<div class="cursor__wrapper">
		<!-- circles -->
		<div class="cursor__follower">
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
				<circle id="inner" cx="25" cy="25" r="24" style="opacity: 0.6;"></circle>
				<circle id="outer" cx="25" cy="25" r="24" style="stroke-dashoffset: 252.327; stroke-dasharray: 0px, 999999px;"></circle>
			</svg>
		</div>
		<!-- - circles -->
		<!-- arrows -->
		<div class="cursor__arrows">
			<div class="cursor__arrow cursor__arrow_up material-icons keyboard_arrow_up"></div>
			<div class="cursor__arrow cursor__arrow_down material-icons keyboard_arrow_down"></div>
			<div class="cursor__arrow cursor__arrow_left material-icons keyboard_arrow_left"></div>
			<div class="cursor__arrow cursor__arrow_right material-icons keyboard_arrow_right"></div>
		</div>
		<!-- - arrows -->
		<!-- label holder -->
		<div class="cursor__label"></div>
		<!-- - label holder -->
		<!-- icon holder -->
		<div class="cursor__icon"></div>
		<!-- - icon holder -->
	</div>
</div>
