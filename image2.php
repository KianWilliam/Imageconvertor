<?php

/**
 * @package Package - imageconvertor for Joomla! 3.x, Joomla 4.x & Jooomla 5.x 
 * @version $Id: Package - imageconvertor 1.0.0 2023-11-15 23:26:33Z $
 * @author KWProductions Co.
 * @(C) 2020-2025.Kian William Productions Co. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 
 This file is part of Package - imageconvertor.
    Package - imageconvertor is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    package - imageconvertor is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with package - imageconvertor.  If not, see <http://www.gnu.org/licenses/>.
 
**/

defined('_JEXEC') || die;

use Joomla\CMS\Plugin\PluginHelper;
use Ttc\Freebies\Responsive\Helper as ResponsiveHelper;

extract($displayData);

/** @var $img          string  the original image tag*/
/** @var $breakpoints  array   the breakpoints */

if (PluginHelper::isEnabled('system', 'imageconvertor')) {
  $img = (new ResponsiveHelper)->transformImage($img, $breakpoints);
}

echo $img;