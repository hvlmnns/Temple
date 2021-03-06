<?php

namespace Temple\Engine;


use Temple\Engine\Exception\ExceptionHandler;
use Temple\Engine\InjectionManager\Injection;
use Temple\Engine\Languages\Language;
use Temple\Engine\Languages\LanguageConfig;


/**
 * if you add setter and getter which have an add function
 * the name must be singular
 * Class Config
 *
 * @package Temple\Engine
 */
class Config extends Injection
{

    /** @var int $identifier */
    private $identifier = 0;

    /** @var bool $doUpdate */
    private $doUpdate = true;

    /** @var bool $shutdownCallbackRegistered */
    private $shutdownCallbackRegistered = false;

    /** @var  Instance $Instance */
    private $Instance;

    /** @var null $subfolder */
    private $subfolder = null;

    /** @var bool $errorHandler */
    private $errorHandler = true;

    /** @var ExceptionHandler $errorHandlerInstance */
    private $errorHandlerInstance;

    /** @var string $cacheDir */
    private $cacheDir = "./Cache";

    /** @var bool $cacheEnabled */
    private $cacheEnabled = true;

    /** @var bool $ConfigCacheEnabled */
    private $configCacheEnabled = true;

    /** @var bool $variableCacheEnabled */
    private $variableCacheEnabled = true;

    /** @var array $templateDirs */
    private $templateDirs = array();

    /** @var bool $showBlockComments */
    private $showBlockComments = true;

    /** @var string $extension */
    private $extension = "tmpl";

    /** @var array $languages */
    private $languages = array();

    /** @var string $defaultLanguage */
    private $defaultLanguage = "./Languages/html";

    /** @var array $languages */
    private $languageTagName = "lang";

    /** @var array $languageCacheFolders */
    private $languageCacheFolders = array();

    /** @var array $languageConfigs */
    protected $languageConfigs = array();

    /** @var array $curlUrls */
    private $curlUrls = array();

    /** @var bool $useCoreLanguage */
    private $useCoreLanguage = true;

    /** @var array $processedTemplates */
    private $processedTemplates = array();


    /**
     * @param Instance $Instance
     */
    public function setInstance($Instance)
    {
        $this->Instance = $Instance;
    }


    /**
     * converts the config to an array
     */
    public function toArray()
    {
        $array = array(
            "static"  => array(
                "cacheDir"             => $this->Instance->DirectoryHandler()->getCacheDir(),
                "languageCacheFolders" => $this->getLanguageCacheFolders(),
                "curlUrls"             => $this->getCurlUrls(),
                "subfolder"            => $this->getSubfolder(),
                "cacheEnabled"         => $this->isCacheEnabled(),
                "templateDirs"         => $this->getTemplateDirs(),
                "defaultLanguage"      => $this->getDefaultLanguage(),
                "languages"            => $this->getLanguages(),
                "useCoreLanguage"      => $this->isUseCoreLanguage(),
                "DocumentRoot"         => $_SERVER["DOCUMENT_ROOT"],

            ),
            "dynamic" => array(
                "processedTemplates" => $this->getProcessedTemplates(),
                "languageConfigs"    => array()
            )
        );

        $languageConfigs = $this->getLanguageConfigs();

        /* @var LanguageConfig $languageConfig */
        foreach ($languageConfigs as $name => $languageConfig) {
            $array["dynamic"]["languageConfigs"][ $name ] = $languageConfig->toArray();
        }

        return $array;

    }


