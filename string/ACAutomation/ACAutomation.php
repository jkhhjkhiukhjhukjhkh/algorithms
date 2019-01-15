<?php

/**
 * Created by PhpStorm.
 * User: lineofsky2017 copyright(c) 2019
 * Date: 2019/1/13
 * Time: 19:39
 */
class ACAutomation
{
    /**
     * @var string[]
     */
    private $patterns = [];
    private $inputString = null;
    private $occurs = [];
    /**
     * @var Trie|null
     */
    private $root = null;

    public function init()
    {
        require 'Trie.php';
    }

    public function __construct()
    {
        $this->init();
    }

    /**
     * @param string $pattern
     * @internal param array $patterns
     */
    public function setPattern(string $pattern)
    {
        $this->patterns[] = $pattern;
    }

    /**
     * @param null $inputString
     */
    public function setInputString($inputString)
    {
        $this->inputString = $inputString;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getOccurs(): array
    {
        if (empty($this->patterns) || empty($this->inputString)) {
            throw new \Exception('前置条件为空');
        }

        $this->buildTrie();

        $this->find();

        return $this->occurs;
    }

    private function find()
    {
        /**
         * @var Trie $currTrie
         */
        $currTrie = $this->root;
        for ($i = 0; $i < strlen($this->inputString);) {
            $currChar = $this->inputString{$i};
            if ($currTrie->hasChild($currChar)) {
                $child = $currTrie->getChild($currChar);
                if ($child->isWord()) {
                    if (!isset($this->occurs[$child->string])) {
                        $this->occurs[$child->string] = [];
                    }
                    $this->occurs[$child->string][] = $i - strlen($child->string) + 1;
                }

                if ($child->fail != $this->root && $child->fail->isWord()) {
                    $this->occurs[$child->fail->string][] = $i - strlen($child->fail->string) + 1;
                }

                $currTrie = $child;
                $i++;
            } else {
                if ($currTrie->fail != null) {
                    $currTrie = $currTrie->fail;
                } else {
                    $i++;
                }
            }
        }
    }

    private function buildTrie()
    {
        /**
         * @var Trie $curr
         */
        $this->root = new Trie();
        foreach ($this->patterns as $pattern) {
            $this->root->addTrieNode($pattern, $pattern);
        }
        //        $this->root->fail = $this->root;

        // build fail
        $queue = new \SplQueue();
        foreach ($this->root->getChildNodes() as $childNode) {
            $queue->enqueue($childNode);
            $childNode->fail = $this->root;
        }

        while (!$queue->isEmpty()) {

            $curr = $queue->dequeue();
            foreach ($curr->getChildNodes() as $childNode) {
                $failNode = $curr->fail;

                while (true) {

                    if ($failNode === null) {
                        $childNode->fail = $this->root;
                        break;
                    }

                    if ($failNode->hasChild($childNode->nodeChar)) {
                        $childNode->fail = $failNode->getChild($childNode->nodeChar);
                        break;
                    } else {
                        $failNode = $failNode->fail;
                    }

                }

                $queue->enqueue($childNode);
            }

        }
    }

}