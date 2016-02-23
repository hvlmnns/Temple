<?php

namespace Caramel;

/**
 *
 * Class PluginImport
 * @package Caramel
 *
 * @description: handles file imports
 * @position: 0
 * @author: Stefan Hövelmanns
 * @License: MIT
 *
 */

class PluginImport extends Plugin
{

    /** @var int $position */
    protected $position = 0;

    /**
     * @param Node $node
     * @return bool
     */
    public function check($node)
    {
        return ($node->get("tag.tag") == "import");
    }

    /**
     * @param Node $node
     * @return Node $node
     */
    public function process($node)
    {
        $this->import($node);

        return $node;
    }

    /**
     * imports a file where the node is located
     *
     * @param Node $node
     * @return Node $node
     */
    private function import($node)
    {

        $node->set("tag.display", false);
        $file = $this->getPath($node);
        $file = $this->caramel->parse($file);
        $node->set("content", "<?php include '" . $file . "' ?>");

        return $node;
    }

    /**
     * searches for a template file and returns the correct path
     *
     * @param Node $node
     * @return string $file
     */
    private function getPath($node)
    {
        # if the file has an absolute path
        $file      = $node->get("attributes");
        $relative  = $file[0] != "/";
        $templates = $this->caramel->getTemplateDirs();

        if ($relative) {
            $dir  = $this->getParentPath($node, $templates);
            $file = $dir . $file;
        }

        return $file;
    }


    /**
     * returns the template path to the file which is importing
     *
     * @param Node $node
     * @param array $templates
     * @return mixed
     */
    private function getParentPath($node, $templates)
    {
        $path = explode("/", $node->get("file"));
        array_pop($path);
        $path = implode("/", $path) . "/";
        foreach ($templates as $template) {
            $path = str_replace($template, "", $path);
        }

        return $path;
    }

}