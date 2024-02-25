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

?>
<?php 
namespace Joomla\Plugin\System\Imageconvertor\Extension;


defined('_JEXEC') or die;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Filesystem\Folder;
use Joomla\Event\SubscriberInterface;
use Joomla\CMS\Session\Session;





final class Imageconvertor extends CMSPlugin implements SubscriberInterface
{
	protected $page;
	protected $loadtype;
	protected $autoloadLanguage = true;
	
	 public static function getSubscribedEvents(): array
  {
    return [
    'onAfterRender'   => 'onRender',	
      'onAjaxImageconvertor'   => 'imageconvertorAjax',
	  'onAfterInitialize' => 'init',
    ];
  }
	
	public function init($event)
	{
		
		$this->loadLanguage();
	}
	
	public function onRender($event)
	{
		
		$app = Factory::getApplication();
		$input = $app->input;
	    
	
			  $uri = Uri::getInstance();
			  $uri = $uri->toString();
		
			 if($app->isClient('site'))	:
			
			
			  	$notallowed_pages = $this->params->get("notallowed_pages", "");
				
				$seo = $this->params->get("seofriendly", "");
				$notallowed_pages = trim($notallowed_pages);
				if($seo==1):
				if(preg_match('/all/', $notallowed_pages))
					return;
				else
				if(preg_match('/[\,]/', $notallowed_pages) && $seo ==1)
				{
				$nap = explode(',',$notallowed_pages);
				foreach($nap as $n){
					if(!empty($n) && isset($n)):
				$n = trim($n);
				$uri = trim($uri);
			
			
		            if(preg_match('/'.$n.'/', $uri)) { 
				
			                   return; 
		               }
					   endif;
				   }
				}
				else
					  if( (isset($notallowed_pages) && !empty($notallowed_pages) && preg_match("({$notallowed_pages})", $uri) == 1 ) && $seo==1 ) { 
			                   return; 
		               }
			   endif;
			
					   
					   $this->page = $app->getBody(false);	
						  
					  
					  if(preg_match('/<img/', $this->page)==1):
					
                 
                   preg_match_all('/<img\s[^>]+>/', $this->page, $matches);
				   $excludedfiles = $this->params->get('excluded');
				   if($excludedfiles!==NULL)
				   $ef = explode(',' ,$excludedfiles);
			   foreach($ef as $f){
			   $ef[] = trim($f);
			   }
			   
//var_dump($matches[0]);
//exit();
                   
				foreach($matches[0] as $img):
			//	var_dump("haha");
			//	exit();
						$excludedflag = 0;
					if($excludedfiles!==NULL)
						foreach($ef as $efile)
						{
							if(preg_match('/'.$efile.'/', $img))
							{
								$excludedflag = 1;
							}
						}
                  if($excludedflag == 0):    
        					  
                    
					        if(strpos($img, 'webp') || strpos($img, 'avif') || strpos($img, 'jpeg xl'))
								continue;        
                           // if (strpos($img, ' src=') === false || strpos($img, '//') !== false) 
						   if (strpos($img, ' src=') === false  && strpos($img, ' data-src=') === false ) 
								continue;
                            $sizes = array_map('trim', array_filter(explode(',', $this->params->get('sizes', '320,768,1200')), 'trim'));
							
						   $processed = (new \Ttc\Freebies\Responsive\Helper)->transformImage($img, $sizes);
						   
                            if ($processed !== $img) {
                            $this->page = str_replace($img, $processed, $this->page);
						
                             }
                    endif;

							 
						endforeach;
						$app->setBody($this->page);

						
					   
					   endif;

				endif;	   
        		   
			  
	}
  public function imageconvertorAjax($event)
  {
	
   if (!Session::checkToken('request') || !(Factory::getUser())->authorise('core.edit', 'com_plugins')) throw new \Exception('Not Allowed');
   if (is_dir(JPATH_ROOT . '/media/cached-resp-images')) return Folder::delete(JPATH_ROOT . '/media/cached-resp-images');



   return true;
  }
	
}