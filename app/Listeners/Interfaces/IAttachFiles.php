<?php

namespace App\Listeners\Interfaces;

interface IAttachFiles
{
    public function getRequest();
    public function getEntity();
}