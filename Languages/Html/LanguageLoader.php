<?php

namespace Temple\Languages\Html;


use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Structs\Language;
use Temple\Languages\Html\Modifiers\SizeofModifier;
use Temple\Languages\Html\Nodes\CommentNode;
use Temple\Languages\Html\Nodes\ForeachNode;
use Temple\Languages\Html\Nodes\HtmlNode;
use Temple\Languages\Html\Nodes\IfNode;
use Temple\Languages\Html\Nodes\IncludeNode;
use Temple\Languages\Html\Nodes\PlainNode;
use Temple\Languages\Html\Nodes\VariableNode;
use Temple\Languages\Html\Plugins\CleanCommentsPlugin;
use Temple\Languages\Html\Plugins\CleanPhpTagsPlugin;
use Temple\Languages\Html\Plugins\VariableReturnPlugin;
use Temple\Languages\Html\Plugins\VariablesPlugin;
use Temple\Languages\Html\Services\VariableCache;


/**
 * this is the default language
 * it renders to a mix of html and php
 * Class LanguageLoader
 *
 * @package Temple\Languages\Html
 */
class LanguageLoader extends Language
{

    /** @var  EventManager $EventManager */
    private $EventManager;


    /**
     * @return string
     */
    public function extension()
    {
        return "php";
    }

    /**
     * registers the VariableCache
     */
    public function variableCache()
    {
        return new VariableCache();
    }


    /**
     * subscribe the nodes for the language
     */
    public function register()
    {
        $this->EventManager = $this->Instance->EventManager();
        $this->registerNodes();
        $this->registerModifiers();
        $this->registerPlugins();
    }


    /**
     * registers all nodes for the html language
     */
    private function registerNodes()
    {
        $this->subscribe("node.include", new IncludeNode());
        $this->subscribe("node.variable", new VariableNode());
        $this->subscribe("node.plain", new PlainNode());
        $this->subscribe("node.html", new HtmlNode());
        $this->subscribe("node.comment", new CommentNode());
        $this->subscribe("node.foreach", new ForeachNode());
        $this->subscribe("node.if", new IfNode());
    }


    /**
     * registers all plugins for the html language
     */
    private function registerPlugins()
    {
        $this->subscribe("plugin.nodeOutput.variables", new VariablesPlugin());
        $this->subscribe("plugin.output.variables", new VariablesPlugin());
        $this->subscribe("plugin.variableNode.variableReturn", new VariableReturnPlugin());
        $this->subscribe("plugin.dom.cleanComments", new CleanCommentsPlugin());
        $this->subscribe("plugin.output.cleanPhpTags", new CleanPhpTagsPlugin());
    }


    /**
     * subscribe all variable modifiers
     */
    private function registerModifiers()
    {
        $this->subscribe("modifier.sizeof", new SizeofModifier());
    }


}