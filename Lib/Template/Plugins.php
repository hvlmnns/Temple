<?php

namespace Temple\Template;


use Temple\Dependency\DependencyInstance;
use Temple\Instance;
use Temple\Models\Plugin\Plugin;
use Temple\Utilities\Config;
use Temple\Utilities\Directories;


class Plugins extends DependencyInstance
{

    /** @var Instance $Temple */
    protected $Temple;

    /** @var Config $Config */
    protected $Config;

    /** @var  Directories $Directories */
    protected $Directories;

    /** @var  PluginFactory $PluginFactory */
    protected $PluginFactory;


    /**
     * @return array
     */
    public function dependencies()
    {
        return array(
            "Utilities/Config"       => "Config",
            "Utilities/Directories"  => "Directories",
            "Template/PluginFactory" => "PluginFactory"
        );
    }


    /**
     * adds a plugin directory
     *
     * @param $dir
     * @return bool|mixed|string
     */
    public function addDirectory($dir)
    {
        $dir = $this->Directories->add($dir, "plugins");
        $this->initiatePlugins($dir);

        return $dir;
    }


    /**
     * removes a plugin directory on the given position
     *
     * @param $position
     * @return bool|mixed|string
     */
    public function removeDirectory($position)
    {
        return $this->Directories->add($position, "plugins");
    }


    /**
     * returns currently available plugin directories
     *
     * @return mixed
     */
    public function getDirectories()
    {
        return $this->Directories->get("plugins");
    }


    /**
     * load and install all plugins within the added directories
     * if dir is passed it will just look for plugins within this directory
     * also add $Temple to the plugin constructor so we can use it within the plugins
     *
     * @param null $dir
     * @return bool
     */
    public function initiatePlugins($dir = null)
    {
        return $this->PluginFactory->loadAll($dir);
    }


    /**
     * returns all registered plugins for the instance
     *
     * @throws \Temple\Exception\TempleException
     */
    public function getPlugins()
    {
        return $this->PluginFactory->getPlugins();
    }


    /**
     * returns all registered plugins for the instance
     *
     * @param string $type
     * @return array
     * @throws \Temple\Exception\TempleException
     */
    public function getPluginsByType($type)
    {
        return $this->PluginFactory->getPluginsByType($type);
    }


    /**
     * returns a registered plugins depending on the passed name
     *
     * @param  string $name ;
     * @throws \Temple\Exception\TempleException
     * @return Plugin
     */
    public function getPlugin($name)
    {
        return $this->PluginFactory->getPlugin($name);
    }


    /**
     * processes a single line from the template file
     *
     * @param $element
     * @return mixed
     */
    public function preProcess($element)
    {
        return $this->executePlugin("preprocessor", $element);
    }


    /**
     * processes a html node
     *
     * @param $element
     * @return mixed
     */
    public function process($element)
    {
        return $this->executePlugin("processor", $element);
    }


    /**
     * processes a function node
     *
     * @param $element
     * @return mixed
     */
    public function processFunctions($element)
    {
        return $this->executePlugin("functions", $element);
    }


    /**
     * processes the dom
     *
     * @param $element
     * @return mixed
     */
    public function postProcess($element)
    {
        return $this->executePlugin("postprocessor", $element);
    }


    /**
     * processes the finished template content
     *
     * @param $element
     * @return mixed
     */
    public function processOutput($element)
    {
        return $this->executePlugin("outputprocessor", $element);
    }


    private function executePlugin($type, $element)
    {
        $plugins = $this->getPluginsByType($type);
        if (is_null($plugins)) {
            return $element;
        }
        foreach ($plugins as $positions) {
            /** @var Plugin $plugin */
            foreach ($positions as $plugin) {
                $element = $plugin->process($element);
            }
        }

        return $element;
    }

}
