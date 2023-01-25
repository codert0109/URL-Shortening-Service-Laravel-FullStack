<?php

namespace Tests\Unit;

use App\Models\Url;
use App\Street;
use Tests\ModelTestCase;

class UrlTest extends ModelTestCase
{
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Url(), [
            'destination', 'slug', 'views'
        ]);
    }
}
