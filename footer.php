<?php require_once 'classes/class.locale.php';
$locale = new CpanelLocale();?>

	<div id="push"></div>
	</div><!--div class container-opened in header.php-->
        </div><!--wrap-->
		<footer>
		<div class="container">
			<div class="row">
				<div class="col-xs-12 text-center">
					<p>Creative Commons - BY </p>
					<p class="social">
						<a href="https://github.com/MaadixNet" target="_blank">
							<span class="fa-stack fa-lg">
								<i class="fa fa-circle fa-stack-2x"></i>
								<i class="fa fa-github fa-stack-1x fa-inverse"></i>
							</span>
						</a>
					</p>
				</div>
                                 <div class="col-sm-2">
                                  <?php echo $locale->locale_select();?>
                                </div>

			</div>
		</div>
	</footer>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script> <!-- load jquery via CDN -->
    <script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/customscript.js"></script>
  </body>
</html>
