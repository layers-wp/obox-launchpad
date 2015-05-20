<?php global $apollo_options, $apollo_theme_options, $apollo; ?>
<?php if(isset($apollo_options["video"]) && $apollo_options["video"] != "") :
    $embed_code = apply_filters('the_content', '[embed width="740" height="555"]'.$apollo_options["video"].'[/embed]');
    $embed_code = strip_tags($embed_code, '<iframe>, <object>'); ?>
    <div class="video"><?php echo $embed_code; ?></div>
<?php endif; ?>