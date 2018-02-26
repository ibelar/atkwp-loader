# Autoloader for plugin using Composer

This allow uses of composer vendor directory structure for Wordpress plugins. It will make sure that the 
proper vendor directory is in uses when a plugin need to load classes from it's vendor directory.

## Install 

For best result, this need to be install in Wordpress must use plugin folder. Create a mu-plugins folder 
inside wp_content folder if your Wordpress installation does not have one:

```
    $ mkdir /wp_contents/mu-plugins
```

Clone or copy this folder inside the mu-plugins folder.

```
    $ cd mu-plugins
    $ git clone https//github.com/ibelar/atkwp-loader
```

Next step is to make sure that atkwp-loader.php file is loaded into Wordpress because Wordpress does
not load plugin file automatically from a folder. 

To do so, create a php file directly in mu-plugins folder and add the following:

```
    require("atkwp-loader/atkwp-loader.php");
```

## How to use

### Plugin initialisation

Fist step in using this autoloader is to setup composer autoloader as you would normally do in you plugin file:

```
    //load composer autoloader to start loading classes.
    $loader = require 'vendor/autoload.php';
```

Then use the $loader acquire via regular composer to load necessary classes to instantiate your plugin.

```
$atk_plugin_name = "atkdemo";
$atk_plugin = __NAMESPACE__."\\Plugin";
$$atk_plugin_name = new  $atk_plugin($atk_plugin_name, new Pathfinder(plugin_dir_path(__FILE__)), new ComponentController());
```

Once your plugin initialisation is done, register this composer loader and associate it with your plugin in AtkWpLoader.

```
    //Register class loader with this plugin. Make sure atkwp-loader.php is install in mu-plugins.
    \AtkWpLoader::getLoader()->registerPluginLoader($atk_plugin_name, $loader);
``` 

After your plugin is fully initialized, simply unregister the regular composer autoload function.

```
    //No need for regular composer autoloader, our AtkWpLoader will take care of loading class from now on.
    $loader->unregister();
    unset($loader);
```

### Usage during plugin action

When your plugin need to load classes for instantiating object during a Wordpress action or filter, the AtkWpLoader current plugin need to be set prior to instantiate your objects. 
This will make sure that proper vendor dir inside you plugin folder will be use.

```
    add_action( 'admin_init', function () {
        \AtkWpLoader::getLoader()->setCurrentPlugin('atkdemo');
        $view = new \atk4\ui\View();
        $view->renderHtml();
    });
```
For a complete example on how to use this autoloader, please visit [atk-wordpress-demo](https://github.com/ibelar/atk-wordpress-demo)