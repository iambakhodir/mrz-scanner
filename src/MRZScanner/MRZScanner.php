<?php


namespace bakhodir\MRZScanner;


class MRZScanner
{
    /**
     * @var string
     */
    public $command = 'mrz';
    /**
     * @var string
     */
    public $file = "";

    /**
     * @var bool
     */
    public $isJson = false;

    /**
     * @var string|array
     */
    public $result;

    /**
     * @var int
     */
    public $validScore = 90;

    /**
     * @param $validScore
     * @return $this
     */
    public function setValidScore($validScore)
    {
        $this->validScore = $validScore;
        return $this;
    }

    /**
     * @return int
     */
    public function getValidScore(): int
    {
        return $this->validScore;
    }

    /**
     * @return $this
     */
    public function json()
    {
        $this->command .= " --json";
        $this->isJson = true;

        return $this;
    }

    /**
     * @param $filePath
     * @return $this
     */
    public function setFile($filePath)
    {
        $this->file = $filePath;
        return $this;
    }


    /**
     * @return string
     */
    public function getCommand(): string
    {
        return escapeshellcmd($this->command);
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $this->result = trim(shell_exec($this->command . " " . $this->getFile() . " 2>&1"));
        return $this;
    }

    /**
     * @return array|string
     */
    public function getResult()
    {
        return $this->isJson ? json_decode($this->result, true) : $this->result;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return ($this->getScore() > $this->getValidScore());
    }

    /**
     * @return float|int
     */
    public function getScore()
    {
        $result = $this->getResult();
        if ($this->isJson) {
            return array_key_exists('valid_score', $result) ? abs($result['valid_score']) : 0;
        } else {
            preg_match('/^.*?valid_score\s+([0-9]+).*?$/m', $result, $matches);
            return abs($matches[1]) ?? 0;
        }
    }
}