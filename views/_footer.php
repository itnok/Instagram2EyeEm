
		<hr>
		
		<div class="donation">
			<h4>Creating this free web tool involved my energy, my passion and my spare time.</h4>
			If you think this tool is useful and if you would like to support my work and my passion...<br />
			<b>Please donate what you think it deserves.</b><br />
			There is no minimum and no maximum allowed amount... just donate what you think is fair!<br />
			&nbsp;
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="7Y29YJA5CZX5E">
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
			</form>
			<b>Thank you!</b>
		</div>

		</div> <!-- /jumbotron -->
    </div> <!-- /container -->
	<div class="container-footer row-fluid">
		<div class="footer span12">
			<p>&copy; Simone Conti @ dev.itnok.com 2012-2013</p>
			<p><a href="http://twitter.com/itnok" class="twitter-link">Follow me on Twitter</a></p>
			<p>
				Thank you to EyeEm staff for believing in my project and for allowing me to have writing access to their API<br />
				All trademarks are the property of their respective owners.
			</p>
		</div>
	</div>


    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script  type="text/javascript" src="lib/js/bootstrap/docs/assets/js/jquery.js"></script>
    <script  type="text/javascript" src="lib/js/bootstrap/docs/assets/js/bootstrap-transition.js"></script>
    <script  type="text/javascript" src="lib/js/bootstrap/docs/assets/js/bootstrap-alert.js"></script>
    <script  type="text/javascript" src="lib/js/bootstrap/docs/assets/js/bootstrap-modal.js"></script>
    <script  type="text/javascript" src="lib/js/bootstrap/docs/assets/js/bootstrap-dropdown.js"></script>
    <script  type="text/javascript" src="lib/js/bootstrap/docs/assets/js/bootstrap-scrollspy.js"></script>
    <script  type="text/javascript" src="lib/js/bootstrap/docs/assets/js/bootstrap-tab.js"></script>
    <script  type="text/javascript" src="lib/js/bootstrap/docs/assets/js/bootstrap-tooltip.js"></script>
    <script  type="text/javascript" src="lib/js/bootstrap/docs/assets/js/bootstrap-popover.js"></script>
    <script  type="text/javascript" src="lib/js/bootstrap/docs/assets/js/bootstrap-button.js"></script>
    <script  type="text/javascript" src="lib/js/bootstrap/docs/assets/js/bootstrap-collapse.js"></script>
    <script  type="text/javascript" src="lib/js/bootstrap/docs/assets/js/bootstrap-carousel.js"></script>
    <script  type="text/javascript" src="lib/js/bootstrap/docs/assets/js/bootstrap-typeahead.js"></script>
    <script  type="text/javascript" src="lib/js/bootstrap/docs/assets/js/holder/holder.js"></script>
    <?php
	    // Add more view-specific js files
    	foreach( $js_append as $js ) {
	    	echo "\t" . '<script  type="text/javascript" src="' . $js . '"></script>' . "\n";
    	}
    ?>
	<a href="https://github.com/itnok"><img src="https://s3.amazonaws.com/github/ribbons/forkme_right_orange_ff7600.png" alt="Fork me on GitHub" class="forkme hidden-phone"></a>
</body>
</html>
