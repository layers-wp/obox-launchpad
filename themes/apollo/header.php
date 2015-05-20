<?php global $apollo_options, $apollo_theme_options, $apollo; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title><?php bloginfo("name"); ?></title>

<!-- WordPress Headers -->
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />

<!-- Launchpad Headers -->
<link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php bloginfo('template_directory'); ?>/responsive.css" rel="stylesheet" type="text/css" />
<?php // ddk2cup ?>
<?php if(isset($apollo_theme_options["typekit"]) && $apollo_theme_options["typekit"] != "") : ?>
	<script type="text/javascript" src="http://use.typekit.com/<?php echo $apollo_theme_options["typekit"]; ?>.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php endif; ?>

<?php wp_head(); ?>
</head>
<body>
<div id="wrapper">