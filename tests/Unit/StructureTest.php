<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

test('Not debugging statements are left in our code.')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed();

arch('App Model Structure check')
    ->expect('App\Models')
    ->toExtend(Model::class)
    ->ignoring(User::class)

    ->expect('App\Enums')
    ->toBeEnums();
