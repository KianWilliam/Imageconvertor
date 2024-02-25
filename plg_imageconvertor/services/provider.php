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
use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\System\Imageconvertor\Extension\Imageconvertor;




return new class implements ServiceProviderInterface
{
  public function register(Container $container)
  {
    $container->set(
      PluginInterface::class,
      function (Container $container)
      {
        $plugin                 = PluginHelper::getPlugin('system', 'imageconvertor');
        $dispatcher             = $container->get(DispatcherInterface::class);
        $documentFactory        = $container->get('document.factory');

        $plugin = new Imageconvertor($dispatcher, (array) $plugin, $documentFactory);
        $plugin->setApplication(Factory::getApplication());

        return $plugin;
      }
    );
  }
};
