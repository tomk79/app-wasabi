<?php

return [
    "app" => [
        App\Wasabi\Pickles2\register::config(),
        App\Wasabi\AbstractAnalytics\register::config(),
        App\Wasabi\AbstractCalendar\register::config(),
        App\Wasabi\AbstractMeetingLog\register::config(),
        App\Wasabi\AbstractMessenger\register::config(),
        App\Wasabi\AbstractTaskmanager\register::config(),
        App\Wasabi\AbstractStrage\register::config(),
        App\Wasabi\CostManager\register::config(),
    ],
];
