<?php

include 'FootballException.php';

class Football
{
    const ROUND = 10;

    public $maxGoals = 5;

    private $data;
    private $c1;
    private $c2;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function setMaxGoals($value)
    {
        $this->maxGoals = $value;
    }

    public function setFirstCommand($key)
    {
        if(array_key_exists($key, $this->data)) {
            $this->c1 = $this->preparation($this->data[$key]);
        } else {
            throw new \FootballException($key);
        }
    }

    public function setSecondCommand($key)
    {
        if(array_key_exists($key, $this->data)) {
            $this->c2 = $this->preparation($this->data[$key]);
        } else {
            throw new \FootballException($key);
        }
    }

    public function calculation()
    {
        $epsGoalsScoredC1 = $this->epsGoalsScored($this->c1, $this->c2);
        $epsGoalsScoredC2 = $this->epsGoalsScored($this->c2, $this->c1);

        $eventProbabilityC1 = $this->poissonFormula($epsGoalsScoredC1, $this->c1['games']);        
        $eventProbabilityC2 = $this->poissonFormula($epsGoalsScoredC2, $this->c2['games']);

        $res1 = mt_rand(0, ceil($eventProbabilityC1));
        $res2 = mt_rand(0, ceil($eventProbabilityC2));

        return [$res1, $res2];
    }

    private function poissonFormula($eGs, $games)
    {
        $value = 0.0;
        for($i = 0; $i < $this->maxGoals; $i++) {
            $value += substr(pow($games * $eGs, $i) / $this->factorial($i) * exp(-$games * $eGs), 0, self::ROUND);
        }

        return round($value / $this->maxGoals, self::ROUND);
    }

    private function preparation($obj)
    {
        return [
            'attak'   => round(($obj['goals']['scored'] / $obj['games']), self::ROUND),
            'defense' => round(($obj['goals']['skiped'] / $obj['games']), self::ROUND),
            'games'   => $obj['games'],
            'scored'  => $obj['goals']['scored']
        ];        
    }

    private function epsGoalsScored($c1, $c2)
    {
        return $c1['attak'] * $c2['defense'] * $c1['scored'] / 100;
    }

    private function factorial($n)
    {
        if ($n == 0) {
            return 1;
        } else {
            return $n * $this->factorial($n - 1);
        }
    }
}