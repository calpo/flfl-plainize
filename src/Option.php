<?php
/**
 * This file is part of the flfl-plainize.
 */

namespace FlflPlainize;


class Option
{
    private $keys = [];

    public function __construct(Array $opts)
    {
        $this->keys = $this->mergeMultipleOption($this->keys, $opts, 'k');
        $this->keys = $this->mergeMultipleOption($this->keys, $opts, 'key');
    }

    public static function getopt()
    {
        return getopt(
            'k:',
            ['key:']
        );
    }

    public function getKeys()
    {
        return $this->keys;
    }

    private function mergeMultipleOption(Array $arr, Array $opts, $name)
    {
        if (!array_key_exists($name, $opts)) {
            return $arr;
        }

        return array_merge($arr, is_array($opts[$name]) ? $opts[$name] : [$opts[$name]]);
    }
}
