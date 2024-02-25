Imageconvertor plugin is developed by KWProductions Co. for websites that have already developed with images' extensions
that of not friendly seo, therefore the plugin will be able to resolve this situation.
For responsive websites, you got to add a js file in which passes the width and the height to the element <img />
for instance : in the related webpage you have <img id='blah' src='blah' /> that no width or height is set there.
To prevent the intrinsic sizes to be passed, in js file you may write down the logic as below:
if(800<=screen.width<=987){
 jQuery('#blah').set('width', '121');
  jQuery('#blah').set('height', '121');
  }
 else
    if(768<=screen.width<=800){
	jQuery('#blah').set('width', '93');
  jQuery('#blah').set('height', '93');
	}
	else
	{ //and etc.
The imageconvertor plugin starts working after render is over, it reads these widths and heights and
delivers an image with the same size and seo friendly extension.
For small sizes in which the images covers all the screen, you only input the related width in the valid sizes field.
The defaults there are [320, 768, 1200].
Excluded pages only work with seo friendly urls of joomla, then use the yes / no option in settings.
Once you enabled the plugin, to collect the cached images, you have to visit all pages of your website one by one.
Then next time ready cahced images will be loaded and your site works faster.
I have got some good and intelligent ideas from the extension pkg_responsive , Dimitrios Grammatikogiannis but the essence of my plugin is so different 
and more practical for the users.
In case of any problem contact : webarchitect@kwproductions121.ir

