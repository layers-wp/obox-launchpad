<?php global $apollo_social_options; ?>
<?php // if(isset($apollo_social_options["social-active"])) : ?>
    <ul class="social">
        <?php if(isset($apollo_social_options["facebook"]) && $apollo_social_options["facebook"] !== "") : ?>
        	<li><a href="<?php echo esc_url($apollo_social_options["facebook"]); ?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/images/facebook_32.png" alt="" /></a></li>
        <?php endif; ?>
        <?php if(isset($apollo_social_options["vimeo"]) && $apollo_social_options["vimeo"] !== ""): ?>
        	<li><a href="<?php echo esc_url($apollo_social_options["vimeo"]); ?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/images/vimeo_32.png" alt="" /></a></li>
        <?php endif; ?>
        <?php if(isset($apollo_social_options["tumblr"]) && $apollo_social_options["tumblr"] !== ""): ?>
        	<li><a href="<?php echo esc_url($apollo_social_options["tumblr"]); ?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/images/tumblr_32.png" alt="" /></a></li>
        <?php endif; ?>
        <?php if(isset($apollo_social_options["wordpress"]) && $apollo_social_options["wordpress"] !== ""): ?>
        	<li><a href="<?php echo esc_url($apollo_social_options["wordpress"]); ?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/images/wordpress_32.png" alt="" /></a></li>
        <?php endif; ?>
        <?php if(isset($apollo_social_options["twitter"]) && $apollo_social_options["twitter"] !== ""): ?>
        	<li><a href="<?php echo esc_url($apollo_social_options["twitter"]); ?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/images/twitter_32.png" alt="" /></a></li>
        <?php endif; ?>
    </ul>
<?php // endif; ?>