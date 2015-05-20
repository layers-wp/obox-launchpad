<?php global $apollo_options, $apollo_theme_options, $apollo; ?>
<?php if($apollo_options["title"] || $apollo_options["intro"]) : ?>
    <div class="copy <?php if($apollo_options["video"]) : ?>top-margin<?php endif;?>">
    	<h2 class="post-title"><?php echo $apollo_options["title"]; ?></h2>
        <p><?php echo $apollo_options["intro"]; ?></p>
	</div>			
<?php endif; ?>