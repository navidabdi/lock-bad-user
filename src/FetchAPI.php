<?php

declare(strict_types=1);

namespace Inpsyde\UserTableShowcase;

interface FetchAPI
{
    public function data(): array;

    public function cache(): array|bool;
}
