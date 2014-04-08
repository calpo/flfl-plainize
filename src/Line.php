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
    public function toPlainText(array $keys = null)
    {
        $data = $this->filter($keys, $this->json);

        return date('Y-m-d H:i:s', $this->time) .
            static::TEXT_SEPARATOR .
            $this->implode_recursive(static::TEXT_SEPARATOR, $data);
    }

    public function getTime()
    {
        return $this->time;
    }

    private function init($line)
    {
        $line = trim($line);
        if (!$line) {
            throw new InvalidLineException();
        }

        @list($timestamp, $logname, $json_str) = explode("\t", $line);

        $this->validate_explode_result($timestamp, $logname, $json_str);

        $this->time = strtotime($timestamp);
        $this->name = $logname;
        $this->json = json_decode($json_str, true);

        $this->validate_init_result();
    }

    private function validate_explode_result($timestamp, $logname, $json_str)
    {
        if (empty($timestamp) || empty($logname) || empty($json_str)) {
            throw new InvalidLineException('insufficient columns');
        }
    }

    private function validate_init_result()
    {
        if (empty($this->time)) {
            throw new InvalidLineException('invalid timestamp');
        }
        if (empty($this->name)) {
            throw new InvalidLineException('invalid timestamp');
        }
        if (!is_array($this->json)) {
            throw new InvalidLineException('invalid json');
        }
    }

    private function implode_recursive($glue, $pieces) {
        if (!$pieces) {
            return '';
        }

        $ret = '';

        foreach ($pieces as $item) {
            if (is_array($item)) {
                $ret .= $this->implode_recursive($glue, $item) . $glue;
            } else {
                $ret .= $item . $glue;
            }
        }

        $ret = substr($ret, 0, 0 - strlen($glue));

        return $ret;
    }

    private function filter($keys, $arr)
    {
        if (!$keys) {
            return $arr;
        }

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
                break;
            }
            $cursor = &$cursor[$key];
        }

        return $cursor;
    }
}
