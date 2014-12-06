<?php


class Jxs_slideshow
{
	function output()
	{
		echo json_encode($this->output); 
		exit;
	}
	
	function Jxs_slideshow()
	{

		$OBJ =& get_instance();
		global $default;
	
		$rs = $OBJ->db->fetchRecord("SELECT * FROM ".PX."objects, ".PX."media 
			WHERE media_id = '$_POST[i]' 
			AND media_ref_id = id");
	
		$caption = ($rs['media_title'] == '') ? '' : "<div class='title'>" . $rs['media_title'] . "</div>";
		$caption .= ($rs['media_caption'] == '') ? '' : "<div class='caption'>" . $rs['media_caption'] . "</div>";
	
		// tmep
		$vids = array_merge($default['media'], $default['services']);
	
		$path = ($rs['media_dir'] != '') ? "/files/$rs[media_dir]/" : '/files/gimgs/';
	
		if (in_array($rs['media_mime'], $vids))
		{
			// if it's a movie else it's a service
			$file = (in_array($rs['media_mime'], $default['media'])) ? DIRNAME . $path . $rs['media_file'] : $rs['media_file'];
			$mime = $rs['media_mime'];
	
			// height and width of thumbnail
			$size[0] = $rs['media_x'];
			$size[1] = $rs['media_y'];
		
			$right_margin = (isset($OBJ->hook->options['slideshow_settings']['margin'])) ? 
				$OBJ->hook->options['slideshow_settings']['margin'] : 25;
			$bottom_margin = (isset($OBJ->hook->options['slideshow_settings']['bottom_margin'])) ? 
				$OBJ->hook->options['slideshow_settings']['bottom_margin'] : 25;

			$temp_x = $rs['media_x'] + $right_margin;
			$temp_y = $rs['media_y'] + $bottom_margin;
		
			// we need the base index.php file for this one
			require_once(DIRNAME . '/ndxzsite/plugin/index.php');
			
			$file = ($rs['media_dir'] != '') ? $rs['media_dir'].'/'.$rs['media_file'] : $rs['media_file'];

			//$a = "<div id='slideshow'>\n";
			//$a = '<div id="slideshow' . $_POST['z'] . '" class="picture" style="z-index: ' . $_POST['z'] . '; position: absolute; height: ' . $rs['media_y'] . 'px; display: none;">';
			
			// what is 40% of width
			$click_width = round($size[0] * .3);
			
			// make overlays for previous and next.
			//$a = "<a id='slide-previous' style='display: block; position: absolute; z-index: 1005; top: 45px; left: 0; bottom: 45px; width: {$click_width}px; text-indent: -9999px; background: url(" . $OBJ->vars->exhibit['baseurl'] . "/ndxzsite/img/previous.gif) no-repeat left center;' href='#' onclick=\"previous(); return false;\">previous</a>";
			
			$bottom_setting = ($size[1] - 90);
			
			$a = "<a id='slide-previous' style='width: {$click_width}px; height: {$bottom_setting}px;' href='#' onclick=\"previous(); return false;\">previous</a>";
			
			$adjuster = ($size[0] - $click_width);
			
			$a .= "<a id='slide-next' style='left: {$adjuster}px; width: {$click_width}px; height: {$bottom_setting}px;' href='#' onclick=\"next(); return false;\">next</a>";
			
			// odd vimeo bug
			$mime_display = ($rs['media_mime'] == 'vimeo') ? '' : ' display: none;';
			
			$a .= '<div id="slide' . $_POST['z'] . '" class="picture" style="z-index: ' . $_POST['z'] . '; position: absolute;' . $mime_display . '">';
			//$a .= '<div class="picture">';
			$a .= $mime($file, $rs['media_x'], $rs['media_y'], $rs['media_thumb']);
			//$a .= '</div>';
				

			if (($rs['media_title'] != '') && ($rs['media_caption'] != ''))
			{
				$a .= "<div class='captioning'>\n";
				if ($rs['media_title'] != '') $a .= "<div class='title'>$rs[media_title]</div>\n";
				if ($rs['media_caption'] != '') $a .= "<div class='caption'>$rs[media_caption]</div>\n";
				$a .= "</div>\n";
			}

			$a .= "</div>\n\n";

			$this->output['height'] = $rs['media_y'];
			$this->output['output'] = $a;
			return;
		}
		else
		{
			$file = DIRNAME . '/files/gimgs/' . $rs['id'] . '_' . $rs['media_file'];
	
			// height and width of thumbnail
			$size = getimagesize($file);

			//$size = getimagesize(DIRNAME . '/files/gimgs/' . $image['media_ref_id'] . '_' . $image['media_file']);
			
			// what is 40% of width
			$click_width = round($size[0] * .3);
			
			// make overlays for previous and next.
			//$a = "<a id='slide-previous' style='display: block; position: absolute; z-index: 1005; top: 45px; left: 0; bottom: 45px; width: {$click_width}px; text-indent: -9999px; background: url(" . $OBJ->vars->exhibit['baseurl'] . "/ndxzsite/img/previous.gif) no-repeat left center;' href='#' onclick=\"previous(); return false;\">previous</a>";
			
			$bottom_setting = ($size[1] - 90);
			
			$a = "<a id='slide-previous' style='width: {$click_width}px; height: {$bottom_setting}px;' href='#' onclick=\"previous(); return false;\">previous</a>";
			
			$adjuster = ($size[0] - $click_width);
			
			$a .= "<a id='slide-next' style='left: {$adjuster}px; width: {$click_width}px; height: {$bottom_setting}px;' href='#' onclick=\"next(); return false;\">next</a>";
			
			//height: ' . $size[1] . 'px; 
			
			//$a = "<div id='slideshow' style='position: relative; height: " . $size[1] . "px;'>\n";
			$a .= '<div id="slide' . $_POST['z'] . '" class="picture" style="z-index: ' . $_POST['z'] . '; position: absolute; display: none;"><img src="' . $OBJ->baseurl . '/files/gimgs/' . $rs['id'] . '_' . $rs['media_file'] . '" width="' . $size[0] . '" height="' . $size[1] . '" />';
		
			//$a = "<div id='slideshow'>\n";
			//$a .= "<div class='picture'><a href='#' onclick=\"next(); return false;\" alt=''><img src='" . $OBJ->baseurl . '/files/gimgs/' . $rs['id'] . '_' . $rs['media_file'] . "' width='$size[0]' height='$size[1]' /></a></div>\n";
			
			
			if (($rs['media_title'] != '') && ($rs['media_caption'] != ''))
			{
				$a .= "<div class='captioning'>\n";
				if ($rs['media_title'] != '') $a .= "<div class='title'>$rs[media_title]</div>\n";
				if ($rs['media_caption'] != '') $a .= "<div class='caption'>$rs[media_caption]</div>\n";
				$a .= "</div>\n";
			}
			
		
			$a .= "</div>\n";
		
			//return $a;
			
			$this->output['mime'] 	= $rs['media_mime'];
			$this->output['height'] = $size[1];
			$this->output['output'] = $a;
			return;
		}
	
		//return $output;
		$this->output = '';
	}
}