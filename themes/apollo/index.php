<?php get_header(); ?>
    <div id="header-container">
        <div id="header">
            <div class="logo">
                <h1><a href="<?php echo get_home_url(); ?>">	
                	<?php if(isset($apollo_theme_options["logo"]) && $apollo_theme_options["logo"] != "") : 
	                	echo '<img src="' . $apollo_theme_options["logo"] . '" />';
                	else :
	                	bloginfo("name");                 	
                	endif; ?>
                </a></h1>
                <?php if(isset($apollo_options["display_tagline"])) : ?>
                	<p class="tagline"><?php bloginfo("description"); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div id="content-container">
        <div id="content">
        	<?php $homepage = get_option( "apollo_order_options" );
        	if(!empty($homepage)) :
	        	foreach($homepage as $item => $template) :
	        		get_template_part($template);
	        	endforeach;
        	endif; ?>
        </div>
    </div>
 <?php get_footer(); ?>