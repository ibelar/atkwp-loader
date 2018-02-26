<?php

/**
 * Class AtkWpLoader
 *
 * This will collect Composer ClassLoader instances and associated them
 * with proper plugin. Therefore, make sure a plugin is loading class from it's vendor
 * directory and not from another plugin vendor directory when more that one plugin use
 * composer folder stucture.
 *
 * Plugin using this loader need to properly setup the current WP plugin prior to creating it's object instance.
 * To see an exaple of a plugin using this loader please visit https://github.com/ibelar/atk-wordpress-demo
 *
 */
class AtkWpLoader
{
    private static $loader;

    /**
     * Collects Composer Autoloaders.
     *
     * @var array
     */
    private $loaders = [];

    /**
     * Contains the name of the plugin that currently need Autoloader.
     *
     * @var null|string
     */
    private $currentPlugin = null;

    /**
     * Whether the spl_autoload function has been registered or not.
     *
     * @var bool
     */
    private $isRegistered = false;

    /**
     * Return loader instance.
     *
     * @return AtkWpLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }
        self::$loader = $loader = new atkWpLoader();
        return $loader;
    }

    /**
     * Register a composer loader for a specific plugin.
     *
     * @param $pluginName
     * @param $loader
     */
    public function registerPluginLoader($pluginName, $loader)
    {
        $this->loaders[$pluginName] = $loader;
        if (!$this->isRegistered) {
            spl_autoload_register([$this, 'loadClass'], true, true);
            $this->isRegistered = true;
        }
    }

    /**
     * Set current plugin that need autoloader.
     *
     * @param $name
     */
    public function setCurrentPlugin($name)
    {
        $this->currentPlugin = $name;
    }

    /**
     * The actual class loader function register in spl_autoload.
     * Will use Composer ClassLoader associate with currentPlugin.
     *
     * @param $class
     */
    public function loadClass($class)
    {
        if ($this->currentPlugin) {
            $this->loaders[$this->currentPlugin]->loadClass($class);
        }
    }
}
