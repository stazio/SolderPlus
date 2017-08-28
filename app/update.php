<?php

Route::get('update', function() {
	return View::make('install.update');
});

Route::post('update', function() {
	set_time_limit(0);

	function put($text, $die=false) {
		file_put_contents(public_path('update_log.txt'), $text, FILE_APPEND);
		if ($die) {
			http_response_code(500);
			die();
		}
	}

	put('Entering maintenance mode<br>');
	put (shell_exec('php artisan down'));

	put ('Downloading new version of SolderPlus');

	//This is the file where we save the    information
	$fp = fopen (base_path('update.zip'), 'w+');
	//Here is the file we are downloading, replace spaces with %20
	$latestVersion = UpdateUtils::getLatestVersion();
	if (!is_int($latestVersion))
		put('Failure. Latest version could not be checked', true);
	$ch = curl_init(str_replace(" ","%20",
		"https://github.com/stazio/SolderPlus/releases/download/$latestVersion/$latestVersion.zip"));
	curl_setopt($ch, CURLOPT_TIMEOUT, 50);
	// write curl response to file
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	// get curl response
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);

	put('Extracting');
	$zip = new ZipArchive();
	if (($res = $zip->open(base_path('update.zip'))) !== TRUE)
		put('Failed to open zip file<br>' . $res, true);
	$zip->extractTo(base_path());
	$zip->close();

	put ('Updating database<br>');
	put (shell_exec('php artisan migrate'));

	put('<br>Exiting maintenance mode<br>');
	put (shell_exec('php artisan up'));

	return BaseController::success();
});