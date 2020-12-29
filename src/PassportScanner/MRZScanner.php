<?php


namespace PassportScanner;


class MRZScanner
{
    /**
     * @var string
     */
    protected $command = 'mrz';
    /**
     * @var string
     */
    protected $file = "";

    /**
     * @var bool
     */
    protected $isJson = false;

    /**
     * @var string|array
     */
    protected $result;

    /**
     * @var int
     */
    protected $validScore = 90;

    /**
     * @var int
     */
    protected $score = 0;

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
        return escapeshellcmd($this->command . " " . $this->getFile());
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
        $this->result = trim(shell_exec($this->getCommand()));

        // parse score from result
        $this->extractScore();

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

    public function setScore($score)
    {
        $this->score = $score;
        return $this;
    }

    private function extractScore()
    {
        $result = $this->getResult();

        if (is_array($result) && $this->isJson) {
            $this->setScore(array_key_exists('valid_score', $result) ? abs($result['valid_score']) : 0);
            return $this->getScore();
        } else {
            preg_match('/^.*?valid_score\s+([0-9]+).*?$/m', $result, $matches);
            $this->setScore($matches[1] ? abs($matches[1]) : 0);
            return $this->getScore();
        }
    }

    /**
     * @return float|int
     */
    public function getScore()
    {
        return $this->score;
    }
}