    /**
     * creates a config from an array
     *
     * @param array $config
     *
     * @return Config
     */
    public function createFromArray($config)
    {


        /** @var Config $Config */
        $methods = array_flip(get_class_methods($this));
        foreach ($config as $type) {
            foreach ($type as $method => $value) {
                $setMethodName = "set" . ucfirst($method);
                $addMethodName = "add" . preg_replace("/s$/", "", ucfirst($method));

                if (isset($methods[ $setMethodName ])) {
                    $this->$setMethodName($value);
                }

                if (isset($methods[ $addMethodName ])) {
                    foreach ($value as $k => $v) {
                        if (is_int($k)) {
                            $this->$addMethodName($v);
                        } else {
                            if ($method == "languageConfigs") {
                                $languageConfig = new LanguageConfig($this->Instance);
                                $v              = $languageConfig->createFromArray($v);
                            }
                            $this->$addMethodName($v, $k);
                        }
                    }
                }
            }
        }

        return $this;
    }


    /**
     * whenever or not to update the cache for this config
     *
     * @param $update
     */
    public function setUpdate($update)
    {
        $this->doUpdate = $update;
    }


    /**
     * @return bool
     */
    public function getUpdate()
    {
        return $this->doUpdate;
    }


    /**
     * updates the config
     */
    public function update()
    {
        if (!$this->shutdownCallbackRegistered) {
            register_shutdown_function(function (Config $configInstance) {
                if ($configInstance->getUpdate()) {
                    $configInstance->Instance->ConfigCache()->save($configInstance->toArray(), $configInstance->getIdentifier());
                }
            }, $this);
            $this->shutdownCallbackRegistered = true;
        }

        if ($this->errorHandler) {
            $this->errorHandlerInstance = new ExceptionHandler();
        } else {
            $this->errorHandlerInstance = null;
        }
    }


    /**
     * @return int
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }


    /**
     * @param int $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }


    /**
     * @return null
     */
    public function getSubfolder()
    {
        return $this->subfolder;
    }


    /**
     * @param $subfolder
     *
     * @return null
     */
    public function setSubfolder($subfolder)
    {
        $this->subfolder = $subfolder;
        $this->update();

        return $this->subfolder;
    }


    /**
     * @return boolean
     */
    public function isErrorHandler()
    {
        return $this->errorHandler;
    }


    /**
     * @param $errorHandler
     *
     * @return bool
     */
    public function setErrorHandler($errorHandler)
    {
        $this->errorHandler = $errorHandler;
        $this->update();

        return $this->errorHandler;
    }


    /**
     * @return string
     */
    public function getCacheDir()
    {
        return $this->cacheDir;
    }


    /**
     * @param $cacheDir
     *
     * @return string
     */
    public function setCacheDir($cacheDir)
    {
        $this->cacheDir = $cacheDir;
        $this->update();

        return $this->cacheDir;
    }


    /**
     * @return boolean
     */
    public function isCacheEnabled()
    {
        return $this->cacheEnabled;
    }


    /**
     * @param $cacheEnabled
     *
     * @return bool
     */
    public function setCacheEnabled($cacheEnabled)
    {
        $this->cacheEnabled = $cacheEnabled;
        $this->update();

        return $this->cacheEnabled;
    }


    /**
     * @return boolean
     */
    public function isConfigCacheEnabled()
    {
        return $this->configCacheEnabled;
    }


    /**
     * @param boolean $ConfigCacheEnabled
     */
    public function setConfigCacheEnabled($ConfigCacheEnabled)
    {
        $this->configCacheEnabled = $ConfigCacheEnabled;
    }


    /**
     * @return boolean
     */
    public function isVariableCacheEnabled()
    {
        return $this->variableCacheEnabled;
    }


    /**
     * @param boolean $variableCacheEnabled
     */
    public function setVariableCacheEnabled($variableCacheEnabled)
    {
        $this->variableCacheEnabled = $variableCacheEnabled;
    }


    /**
     * @return array
     */
    public function getTemplateDirs()
    {
        return $this->templateDirs;
    }


    /**
     * @param $templateDirs
     *
     * @return array
     */
    public function setTemplateDirs($templateDirs)
    {
        $this->templateDirs = $templateDirs;
        $this->update();

        return $this->templateDirs;
    }


    /**
     * @return boolean
     */
    public function isShowBlockComments()
    {
        return $this->showBlockComments;
    }


