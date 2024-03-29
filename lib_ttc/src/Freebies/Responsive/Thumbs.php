<?php


/**
 * @package Plugin system - imageconvertor for Joomla! 3.x, Joomla 4.x & Jooomla 5.x 
 * @version $Id: system - imageconvertor 1.0.0 2023-11-15 23:26:33Z $
 * @author KWProductions Co.
 * @(C) 2020-2025.Kian William Productions Co. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 
 This file is part of system - imageconvertor.
    system - imageconvertor is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    plugin system - imageconvertor is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with system - imageconvertor.  If not, see <http://www.gnu.org/licenses/>.
 
**/

namespace Ttc\Freebies\Responsive;

class Thumbs
{
  private $driver = 'gd';

  public function __construct($driver = 'gd')
  {
    $this->driver = $this->getGraphicsDriver($driver);
  }

  public function create($img, $options, $srcSets)
  {
    if ($this->driver === 'imagick') return $this->createImagic($img, $options, $srcSets);
    elseif ($this->driver === 'gd') return $this->createGd($img, $options, $srcSets);
    else return;
  }

  private function createGd($img, $options, $srcSets)
  {
    for ($i = 0, $l = count($options->validSizes); $i < $l; $i++) {
      if ($options->scaleUp || ($img->width >= (int) $options->validSizes[$i])) {
        $fileSrc = 'media/cached-resp-images/' . $img->dirname . '/' . $img->filename . $options->separator . trim($options->validSizes[$i]);

        // Load the image
        if ($img->type === 'jpeg') {
          ini_set('gd.jpeg_ignore_warning', 1);
          $sourceImage = \imagecreatefromjpeg(JPATH_ROOT . '/' . $img->dirname . '/' . $img->filename . '.' . $img->extension);
        }
        if ($img->type === 'png') {
          $sourceImage = \imagecreatefrompng(JPATH_ROOT . '/' . $img->dirname . '/' . $img->filename . '.' . $img->extension);
        }
		   if ($img->type === 'gif') {
          $sourceImage = \imagecreatefromgif(JPATH_ROOT . '/' . $img->dirname . '/' . $img->filename . '.' . $img->extension);
        }
		
		
		
		
        if (!$sourceImage) return;

        $orgWidth    = \imagesx($sourceImage);
        $orgHeight   = \imagesy($sourceImage);
        $thumbHeight = floor($orgHeight * ((int) $options->validSizes[$i] / $orgWidth));
        $destImage   = \imagecreatetruecolor((int) $options->validSizes[$i], $thumbHeight);
        // Retain the alpha data
		
        if ($img->type === 'png') {
          \imageAlphaBlending($destImage, false);
          \imageSaveAlpha($destImage, true);
        }
		
		
        \imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, (int) $options->validSizes[$i], $thumbHeight, $orgWidth, $orgHeight);

        // Save the image
        if ($img->type === 'jpeg') {
          \imagejpeg($destImage, JPATH_ROOT . '/' . $fileSrc . '.' . $img->extension);
          $hash = hash_file('md5', JPATH_ROOT . '/' . $fileSrc . '.' . $img->extension, false);
          $srcSets->base->srcset[$options->validSizes[$i]] = str_replace(' ', '%20', $fileSrc) . '.' . $img->extension . '?version=' . $hash . ' ' . $options->validSizes[$i] . 'w';
        } else if ($img->type === 'png') {
          \imagepng($destImage, JPATH_ROOT . '/' . $fileSrc . '.' . $img->extension);
          $hash = hash_file('md5', JPATH_ROOT . '/' . $fileSrc . '.' . $img->extension, false);
          $srcSets->base->srcset[$options->validSizes[$i]] = str_replace(' ', '%20', $fileSrc) . '.' . $img->extension . '?version=' . $hash . ' ' . $options->validSizes[$i] . 'w';
        }
		 else if ($img->type === 'gif') {
          \imagegif($destImage, JPATH_ROOT . '/' . $fileSrc . '.' . $img->extension);
          $hash = hash_file('md5', JPATH_ROOT . '/' . $fileSrc . '.' . $img->extension, false);
          $srcSets->base->srcset[$options->validSizes[$i]] = str_replace(' ', '%20', $fileSrc) . '.' . $img->extension . '?version=' . $hash . ' ' . $options->validSizes[$i] . 'w';
        }

        if ($options->enableWEBP && \imagewebp($destImage, JPATH_ROOT . '/' . $fileSrc . '.webp', $options->qualityWEBP)) {
          $hash = hash_file('md5', JPATH_ROOT . '/' . $fileSrc . '.webp', false);
          if (!isset($srcSets->webp)) $srcSets->webp = (object) ['srcset' => []];
          $srcSets->webp->srcset[$options->validSizes[$i]] = str_replace(' ', '%20', $fileSrc) . '.webp' . '?version=' . $hash . ' ' . $options->validSizes[$i] . 'w';
        }
        if ($options->enableAVIF && \imagewebp($destImage, JPATH_ROOT . '/' . $fileSrc . '.avif', $options->qualityAVIF)) {
          $hash = hash_file('md5', JPATH_ROOT . '/' . $fileSrc . '.avif', false);
          if (!isset($srcSets->avif)) $srcSets->avif = (object) ['srcset' => []];
          $srcSets->avif->srcset[$options->validSizes[$i]] = str_replace(' ', '%20', $fileSrc) . '.avif' . '?version=' . $hash . ' ' . $options->validSizes[$i] . 'w';
        }

        \imagedestroy($sourceImage);
        \imagedestroy($destImage);

        $sourceImage = null;
        $destImage = null;

        \gc_collect_cycles();
      }
    }

