<?php

/**
 * Created by PhpStorm.
 * User: lineofsky2017 ©copyright
 * Date: 2019/1/1
 * Time: 13:56
 */
class Trie
{
    /**
     * @var Trie[] childNodes
     */
    private $childNodes = null;
    private $freq = 0;
    public $nodeChar = null;

    public function addTrieNode($word)
    {
        if (strlen($word) === 0) {
            return;
        }
        $k = $word[0] - 'a';

        if ($this->childNodes[$k] === null) {
            $this->childNodes[$k] = new Trie();
        }

        $this->nodeChar = $word[0];

        $nextWord = substr($word, 1);

        if (strlen($nextWord) === 0) {
            $this->freq++;
        }

        $this->childNodes[$k]->addTrieNode($nextWord);

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
