<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\ModelTestCase;

class UserTest extends ModelTestCase
{
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new User(), [
            'name', 'email', 'password',
        ], [
            'password', 'remember_token',
        ], ['*'], [], ['id' => 'int', 'email_verified_at' => 'datetime']);
    }
}
