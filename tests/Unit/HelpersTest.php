<?php

namespace audunru\VersionWarning\Tests\Unit;

use audunru\VersionWarning\Tests\TestCase;
use Illuminate\Support\Facades\File;

class HelpersTest extends TestCase
{
    public function testAppVersionHelperReturnsAppVersion()
    {
        File::partialMock()
            ->shouldReceive('get')
            ->once()
            ->andReturn('{"version": "3.2.1"}');

        $appVersion = app_version();

        $this->assertEquals('3.2.1', $appVersion);
    }
}
