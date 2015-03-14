# CalderaWP File Loader

Return a file path or file contents, checking in child theme, then theme, then as an absolute file path.
 
### Usage
```php
    //Load a file called "food.html" from current themes "templates" folder
    $file = calderawp_file_locator( 'templates/food.html' );
    if ( is_string( $file ) ) {
        echo $file;
    }
    
    //load a php file from your plugin, if a file "noms.php" of same name is not in template
    $file = calderawp_file_locator( 'noms.php' );
    
    //By default only file extensions php|html|htm are allowed.
    //TO override this use the "calderawp_file_locator_allow_extensions" filter before calling it, like this:
    add_filter( 'calderawp_file_locator_allow_extensions', function( $allowed, $context ) {
    	if ( 'slug_json_loader' === $context ) {
    		$allowed = array( 'json' );
    	}
    
    	return $allowed;
    
    }, 10, 2 );
    
    return calderawp_file_locator( 'hats.json', 'slug_json_loader' );
    
    //Use a special single-{$post-type}.php, in this case single-hat.php from plugin if theme/child theme doesn't have that file.
add_filter( 'template_includes', function( $template ) {
	if ( 'single-hat.php' == $template && ! is_file( calderawp_file_locator( $template ) ) ) {
		$template = calderawp_file_locator( trailingslashit( plugin_dir_path( __FILE__ ) ) . $template );
	}

	return $template;

}, 10 );
    
```
  
### Can't you do this in WordPress Already?
@see https://core.trac.wordpress.org/ticket/13239

### License, Copyright etc.
Copyright 2015 [CalderaWP LLC](https://CalderaWP.com) & [Josh Pollock](http://JoshPress.net).

Licensed under the terms of the [GNU General Public License version 2](http://www.gnu.org/licenses/gpl-2.0.html) or later. Please share with your neighbor.
    
The actual file locating code is basically copypasta from https://github.com/pods-framework/pods/blob/master/classes/PodsView (c) Pods Foundation. Much GPL, very thanks.
