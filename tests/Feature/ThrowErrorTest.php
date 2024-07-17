<?php

namespace audunru\VersionWarning\Tests\Feature;

use audunru\VersionWarning\Tests\TestCase;

class ThrowErrorTest extends TestCase
{
    public function testThrowsError()
    {
        $original = config('version-warning.errors.throw');
        config(['version-warning.errors.throw' => true]);

        $response = $this->get('/test');

        $response->assertInternalServerError();

        config(['version-warning.errors.throw' => $original]);
    }
}
