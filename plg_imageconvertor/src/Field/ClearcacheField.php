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

namespace Joomla\Plugin\System\Imageconvertor\Field;

defined('_JEXEC') || die;

use Joomla\CMS\Form\FormField;
use Joomla\CMS\Layout\LayoutHelper;

class ClearcacheField extends FormField
{
  protected $type = 'clearcache';
  protected $layout = 'clearcache';

  public function setup(\SimpleXMLElement $element, $value, $group = null)
  {
    if ((string) $element->getName() !== 'field') return false;

    $this->input = null;
    $this->label = null;
    $this->element = $element;
    $this->group = $group;
    $this->hidden = ($this->hidden || strtolower((string) $this->element['type']) === 'hidden');
    $this->layout = !empty($this->element['layout']) ? (string) $this->element['layout'] : $this->layout;
    $this->parentclass = isset($this->element['parentclass']) ? (string) $this->element['parentclass'] : $this->parentclass;
    $this->hiddenLabel = true;
    $this->hidden = true;

    return true;
  }

  protected function getInput(): string
  {
    return rtrim(LayoutHelper::render($this->layout, [], JPATH_PLUGINS . '/system/imageconvertor/layouts'), PHP_EOL);
  }
}
