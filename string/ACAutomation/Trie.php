<?php

/**
 * Created by PhpStorm.
 * User: lineofsky2017 copyright(c) 2019
 * Date: 2019/1/1
 * Time: 13:56
 */
class Trie
{
    /**
     * @var Trie[] childNodes
     */
    public $childNodes = [];
    public $freq = 0;
    public $nodeChar = null;
    public $string = null;
    /**
     * @var Trie $fail
     */
    public $fail = null;

    /**
     * @return Trie[]
     */
    public function getChildNodes(): ?array
    {
        return $this->childNodes;
    }


    public function hasChild($char)
    {
        return array_key_exists($this->hash($char), $this->childNodes);
    }

    public function getChild($char)
    {
        if (!$this->hasChild($char)) {
            return null;
        }
        return $this->childNodes[$this->hash($char)];
    }

    public function isWord()
    {
        return $this->freq > 0;
    }

    private function hash($char)
    {
        return ord($char) - ord('a');
    }

    public function addTrieNode($word, $string)
    {
        if (strlen($word) === 0) {
            return;
        }

        $k = $this->hash($word[0]);

        if (!isset($this->childNodes[$k])) {
            $this->childNodes[$k] = new Trie();
        }

        $this->childNodes[$k]->nodeChar = $word[0];

        $nextWord = substr($word, 1);

        if (strlen($nextWord) === 0) {
            $this->childNodes[$k]->freq++;
            $this->childNodes[$k]->string = $string;
        }

        $this->childNodes[$k]->addTrieNode($nextWord, $string);

        return;
    }

    public function delTrieNode(?string $word)
    {
        if (strlen($word) === 0) {
            return;
        }

        $k = $word[0] - 'a';

        if ($this->childNodes[$k] === null) {
            // not found
            throw new \Exception('not found');
        }

        if (!($this->childNodes[$k] instanceof Trie)) {
            throw new \Exception('instance error');
        }

        // found
        $nextWord = substr($word, 1);

        if (strlen($nextWord) === 0) {
            // found the word end
            if ($this->freq == 0) {
                // error
                throw new \Exception('data error');
            }
            $this->freq--;
        }

        $return = $this->childNodes[$k]->delTrieNode($nextWord);

        if ($return === false) {
            // del useless node
            unset($this->childNodes[$k]);
        }

        if ($this->freq === 0 && count($this->childNodes) === 0) {
            // let parent know to unset this node
            return false;
        }

        return;
    }
}
