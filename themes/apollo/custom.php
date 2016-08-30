<?php global $apollo_options, $apollo_theme_options, $apollo; ?>
<?php if(isset($apollo_options["custom_html"]) && $apollo_options["custom_html"] != "") : ?>
    <div class="custom_html">
    	<?php if($apollo_options["custom_html"] != "") : ?>
	    	<center><?php echo $apollo_options["custom_html"]; ?></center>
</div>
    	<?php endif; ?>
  <?php endif; ?>  