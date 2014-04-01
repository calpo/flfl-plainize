<?php
namespace FlflPlainize;

use FlflPlainize\Exception\InvalidLineException;

class Line
{
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
     * @return string
     */
    public function toPlainText()
    {
        return date('Y-m-d H:i:s', $this->time) .
            @implode("¥t", $this->json);
    }

    public function getTime()
    {
        return $this->time;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getJson()
    {
        return $this->json;
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
}