    $this->createJSON($img, $srcSets);
  }

  private function createImagic($img, $options, $srcSets)
  {
    for ($i = 0, $l = count($options->validSizes); $i < $l; $i++) {
      if ($options->scaleUp || ($img->width >= (int) $options->validSizes[$i])) {
        $fileSrc = 'media/cached-resp-images/' . $img->dirname . '/' . $img->filename . $options->separator . trim($options->validSizes[$i]);
//php funciton too       
	   $image = new \Imagick;

        // Load the image
        $image->readImage(JPATH_ROOT . '/' . $img->dirname . '/' . $img->filename . '.' . $img->extension);
        $image->resizeImage((int) $options->validSizes[$i], 0, \Imagick::FILTER_LANCZOS, 1);



        // Save the image
        if ($img->type === 'jpeg')
			$image->setimageformat('JPEG');
        else if ($img->type === 'png')
      		$image->setImageFormat('PNG');
			else
				$image->setImageFormat('GIF');

		
		

        $image->setImageCompressionQuality($options->qualityJPG);
        $image->stripImage();
        $image->writeImage(JPATH_ROOT . '/' . $fileSrc . '.' . $img->extension);
        $hash = hash_file('md5', JPATH_ROOT . '/' . $fileSrc . '.' . $img->extension, false);
        $srcSets->base->srcset[$options->validSizes[$i]] = str_replace(' ', '%20', $fileSrc) . '.' . $img->extension . '?version=' . $hash . ' ' . $options->validSizes[$i] . 'w';

        if ($options->enableWEBP && $image->queryFormats('WEBP')) {
          $image->setImageFormat('WEBP');
          $image->setImageCompressionQuality($options->qualityWEBP);
          $image->writeImage(JPATH_ROOT . '/' . $fileSrc . '.webp');
          $hash = hash_file('md5', JPATH_ROOT . '/' . $fileSrc . '.webp', false);
          if (!isset($srcSets->webp)) $srcSets->webp = (object) ['srcset' => []];
          $srcSets->webp->srcset[$options->validSizes[$i]] = str_replace(' ', '%20', $fileSrc) . '.webp' . '?version=' . $hash . ' ' . $options->validSizes[$i] . 'w';
        }

        if ($options->enableAVIF && $image->queryFormats('AVIF')) {
          $image->setImageFormat('AVIF');
          $image->setImageCompressionQuality($options->qualityAVIF);
          $image->writeImage(JPATH_ROOT . '/' . $fileSrc . '.avif');
          $hash = hash_file('md5', JPATH_ROOT . '/' . $fileSrc . '.avif', false);
          if (!isset($srcSets->avif)) $srcSets->avif = (object)['srcset' => []];
          $srcSets->avif->srcset[$options->validSizes[$i]] = str_replace(' ', '%20', $fileSrc) . '.avif' . '?version=' . $hash . ' ' . $options->validSizes[$i] . 'w';
        }

        $image->destroy();
        $image = null;
        \gc_collect_cycles();
      }
    }


    $this->createJSON($img, $srcSets);
  }

  private function createJSON($img, $srcSets) {
    if (!is_dir(JPATH_ROOT . '/media/cached-resp-images/___data___/' . $img->dirname)) mkdir(JPATH_ROOT . '/media/cached-resp-images/___data___/' . $img->dirname, 0755, true);
    file_put_contents(JPATH_ROOT . '/media/cached-resp-images/___data___/' . $img->dirname . '/' . $img->filename . '.json', \json_encode($srcSets));
  }

  private function getGraphicsDriver(string $preferedDriver)
  {
    if (extension_loaded($preferedDriver)) return $preferedDriver;

    if (extension_loaded('imagick')) return 'imagick';
    if (extension_loaded('gd')) return 'imagick';

    throw new \RuntimeException('GD library is required for manipulation of images.');
  }
}

/**
$img = (object) [
    'dirname'   => string,
    'filename'  => string,
    'extension' => string,
    'width'     => int,
    'height'    => int,
    'type'      => jpg||png,
];
$options = (object) [
    'destination' => 'media/cached-resp-images/',
    'enableWEBP'  => bool,
    'enableAVIF'  => bool,
    'qualityJPG'  => int,
    'qualityWEBP' => int,
    'qualityAVIF' => int,
    'scaleUp'     => bool,
    'seperator'   => '_,
    'validSizes'  => array,
]
 */
