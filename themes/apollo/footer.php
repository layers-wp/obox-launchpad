<?php global $apollo_options, $apollo_theme_options, $apollo; ?>
    <div id="footer-container">
        <div id="footer">
            <div class="footer-text">
                <?php if(isset($apollo_options["copyright_text"])) : ?>
	                <p><?php echo $apollo_options["copyright_text"]; ?></p>
                <?php endif; ?>
                <?php if(isset($apollo_options["show_obox_logo"])) : ?>
                	<a class="obox-credit" href="http://www.obox-design.com">
                    	<img src="<?php bloginfo('template_directory'); ?>/images/layout/obox-logo.png" alt="WordPress Themes by Obox" />
					</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
</div>
<?php wp_footer(); ?>
</body>
</html>