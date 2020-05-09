<?php

function create($class, $attributes = [])
{
    return factory($class)->create($attributes);
}

function make($class, $attributes = [])
{
    return factory($class)->make($attributes);
}

function raw($class, $attributes = [])
{
    return factory($class)->raw($attributes);
}

function current_user()
{
    return auth()->user();
}

function createMany($class, $times = 1, $attributes = [])
{
    return factory($class, $times)->create($attributes);
}

function makeMany($class, $times = 1, $attributes = [])
{
    return factory($class, $times)->make($attributes);
}