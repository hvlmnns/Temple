<?php

namespace Temple\Languages\Js;

use Temple\Engine\Languages\LanguageConfig;


/**
 * if you add setter and getter which have an add function
 * the name must be singular
 * Class Config
 *
 * @package Temple\Engine
 */
class Config extends LanguageConfig
{

    /** @var string $extension */
    protected $extension = "js";

}