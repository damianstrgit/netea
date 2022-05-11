<?php

namespace Tests;
use Carbon\Carbon;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function getCarbonDate($offsetDays)
    {
        return Carbon::now()->addDays($offsetDays);
    }

    protected function getFromatedDateString($offsetDays)
    {
        return $this->getCarbonDate($offsetDays)->format(DATE_RFC3339);
    }
}
