<?php

namespace App\Enum;

class EventEnum extends Enum
{
    /**
     * User start learn course
     * Type event. Table type_events
     */
    const START_COURSE = 1;
    /**
     * User finish learn course
     * Type event. Table type_events
     */
    const FINISH_COURSE = 2;
    /**
     * Course time over
     * Type event. Table type_events
     */
    const TIME_OVER_COURSE = 3;

    /**
     * Count attempts for test range out
     * Type event. Table type_events
     */
    const ATTEMPTS_TEST_OUT = 4;
}
