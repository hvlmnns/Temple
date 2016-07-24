<?php

namespace Underware\Languages\Core\Plugins;


use Underware\Engine\EventManager\Event;
use Underware\Engine\Structs\Dom;
use Underware\Engine\Structs\Node\Node;
use Underware\Languages\Core\Nodes\BlockNode;


/**
 * Class VariablesPlugin
 *
 * @package Underware\Languages\Core\Plugins
 */
class ExtendPlugin extends Event
{

    /** @var  Dom $Dom */
    private $Dom;

    /** @var  Dom $ParentDom */
    private $ParentDom;


    public function dispatch(Dom $Dom)
    {

        if ($Dom->isExtending()) {
            $this->Dom       = $Dom;
            $this->ParentDom = $this->Dom->getParentDom();

            /** @var Node $node */
            foreach ($this->Dom->getNodes() as &$node) {
                $node = $this->iterate($node);
            }

            return $this->ParentDom;
        }

        return $Dom;
    }


    /**
     * @param Node $node
     *
     * @return Node
     */
    private function iterate(Node $node)
    {
        $node     = $this->extend($node);
        $children = $node->getChildren();
        if (sizeof($children) > 0) {
            foreach ($children as &$child) {
                $child = $this->iterate($child);
            }
        }

        return $node;
    }


    /**
     * @param Node $node
     *
     * @return Node
     */
    private function extend(Node $node)
    {

        if ($node->getTag() == "block") {
            /** @var BlockNode $node */
            $name = $node->getBlockName();
            /** @var BlockNode $block */
            $block  = $this->ParentDom->getBlock($name);
            $method = $node->getBlockMethod();
            if ($method == "before") {
                $children = $block->getChildren();
                array_unshift($children, $node);
                $block->setChildren($children);
            } elseif ($method == "after") {
                $children   = $block->getChildren();
                $children[] = $node;
                $block->setChildren($children);
            } elseif ($method == "replace") {
                $block->setShowBlockComment(false);
                $block->setChildren(array($node));
            }

        }

        return $node;
    }

}