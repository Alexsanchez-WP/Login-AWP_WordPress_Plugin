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
    public string $dirUrl;
    public string $dirPath;
    public string $domainPath;

    /**
     * Summary of __construct
     *
     * @param string $dir_url
     * @param string $dir_path
     * @param string $domain_path
     */
    public function __construct($dir_url, $dir_path, $domain_path)
    {
        $this->dirUrl = $dir_url;
        $this->dirPath = $dir_path;
        $this->domainPath = $domain_path;
    }

    public function load(): void
    {
        add_action(
            'plugins_loaded',
            array($this, 'loginAwpTextdomain')
        );

        $public = new PublicRegister(dir_url: $this->dirUrl);
        $public->load();

        $admin = new AdminRegister(dir_url: $this->dirUrl);
        $admin->load();
    }

    public function loginAwpTextdomain(): void
    {
        load_plugin_textdomain(
            'login-awp',
            false,
            $this->domainPath
        );
    }
}
