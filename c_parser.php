<?php
class wpsc_parser_c extends mijnpress_plugin_framework {
	function get_youtube_id($url)
	{
		// $url = 'http://www.youtube.com/watch?v=9DhSwsbKJQ4&feature=topvideos_music';
		// $url = 'http://www.youtube.com/v/9DhSwsbKJQ4?feature=topvideos_music';
		
		$parsedUrl = parse_url($url);
		$parsedQueryString = parse_str($parsedUrl['query'], $arr);
		
		// URL in ?v=... form
		if (isset($arr['v'])) {
		    $id = $arr['v'];
		}
		// URL in /v/... form
		else if (substr($parsedUrl['path'], 0, 3) == '/v/') {
		    $id = substr($parsedUrl['path'], 3);
		}
		// invalid form
		else {
		   return false;
		}
		return $id;
	}
	
	function get_youtube_embed($url, $width = 425, $height = 350)
	{
		$id = wpsc_parser_c::get_youtube_id($url);
		if($id)
		{
			return '<object type="application/x-shockwave-flash" style="width:'.$width.'px; height:'.$height.'px;" data="http://www.youtube.com/v/'.$id.'"><param name="movie" value="http://www.youtube.com/v/'.$id.'" /></object>';
		}
	}
	
	function get_twitter_name($inputstring)
	{
		$text = preg_replace('/@(\w+)/','$1', $inputstring);
		if($text)
		{
			return $text;
		}		
		return false;
	}
	
	function get_twitter_url($inputstring)
	{
		$name = wpsc_parser_c::get_twitter_name($inputstring);
		if($name)
		{
			return '<a href="http://twitter.com/#!/'.$name.'">@'.$name.'</a>';
		}
		return '';
	}
	
	function strip_malicious_html($inputhtml)
	{
		// $document should contain an HTML document.
		// This will remove HTML tags, javascript sections
		// and white space. It will also convert some
		// common HTML entities to their text equivalent.
		
		$search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript
		                 "'<[\/\!]*?[^<>]*?>'si",  // Strip out html tags
		                 "'([\r\n])[\s]+'",  // Strip out white space
		                 "'&(quot|#34);'i",  // Replace html entities
		                 "'&(amp|#38);'i",
		                 "'&(lt|#60);'i",
		                 "'&(gt|#62);'i",
		                 "'&(nbsp|#160);'i",
		                 "'&(iexcl|#161);'i",
		                 "'&(cent|#162);'i",
		                 "'&(pound|#163);'i",
		                 "'&(copy|#169);'i",
		                 "'&#(\d+);'e");  // evaluate as php
		
		$replace = array ("",
		                  "",
		                  "\\1",
		                  "\"",
		                  "&",
		                  "<",
		                  ">",
		                  " ",
		                  chr(161),
		                  chr(162),
		                  chr(163),
		                  chr(169),
		                  "chr(\\1)");
		
		return preg_replace ($search, $replace, $inputhtml);
	}
}
?>