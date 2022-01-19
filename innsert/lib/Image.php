<?php

namespace innsert\lib;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Helper class for images
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Image
{
	/**
	 * Compresses an image
	 *
	 * @access	public
	 * @param	string	$path			Image current path
	 * @param	string	$destination	Destination path (Replaces existing name)
	 * @param	int		$quality		Quality of compression
	 * @return	mixed
	 */
	public static function compress($path, $destination, $quality = 50)
	{
		$extension = pathinfo($path, PATHINFO_EXTENSION);
		if ($extension == 'png') {
			$source = imagecreatefrompng($path);
			imagepng($source, $destination, $quality);
		} else if ($extension == 'gif') {
			$source = imagecreatefromgif($path);
			imagegif($source, $destination, $quality);
		} else if ($extension == 'jpg' || $extension == 'jpeg') {
			$data = file_get_contents($path);
			try {
				$source = imagecreatefromstring($data);
			} catch (Exception $ex) {
				return false;
			}
			imagejpeg($source, $destination, $quality);
		} else {
			return false;
		}
		return $destination;
	}


	/**
	 * Resizes an image relative to given with
	 *
	 * @access	public
	 * @param	string	$path			Image path
	 * @param	string	$newWidth		Image new width
	 * @param	string	$extension		Image file extension
	 * @return	mixed
	 */
	public static function resizeWidth($path, $newWidth, $extension = null)
	{
		return self::resizeRelative($path, $newWidth, 'width', $extension);
	}


	/**
	 * Resizes an image relative to given height
	 *
	 * @access	public
	 * @param	string	$path			Image path
	 * @param	string	$newHeight		Image new height
	 * @param	string	$extension		Image file extension
	 * @return	mixed
	 */
	public static function resizeHeight($path, $newHeight, $extension = null)
	{
		return self::resizeRelative($path, $newHeight, 'height', $extension);
	}

	/**
	 * Resizes an image with relative width or height
	 *
	 * @access	public
	 * @param	string	$path			Image path
	 * @param	string	$newSize		New size
	 * @param	string	$type			Resize width or height
	 * @param	string	$extension		Image file eztension
	 * @return	mixed
	 */
	public static function resizeRelative($path, $newSize, $type = 'width', $extension = null)
	{
		list($w, $h) = getimagesize($path);
		if ($type == 'width') {
			$newWidth = $newSize;
			$newHeight = $newWidth * $h / $w;
		} else {
			$newHeight = $newSize;
			$newWidth = $newHeight * $w / $h;
		}
		if (!isset($extension)) {
			$extension = pathinfo($path, PATHINFO_EXTENSION);
		}
		if ($extension == 'png') {
			$source = imagecreatefrompng($path);
		} else if ($extension == 'gif') {
			$source = imagecreatefromgif($path);
		} else if ($extension == 'jpg' || $extension == 'jpeg') {
			$data = file_get_contents($path);
			try {
				$source = imagecreatefromstring($data);
			} catch (Exception $ex) {
				return false;
			}
		} else {
			return false;
		}
		$resource = imagecreatetruecolor($newWidth, $newHeight);
		if ($extension == 'png') {
			imagealphablending($resource, false);
			imagesavealpha($resource, true);
		}
		imagecopyresampled($resource, $source, 0, 0, 0, 0, $newWidth, $newHeight, $w, $h);
		return $resource;
	}

	/**
	 * Image resource to base64
	 *
	 * @access	public
	 * @param	resource	$resource		Image resource
	 * @param	string		$extension		Image file extension
	 * @return	mixed
	 */
	public static function resourceToBase64($resource, $extension)
	{
		ob_start();
		if ($extension == 'png') {
			imagepng($resource);
		} else if ($extension == 'gif') {
			imagegif($resource);
		} else if ($extension == 'jpg' || $extension == 'jpeg') {
			imagejpeg($resource);
		}
		$data = ob_get_contents();
		ob_end_clean();
		return base64_encode($data);
	}

	/**
	 * Saves an image resource to a file
	 *
	 * @access	public
	 * @param	resource	$resource		Image resource
	 * @param	string		$extension		Image file extension
	 * @param	string		$destination	File destination path
	 * @param	int			$quality		Image quality
	 * @return	bool
	 */
	public static function resourceToFile($resource, $extension, $destination, $quality = null)
	{
		if ($extension == 'png') {
			return isset($quality) ? imagepng($resource, $destination, $quality) : imagepng($resource, $destination);
		} else if ($extension == 'gif') {
			return isset($quality) ? imagegif($resource, $destination, $quality) : imagegif($resource, $destination);
		} else if ($extension == 'jpg' || $extension == 'jpeg') {
			return isset($quality) ? imagejpeg($resource, $destination, $quality) : imagejpeg($resource, $destination);
		}
		return false;
	}
}