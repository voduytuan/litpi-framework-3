<?php

namespace Litpi;

class Timer
{
    //declare variables
    private $starttime;
    private $endtime;

    public function __construct()
    {
        $this->starttime = 0;
        $this->endtime = 0;
    }

    //	start function, gets start time
    public function start()
    {
         $micro = microtime();
         $micro = explode(' ', $micro);
         $this->starttime = $micro[1] + $micro[0];
    }

    // stop function, gets stop time
    public function stop()
    {
         $micro = microtime();
         $micro = explode(' ', $micro);
         $this->endtime = $micro[1] + $micro[0];
    }

    //	works out the total time from the start time
    //	and end time, $precision may be specified
    //	if a certain precision is required
    public function getExecTime($precision = 4, $roundToSecond = true)
    {
        if ($roundToSecond) {
            $duration = round($this->endtime - $this->starttime, $precision);
        } else {
            $duration = $this->endtime - $this->starttime;
        }

         return $duration;
    }
}
