<?php global $apollo_options, $apollo_theme_options, $apollo; ?>
<?php if(isset($apollo_options["subscription_embed"]) && $apollo_options["subscription_embed"] != "") : ?>
    <div class="subscribe-form">
    	<?php if($apollo_options["subscription_title"] != "") : ?>
	    	<h5><?php echo $apollo_options["subscription_title"]; ?></h5>
    	<?php endif; ?>
    	<?php echo $apollo_options["subscription_embed"]; ?>
    </div>
<?php endif; ?>