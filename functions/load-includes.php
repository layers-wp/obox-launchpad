<?php class apollo_include_folders
	{
		function trawl_folder($folder)
			{
				$folder = LAUNCHPADDIR.$folder;
				if ($handler = opendir($folder)) :
					while (false !== ($file = readdir($handler))) :
						if ($file !== "." && $file !== ".." && $file != "load-includes.php" && strpos($file, ".php")) :
							include_once ($folder.$file);
						endif;
					endwhile;
					closedir($handler);
				endif;
			}
	}

$include_folders = array("functions/");
//Include all the OCMX files
foreach($include_folders as $inc_folder) :
	$include_folders = new apollo_include_folders();
	$folder = $inc_folder;
	$include_folders->trawl_folder($folder);
endforeach;
?>