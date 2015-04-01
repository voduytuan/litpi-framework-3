<?php

namespace Vendor\Litpi;

class TimerTest extends \Codeception\TestCase\Test
{
    public function testStart()
    {
        $myTimer = new \Litpi\Timer();

        //Do not round to second
        $myTimer->start();
        usleep(10);
        $myTimer->stop();
        $duration = $myTimer->getExecTime(4, false);
        $this->assertTrue(is_float($duration));

        //Round to second
        $myTimer->start();
        usleep(10);
        $myTimer->stop();
        $duration = $myTimer->getExecTime(4, true);
        $this->assertTrue(is_float($duration));

        unset($myTimer);
    }
}
