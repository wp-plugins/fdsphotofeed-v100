<?php
/*
Plugin Name: fdsPhotoFeed
Plugin URI: http://ferdous.wordpress.com/
Description: A plug-in that Fetch given RSS feeds and parse to show it, You can use it in any post of any page. It's an all-in-one plugin for parsing any photo feed!
Author: Nurul Ferdous
Version: 1.0.0 
Author URI: http://ferdous.wordpress.com
Feature: now supports multi rss source in single post or page. 
*/
/*  Copyright 2008 Nurul Ferdous  (email : nurul.ferdous@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_filter('the_content', 'rss_parse');
/**
rss_parse
this function search the content for {rss} tag and then parsse it
sample formats of the {rss} tag 
  1. {rss uri=http://seismictalk.smugmug.com/hack/feed.mg?Type=nicknameRecentPhotos&Data=SeismicTalk&format=rss200 limit=5}
there are 2 attribute in this {rss} tag which we look for
  1. uri = rss feed location
  2. limit = how many feed to show
@param string $content the content of the post
@return none
*/
function rss_parse($content)
{
	$feed = new photoFEED;
	$pattern = "~{rss\s*uri=(.*)\s*limit=(.*)}~iU";
	preg_match_all($pattern, $content, $matches);
	$uri= (string) $matches[1][0];
	$num = $matches[2][0];
    //initialize the counter
    if (empty($num)) $num = 10;
	$feed->feedURL = html_entity_decode($uri);
	$feed->imageSize = "medium";
	$feed->thumbnailSize = "square";
	//$num= $matches[2][0];
	if ($photos = $feed->getImages("$num")){
		if(strpos($uri,"flickr.com")){
			foreach ($photos as $photo)
			{ 
				$p_content.= "<a href='" . $photo["imageURL"] . "' rel='lightbox2' title='".$photo["title"]."'>";
				$p_content.= "<img style='border:1px solid #bbb; padding:3px; margin:5px 5px 4px 0px' alt='" . $photo["title"] . "' title='" . $photo["title"] . "' src='" . $photo["thumbnailURL"] . "' />";
				$p_content.= "</a>";
			}
		}else if(strpos($uri,"smugmug.com")){
			foreach($photos as $photo){
				$src=explode(' ', $photo['thumbnailURL']);
				$thumbURL=$src['0'].' width="75" height="75"';
				$imageURL=substr($src['0'], 0, -10);
				$imageURL=$imageURL . "-M.jpg";
				$p_content.= "<a href=$imageURL rel='lightbox2' title='".$photo["title"]."'>";
				$p_content.= "<img style='border:1px solid #bbb; padding:3px; margin:5px 5px 4px 0px' alt='" . $photo["title"] . "' title='" . $photo["title"] . "' src=$thumbURL />";
				$p_content.= "</a>";
				unset($src);
			}
		}else if(strpos($uri,".com")){
			foreach($photos as $photo){
				$src=explode(' ', $photo['thumbnailURL']);
				$thumbURL=$src['0'].' width="75" height="75"';
				$imageURL=$src['0'];
				$p_content.= "<a href=$imageURL rel='lightbox2' title='".$photo["title"]."'>";
				$p_content.= "<img style='border:1px solid #bbb; padding:3px; margin:5px 5px 4px 0px' alt='" . $photo["title"] . "' title='" . $photo["title"] . "' src=$thumbURL />";
				$p_content.= "</a>";
				unset($src);
		}
		}else{
			$p_content= "No pictures found\n";
		}
	}
	$content = str_replace($matches[0][0], $p_content, $content);
	return $content;
}
class photoFEED
{
	//Public variables
	var $feedURL = "";
	var $thumbnailSize = "square";
	var $imageSize = "medium";
	var $urlSuffix = array("square" => "_s", "thumb" => "_t", "small" => "_m", "medium" => "", "large" => "_o");
	function getImages($imageCount = "all")
	{
		// create lastRSS object
		$rss = new lastRSS; 
		// setup transparent cache
		$rss->cache_dir = './cache'; 
		$rss->cache_time = 3600; // one hour
		$result = array();
		// load some RSS file
		if ($rs = $rss->get($this->feedURL)) 
		{			
			$count = 0;
			foreach($rs['items'] as $item) 
			{ 
				if ($imageCount != "all" && $count == $imageCount) 
					break;
				$result[] = $this->parseFeedItem($item);
				$count++;
			} 
			return $result;
		}
		else 
			return false;
	} 
	function parseFeedItem($item)
	{
		//title, description, image url, page url
		$result = array();
		$result["title"] = html_entity_decode($item['title']);
		$result["pageURL"] = html_entity_decode($item['link']);
		$feedDescription = html_entity_decode($item['description']);
		//preg_match("/\\<p\\>.*\\<\\/p\\>$/", $feedDescription, $matches);
		preg_match_all("/<p>(.*)<\/p>$/i", $feedDescription, $matches, PREG_PATTERN_ORDER);
		$result["description"] = $matches[1][0];
		preg_match_all("/src=\"(.*)\.jpg/i", $feedDescription, $matches,  PREG_PATTERN_ORDER);
		$result["thumbnailURL"] = str_replace("_m", $this->urlSuffix[$this->thumbnailSize], $matches[1][0]) . ".jpg";
		$result["imageURL"] = str_replace("_m", $this->urlSuffix[$this->imageSize], $matches[1][0]) . ".jpg";
		return $result;
	}
}
class lastRSS {
	// -------------------------------------------------------------------
	// Public properties
	// -------------------------------------------------------------------
	var $default_cp = 'UTF-8';
	var $CDATA = 'nochange';
	var $cp = '';
	var $items_limit = 0;
	var $stripHTML = False;
	var $date_format = '';
	// -------------------------------------------------------------------
	// Private variables
	// -------------------------------------------------------------------
	var $channeltags = array ('title', 'link', 'description', 'language', 'copyright', 'managingEditor', 'webMaster', 'lastBuildDate', 'rating', 'docs');
	var $itemtags = array('title', 'link', 'description', 'author', 'category', 'comments', 'enclosure', 'guid', 'pubDate', 'source');
	var $imagetags = array('title', 'url', 'link', 'width', 'height');
	var $textinputtags = array('title', 'description', 'name', 'link');
	// -------------------------------------------------------------------
	// Parse RSS file and returns associative array.
	// -------------------------------------------------------------------
	function Get ($rss_url) {
		// If CACHE ENABLED
		if ($this->cache_dir != '') {
			$cache_file = $this->cache_dir . '/rsscache_' . md5($rss_url);
			$timedif = @(time() - filemtime($cache_file));
			if ($timedif < $this->cache_time) {
				// cached file is fresh enough, return cached array
				$result = unserialize(join('', file($cache_file)));
				// set 'cached' to 1 only if cached file is correct
				if ($result) $result['cached'] = 1;
			} else {
				// cached file is too old, create new
				$result = $this->Parse($rss_url);
				$serialized = serialize($result);
				if ($f = @fopen($cache_file, 'w')) {
					fwrite ($f, $serialized, strlen($serialized));
					fclose($f);
				}
				if ($result) $result['cached'] = 0;
			}
		}
		// If CACHE DISABLED >> load and parse the file directly
		else {
			$result = $this->Parse($rss_url);
			if ($result) $result['cached'] = 0;
		}
		// return result
		return $result;
	}
	// -------------------------------------------------------------------
	// Modification of preg_match(); return trimed field with index 1
	// from 'classic' preg_match() array output
	// -------------------------------------------------------------------
	function my_preg_match ($pattern, $subject) {
		// start regullar expression
		preg_match($pattern, $subject, $out);
		// if there is some result... process it and return it
		if(isset($out[1])) {
			// Process CDATA (if present)
			if ($this->CDATA == 'content') { // Get CDATA content (without CDATA tag)
				$out[1] = strtr($out[1], array('<![CDATA['=>'', ']]>'=>''));
			} elseif ($this->CDATA == 'strip') { // Strip CDATA
				$out[1] = strtr($out[1], array('<![CDATA['=>'', ']]>'=>''));
			}
			// If code page is set convert character encoding to required
			if ($this->cp != '')
				//$out[1] = $this->MyConvertEncoding($this->rsscp, $this->cp, $out[1]);
				$out[1] = iconv($this->rsscp, $this->cp.'//TRANSLIT', $out[1]);
			// Return result
			return trim($out[1]);
		} else {
		// if there is NO result, return empty string
			return '';
		}
	}
	// -------------------------------------------------------------------
	// Replace HTML entities &something; by real characters
	// -------------------------------------------------------------------
	function unhtmlentities ($string) {
		// Get HTML entities table
		$trans_tbl = get_html_translation_table (HTML_ENTITIES, ENT_QUOTES);
		// Flip keys<==>values
		$trans_tbl = array_flip ($trans_tbl);
		// Add support for &apos; entity (missing in HTML_ENTITIES)
		$trans_tbl += array('&apos;' => "'");
		// Replace entities by values
		return strtr ($string, $trans_tbl);
	}
	// -------------------------------------------------------------------
	// Parse() is private method used by Get() to load and parse RSS file.
	// Don't use Parse() in your scripts - use Get($rss_file) instead.
	// -------------------------------------------------------------------
	function Parse ($rss_url) {
		// Open and load RSS file
		if ($f = @fopen($rss_url, 'r')) {
			$rss_content = '';
			while (!feof($f)) {
				$rss_content .= fgets($f, 4096);
			}
			fclose($f);
			// Parse document encoding
			$result['encoding'] = $this->my_preg_match("'encoding=[\'\"](.*?)[\'\"]'si", $rss_content);
			// if document codepage is specified, use it
			if ($result['encoding'] != '')
				{ $this->rsscp = $result['encoding']; } // This is used in my_preg_match()
			// otherwise use the default codepage
			else
				{ $this->rsscp = $this->default_cp; } // This is used in my_preg_match()
			// Parse CHANNEL info
			preg_match("'<channel.*?>(.*?)</channel>'si", $rss_content, $out_channel);
			foreach($this->channeltags as $channeltag)
			{
				$temp = $this->my_preg_match("'<$channeltag.*?>(.*?)</$channeltag>'si", $out_channel[1]);
				if ($temp != '') $result[$channeltag] = $temp; // Set only if not empty
			}
			// If date_format is specified and lastBuildDate is valid
			if ($this->date_format != '' && ($timestamp = strtotime($result['lastBuildDate'])) !==-1) {
						// convert lastBuildDate to specified date format
						$result['lastBuildDate'] = date($this->date_format, $timestamp);
			}
			// Parse TEXTINPUT info
			preg_match("'<textinput(|[^>]*[^/])>(.*?)</textinput>'si", $rss_content, $out_textinfo);
				// This a little strange regexp means:
				// Look for tag <textinput> with or without any attributes, but skip truncated version <textinput /> (it's not beggining tag)
			if (isset($out_textinfo[2])) {
				foreach($this->textinputtags as $textinputtag) {
					$temp = $this->my_preg_match("'<$textinputtag.*?>(.*?)</$textinputtag>'si", $out_textinfo[2]);
					if ($temp != '') $result['textinput_'.$textinputtag] = $temp; // Set only if not empty
				}
			}
			// Parse IMAGE info
			preg_match("'<image.*?>(.*?)</image>'si", $rss_content, $out_imageinfo);
			if (isset($out_imageinfo[1])) {
				foreach($this->imagetags as $imagetag) {
					$temp = $this->my_preg_match("'<$imagetag.*?>(.*?)</$imagetag>'si", $out_imageinfo[1]);
					if ($temp != '') $result['image_'.$imagetag] = $temp; // Set only if not empty
				}
			}
			// Parse ITEMS
			preg_match_all("'<item(| .*?)>(.*?)</item>'si", $rss_content, $items);
			$rss_items = $items[2];
			$i = 0;
			$result['items'] = array(); // create array even if there are no items
			foreach($rss_items as $rss_item) {
				// If number of items is lower then limit: Parse one item
				if ($i < $this->items_limit || $this->items_limit == 0) {
					foreach($this->itemtags as $itemtag) {
						$temp = $this->my_preg_match("'<$itemtag.*?>(.*?)</$itemtag>'si", $rss_item);
						if ($temp != '') $result['items'][$i][$itemtag] = $temp; // Set only if not empty
					}
					// Strip HTML tags and other bullshit from DESCRIPTION
					if ($this->stripHTML && $result['items'][$i]['description'])
						$result['items'][$i]['description'] = strip_tags($this->unhtmlentities(strip_tags($result['items'][$i]['description'])));
					// Strip HTML tags and other bullshit from TITLE
					if ($this->stripHTML && $result['items'][$i]['title'])
						$result['items'][$i]['title'] = strip_tags($this->unhtmlentities(strip_tags($result['items'][$i]['title'])));
					// If date_format is specified and pubDate is valid
					if ($this->date_format != '' && ($timestamp = strtotime($result['items'][$i]['pubDate'])) !==-1) {
						// convert pubDate to specified date format
						$result['items'][$i]['pubDate'] = date($this->date_format, $timestamp);
					}
					// Item counter
					$i++;
				}
			}
			$result['items_count'] = $i;
			return $result;
		}
		else // Error in opening return False
		{
			return False;
		}
	}
}
?>
