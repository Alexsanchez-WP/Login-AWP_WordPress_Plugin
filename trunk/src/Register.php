<?php

declare(strict_types=1);

/**
 * Register plugin
 *
 * @author AWP-Software
 * @since 2.0.0
 */

namespace Login\Awp;

use Login\Awp\Public\PublicRegister;
use Login\Awp\Admin\AdminRegister;

class Register
{
    public static function load(): void
    {
        (new PublicRegister)->load(); // Login Area
        (new AdminRegister)->load();  // Admin area
    }
}
