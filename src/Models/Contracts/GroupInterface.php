<?php

namespace Redsnapper\LaravelDoorman\Models\Contracts;

interface GroupInterface
{


    public function users();

    public function getKey();

    public function getKeyName();

    public function getPivotKeyName(): string;
}
