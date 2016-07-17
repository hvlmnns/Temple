<?php

namespace Underware\Engine\Filesystem;


use Underware\Engine\Config;
use Underware\Engine\Exception\Exception;
use Underware\Engine\Injection\Injection;


/**
 * Class Directories
 *
 * @package Underware
 */
class DirectoryHandler extends Injection
{

    /** @var  Config $Config */
    protected $Config;


    /** @inheritdoc */
    public function dependencies()
    {
        return array(
            "Engine/Config" => "Config"
        );
    }


    /**
     * returns all template Directories
     *
     * @return mixed
     */
    public function getTemplateDirs()
    {
        return $this->check();
    }


    /**
     * adds a template directory
     *
     * @param $dir
     *
     * @return bool|string
     */
    public function addTemplateDir($dir)
    {
        $dirs = $this->check();
        if (array_key_exists($dir, array_flip($dirs))) {
            return false;
        } else {
            if (!$dir) {
                return false;
            }
            $dir = $this->path($dir);

            # always add a trailing space
            if (strrev($dir)[0] != "/") {
                $dir = $dir . "/";
            }

            array_unshift($dirs, $dir);


            $this->Config->setTemplateDirs($dirs);

            return $dir;
        }
    }


    /**
     * removes a template directory
     *
     * @param null $levelOrPath
     */
    public function removeTemplateDir($levelOrPath = null)
    {

        $dirs = $this->Config->getTemplateDirs();

        if (is_numeric($levelOrPath)) {
            if (array_key_exists($levelOrPath, $dirs)) {
                unset($dirs[ $levelOrPath ]);
            }
        } elseif (is_string($levelOrPath)) {
            if (in_array($levelOrPath, $dirs)) {
                $flipped = array_flip($dirs);
                $key     = $flipped[ $levelOrPath ];
                unset($dirs[ $key ]);
            }
        }

        return $this->Config->setTemplateDirs($dirs);
    }


    /**
     * sets the cache directory
     *
     * @param $dir
     *
     * @return string|void
     */
    public function setCacheDir($dir)
    {
        $dir = $this->validate($dir);
        $dir = $this->Config->setCacheDir($dir);

        return $dir;
    }


    /**
     * returns the current cache directory
     *
     * @return string
     */
    public function getCacheDir()
    {
        $dir = $this->Config->getCacheDir();
        $dir = $this->validate($dir);

        return $dir;
    }


    /**
     * creates a directory at the given path
     *
     * @param $dir
     *
     * @throws \Underware\Engine\Exception\Exception
     */
    public function createDir($dir)
    {
        $dir = $this->path($dir);
        if (!is_dir($dir)) {
            if (!is_writable(dirname($dir))) {
                throw new Exception("You'r missing permissions to setup this directory!", $dir);
            }

            mkdir($dir, 0777, true);
        }
    }


    /**
     * checks if we have a relative or an absolute directory
     * and returns the adjusted directory
     *
     * @param $dir
     *
     * @return string
     */
    private function path($dir)
    {
        if ($dir[0] != "/") {
            $framework = explode("Underware", __DIR__);
            $framework = $framework[0] . "Underware/";
            $dir       = $framework . $dir;
        }
        $dir = str_replace("/./", "/", $dir) . "/";
        $dir = preg_replace("/\/+/", "/", $dir);

        return $dir;
    }


    /**
     * iterates over all template directories and checks if they are valid
     *
     * @return mixed
     * @throws \Underware\Engine\Exception\Exception
     */
    private function check()
    {

        $dirs = $this->Config->getTemplateDirs();
        if (is_array($dirs)) {
            foreach ($dirs as $dir) {
                $this->validate($dir);
            }
        } else {
            $this->validate($dirs);
        }

        return $this->Config->getTemplateDirs();
    }


    /**
     * checks if the passed directory exists
     *
     * @param $dir
     *
     * @return string
     * @throws Exception
     */
    private function validate($dir)
    {
        $dir = $this->path($dir);
        if (is_dir($dir)) return $dir;

        throw new Exception("Can't add directory because it does't exist.", $dir);
    }


}