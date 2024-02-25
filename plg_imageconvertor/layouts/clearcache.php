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


defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;


Factory::getDocument()->getWebAssetManager()
  ->registerScript(
    'clearcache-es6',
    'media/plg_system_imageconvertor/js/clearcache-es6.js',
    ['version' => 'auto', 'relative' => true],
    ['nomodule' => true, 'defer' => true],
    ['core']
  )
  ->registerAndUseScript(
    'clearcache-esm',
    'media/plg_system_imageconvertor/js/clearcache-esm.js',
    ['version' => 'auto', 'relative' => true],
    ['type' => 'module'],
    ['clearcache-es6', 'core']
  );
?>
<clear-cache-field label-text="<?= Text::_('PLG_SYSTEM_IMAGECONVERTOR_MORE_FIELDSET_CLEAR_CACHE_DESC'); ?>" button-text="<?= Text::_('PLG_SYSTEM_IMAGECONVERTOR_MORE_FIELDSET_CLEAR_CACHE_BTN'); ?>" token="<?= Session::getFormToken(); ?>">
  <button>Clean cache</button>
</clear-cache-field>
