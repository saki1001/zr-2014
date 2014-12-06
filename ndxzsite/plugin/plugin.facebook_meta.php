<?php

/*
Plugin Name: Facebook Meta
Plugin URI: http://www.indexhibit.org/plugin/facebook-meta/
Description: Add Facebook meta tags for description and image.
Version: 1.0
Author: Indexhibit
Author URI: http://indexhibit.org/
Type: front
Hook: add_meta_tags
Function: facebook_meta:load
End
*/

class facebook_meta
{
	function load()
	{
		$OBJ =& get_instance();
		
		$meta = "\n<meta property=\"og:description\" content=\"" . $this->description() . "\" />\n";
		
		$image = $this->find_image();
		
		if (is_array($image))
		{
			// make sure it's an image
			if (in_array($image['media_mime'], array('jpg', 'gif', 'png', 'jpeg')))
			{
				$meta .= "<meta property=\"og:image\" content=\"" . BASEURL . GIMGS . "/" . $image['media_file'] . "\" />\n";
			}
			else
			{
				// it's a movie so let's grab the thumbnail
				$meta .= "<meta property=\"og:image\" content=\"" . BASEURL . GIMGS . "/" . $image['media_thumb_source'] . "\" />\n";
			}
		}
		else
		{
			// no image
			$meta .= "<meta property=\"og:image\" content=\"\" />\n";
		}
		
		return $meta;
	}
	
	
	function find_image()
	{
		$OBJ =& get_instance();

		if (!empty($OBJ->vars->images))
		{
			foreach ($OBJ->vars->images as $files)
			{
				foreach ($files as $file)
				{
					if (in_array($file['media_mime'], array('jpg', 'gif', 'png', 'jpeg')))
					{
						return $file;
					}
				}
			}
		}
		
		return false;
	}
	
	
	function description()
	{
		$OBJ =& get_instance();
		
		if ($OBJ->vars->exhibit['content'] == '') return $OBJ->vars->exhibit['title'];

		// if there is an abstract or a special facebook description use it, otherwise,
		// used the first 50 words from the content
		$content = strip_tags($OBJ->vars->exhibit['content']);
		$content = str_replace("\n", '', $content);
		
		// backup, just in case their content was all links or somethin
		if ($content == '') return $OBJ->vars->exhibit['title'];
		
		$content = explode(" ", $content);
		$count = count($content);
		
		if ($count > 50)
		{
			$i=0;
			foreach ($content as $out)
			{
				if ($i < 50)
				{
					$new_content[] = $out;
					$i++;
				}
			}
			
			$content = implode(' ', $new_content);
			$content = htmlentities($content) . '...';
			
			return $content;
		}
	}
}