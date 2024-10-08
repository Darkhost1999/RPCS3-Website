<?php

function cacheRoadmap()
{
	$cr = curl_init();

	// Set the required cURL flags
	curl_setopt($cr, CURLOPT_RETURNTRANSFER, true); // Return result as raw output
	curl_setopt($cr, CURLOPT_URL, "https://github.com/RPCS3/rpcs3/wiki/Roadmap"); // Point cURL resource to the URL

	$content = curl_exec($cr);
	$httpcode = curl_getinfo($cr, CURLINFO_HTTP_CODE);

	curl_close($cr);

	if ($httpcode != 200)
	{
		echo "Caching roadmap failed! httpcode:{$httpcode}".PHP_EOL;
		return;
	}

	if (!empty($content))
	{
		$start = "<div class=\"markdown-body\">";
		$end = "</div>

          </div>
</div>";

		$roadmap = explode($end, explode($start, $content)[1])[0];

		// Save fetched roadmap to cache/roadmap_cached.php
		$path = realpath(__DIR__)."/../../cache/";
		$file = fopen("{$path}roadmap_cached.php", "w");
		fwrite($file, "<!-- This file is automatically generated every hour -->".PHP_EOL);
		fwrite($file, $roadmap);
		fclose($file);
	}
}

// Cache Roadmap (Running every hour)
cacheRoadmap();

?>
