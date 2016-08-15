<?php

namespace Temple\Engine;


use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Exception\Exception;
use Temple\Engine\InjectionManager\Injection;
use Temple\Engine\Structs\Dom;
use Temple\Engine\Structs\Node\Node;


/**
 * Class Compiler
 *
 * @package Project
 */
class Compiler extends Injection
{

    /** @var  EventManager $EventManager */
    protected $EventManager;


    /** @inheritdoc */
    public function dependencies()
    {
        return array(
            "Engine/EventManager/EventManager" => "EventManager"
        );
    }


    /**
     * returns the finished template content
     *
     * @param Dom $dom
     *
     * @return string
     */
    public function compile(Dom $dom)
    {

        $dom = $this->EventManager->notify("plugin.dom", $dom);
        $output = $this->createOutput($dom);
        $output = $this->EventManager->notify("plugin.output", $output);

        return $output;
    }


    /**
     * merges the nodes into the final content
     *
     * @param Dom|array $dom
     *
     * @return mixed
     * @throws Exception
     */
    private function createOutput($dom)
    {
        # temp variable for the output
        $output = '';
        $nodes = $dom->getNodes();
        /** @var Node $node */
        foreach ($nodes as $node) {
            $node->setDom($dom);
            $nodeOutput = $node->compile();
            $nodeOutput = $this->EventManager->notify("plugin.nodeoutput", array($nodeOutput,$node));
            $output .= $nodeOutput;
        }

        if (trim($output) == "") return false;

        return $output;
    }

}