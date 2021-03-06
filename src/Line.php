<?php
namespace FlflPlainize;

use FlflPlainize\Exception\InvalidLineException;

class Line
{
    /**
     * separator for returning text
     */
    const TEXT_SEPARATOR = "\t";

    private $time;
    private $name;
    private $json;

    /**
     * constructer
     *
     * @param  string $line  a line of tsv log (column = timestamp logname JSON)
     * @return FlflPlainize
     */
    public function __construct($line)
    {
        $this->init($line);
    }

    /**
     * plainize JSON to text
     *
     * @param  array  $keys  keys to include in response text
     *                       separate with "." to specify value in multidimension array
     * @return string
     */
    public function toPlainText(Array $keys = null)
    {
        if ($keys) {
            $data = $this->filter($keys, $this->json);
        } else {
            $data = $this->json;
        }

        return date('Y-m-d H:i:s', $this->time) .
            static::TEXT_SEPARATOR .
            $this->implodeRecursive(static::TEXT_SEPARATOR, $data);
    }

    private function init($line)
    {
        $line = trim($line);
        if (!$line) {
            throw new InvalidLineException();
        }

        @list($timestamp, $logname, $json_str) = explode("\t", $line);

        $this->validateExplodeResult($timestamp, $logname, $json_str);

        $this->time = strtotime($timestamp);
        $this->name = $logname;
        $this->json = json_decode($json_str, true);

        $this->validateInitResult();
    }

    private function validateExplodeResult($timestamp, $logname, $json_str)
    {
        if (empty($timestamp) || empty($logname) || empty($json_str)) {
            throw new InvalidLineException('insufficient columns');
        }
    }

    private function validateInitResult()
    {
        if (empty($this->time)) {
            throw new InvalidLineException('invalid timestamp');
        }
        if (!is_array($this->json)) {
            throw new InvalidLineException('invalid json');
        }
    }

    private function implodeRecursive($glue, $pieces)
    {
        if (!$pieces) {
            return '';
        }

        $ret = '';

        foreach ($pieces as $item) {
            if (is_array($item)) {
                $ret .= $this->implodeRecursive($glue, $item) . $glue;
            } else {
                $ret .= $item . $glue;
            }
        }

        $ret = substr($ret, 0, 0 - strlen($glue));

        return $ret;
    }

    private function filter($keys, $arr)
    {
        $result = [];
        foreach ($keys as $key) {
            $result[] = $this->getFilteredArray($key, $arr);
        }

        return $result;
    }

    private function getFilteredArray($dot_separated_key, $arr)
    {
        $keys = explode('.', $dot_separated_key);

        $cursor = &$arr;
        foreach ($keys as $key) {
            if (!array_key_exists($key, $cursor)) {
                return [];
            }
            $cursor = &$cursor[$key];
        }

        return $cursor;
    }
}
