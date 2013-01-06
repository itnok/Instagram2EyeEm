		<hr>
		
		<div class="footer">
		  <p>&copy; Simone Conti @ dev.itnok.com 2012</p>
		</div>

    </div> <!-- /container -->

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