    /**
     * @param $showBlockComments
     *
     * @return mixed
     */
    public function setShowBlockComments($showBlockComments)
    {
        $this->showBlockComments = $showBlockComments;
        $this->update();

        return $this->showBlockComments;
    }


    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }


    /**
     * @param $extension
     *
     * @return bool
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
        $this->update();

        return $this->extension;
    }


    /**
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }


    /**
     * @param $language
     *
     * @return Language
     */
    public function getLanguage($language)
    {
        return $this->languages[ $language ];
    }


    /**
     * @param string $language
     * @param string $key
     */
    public function addLanguage($language, $key = null)
    {
        if (is_null($key)) {
            $key = explode("/", preg_replace("/\/$/", "", $language));
            $key = strtolower(end($key));
        }
        if (!in_array($language, $this->languages)) {
            $language = $this->Instance->DirectoryHandler()->getPath($language);
            $this->Instance->Languages()->initLanguageConfig($key, $language);
            $this->languages[ $key ] = $language;
        }

        $this->update();
    }


    /**
     * @return string
     */
    public function getDefaultLanguage()
    {
        return $this->defaultLanguage;
    }


    /**
     * @param string $language
     */
    public function setDefaultLanguage($language)
    {
        $this->defaultLanguage = $language;
    }


    /**
     * @return array
     */
    public function getLanguageTagName()
    {
        return $this->languageTagName;
    }


    /**
     * @param array $languageTagName
     */
    public function setLanguageTagName($languageTagName)
    {
        $this->languageTagName = $languageTagName;
    }


    /**
     * @return bool
     */
    public function isUseCoreLanguage()
    {
        return $this->useCoreLanguage;
    }


    /**
     * @param bool $useCoreLanguage
     */
    public function setUseCoreLanguage($useCoreLanguage)
    {
        $this->useCoreLanguage = $useCoreLanguage;
    }


    /**
     * @param string $languageCacheFolder
     */
    public function addLanguageCacheFolder($languageCacheFolder)
    {
        if (!isset($this->languageCacheFolders[ $languageCacheFolder ])) {
            $this->languageCacheFolders[ $languageCacheFolder ] = true;
        }

    }


    /**
     * @return array
     */
    public function getLanguageCacheFolders()
    {
        return array_keys($this->languageCacheFolders);
    }


    /**
     * @param LanguageConfig $LanguageConfig
     * @param string         $key
     */
    public function addLanguageConfig($LanguageConfig, $key = null)
    {
        $key                           = $LanguageConfig->getName();
        $this->languageConfigs[ $key ] = $LanguageConfig;
    }


    /**
     * @param string $language
     *
     * @return LanguageConfig
     */
    public function getLanguageConfig($language)
    {
        return $this->languageConfigs[ $language ];
    }


    /**
     * @return array
     */
    public function getLanguageConfigs()
    {

        return $this->languageConfigs;
    }


    /**
     * @param string|array $curlUrl
     */
    public function addCurlUrl($curlUrl)
    {
        if (is_array($curlUrl)) {
            foreach ($curlUrl as $url) {
                if (!isset($this->curlUrls[ $url ])) {
                    $this->curlUrls[ $url ] = true;
                }
            }
        } else {
            if (!isset($this->curlUrls[ $curlUrl ])) {
                $this->curlUrls[ $curlUrl ] = true;
            }
        }
    }


    /**
     * @return array
     */
    public function getCurlUrls()
    {
        return array_keys($this->curlUrls);
    }


    /**
     * @return array
     */
    public function getProcessedTemplates()
    {
        return $this->processedTemplates;
    }


    /**
     * @param array $processedTemplate
     */
    public function addProcessedTemplate($processedTemplate)
    {
        if (!in_array($processedTemplate,$this->processedTemplates)) {
            $this->processedTemplates[] = $processedTemplate;
        }
        $this->update();
    }

}