<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends MY_Controller {

	public function index()
	{
		$rootdir	= PATH_PORTAL_CONTENT . '/gallery';
		$folders	= array_diff(scandir($rootdir), ['..', '.']);
		$folders	= array_filter($folders, function ($folder) use ($rootdir) {
			return is_dir($rootdir . DIRECTORY_SEPARATOR . $folder);
		});
		
		$this->template->build('index', compact('folders'));
	}

	public function show($folder)
	{
		$folder		= urldecode($folder);
		$rootdir	= PATH_PORTAL_CONTENT . '/gallery/' . $folder;
		$files   	= array_diff(scandir($rootdir), ['..', '.']);
		$files	    = glob($rootdir . '/*.{jpg,jpeg,png,gif,JPG}', GLOB_BRACE);

		$images		= array_map(function ($file) use ($rootdir, $folder) {
			$path	= str_replace($rootdir, '', $file);

			return 'portal-content/gallery/' . trim($folder, '\\/') . DIRECTORY_SEPARATOR . trim($path, '\\/');
		}, $files);

		$this->template->build('show', compact('images'));
	}

}

/* End of file Gallery.php */
/* Location: ./application/modules/gallery/controllers/Gallery.php */