<?php

namespace Temple\Languages\Core\Nodes;


use Temple\Engine\Structs\Node\Node;


/**
 * Class BlockNode
 *
 * @package Temple\Languages\Core\Nodes
 */
class BlockNode extends Node
{


    /** @var  string $blockName */
    private $blockName;

    private $methods = array("before", "after", "replace");


    /** @inheritdoc */
    public function check()
    {
        if ($this->getTag() == "block") {
            return true;
        }

        return false;
    }


    /**
     * converts the line into a node
     *
     * @return Node|bool
     */
    public function setup()
    {
        $this->setCommentNode(true);
        $this->setBlockName();
        $this->Dom->addBlock($this->getBlockName(), $this);

        return $this;
    }


    /**
     * @return string
     */
    public function getBlockName()
    {
        return $this->blockName;
    }


    /**
     * sets the name of the block
     */
    public function setBlockName()
    {
        $name = $this->getContent();
        foreach ($this->methods as $method) {
            $name = trim(preg_replace("/" . $method . "$/", "", $name));
        }

        $this->blockName = $name;
    }


    /**
     * returns the block replacing method
     *
     * @return string
     */
    public function getBlockMethod()
    {
        foreach ($this->methods as $method) {
            preg_match("/" . $method . "$/", $this->getContent(), $matches);
            if (isset($matches[0])) {
                return $matches[0];
            }
        }

        return "replace";
    }


    /**
     * creates the output
     *
     * @return string
     */
    public function compile()
    {
        $output = "";
        if ($this->Instance->Config()->isShowBlockComments() && $this->isShowComment()) {
            $output = "<!-- " . trim($this->plain) . " - " . $this->getRelativeFile() . "-->";
        }

        $output .= $this->compileChildren();

        return $output;
    }


}