<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

if (file_exists($this->DocPath() . 'config_' . $this->Environment() . '.php')) {
    $customConfig = $this->loadConfig($this->DocPath() . 'config_' . $this->Environment() . '.php');
} elseif (file_exists($this->DocPath() . 'config.php')) {
    $customConfig = $this->loadConfig($this->DocPath() . 'config.php');
} else {
    $customConfig = [];
}

if (!is_array($customConfig)) {
    throw new Enlight_Exception('The custom configuration file must return an array.');
}

return array_replace_recursive([
    'custom' => [],
    'trustedproxies' => [],
    'cdn' => [
        'backend' => 'local',
        'strategy' => 'md5',
        'liveMigration' => false,
        'adapters' => [
            'local' => [
                'type' => 'local',
                'mediaUrl' => '',
                'permissions' => [
                    'file' => [
                        'public' => 0666 & ~umask(),
                        'private' => 0600 & ~umask(),
                    ],
                    'dir' => [
                        'public' => 0777 & ~umask(),
                        'private' => 0700 & ~umask(),
                    ],
                ],
                'path' => realpath(__DIR__ . '/../../../'),
            ],
            'ftp' => [
                'type' => 'ftp',
                'mediaUrl' => '',

                'host' => '',
                'username' => '',
                'password' => '',
                'port' => 21,
                'root' => '/',
                'passive' => true,
                'ssl' => false,
                'timeout' => 30,
            ],
        ],
    ],
    'csrfProtection' => [
        'frontend' => getenv('SWCSRFPROTECTION_FRONTEND') == '1' ? true : false,
        'backend' => getenv('SWCSRFPROTECTION_BACKEND') == '1' ? true : false,
    ],
    'snippet' => [
        'readFromDb' => getenv('SWSNIPPET_READ_FROM_DB') == '1' ? true : false,
        'writeToDb' => getenv('SWSNIPPET_WRITE_TO_DB') == '1' ?  true : false,
        'readFromIni' => getenv('SWSNIPPET_READ_FROM_INI') == '1' ? true : false,
        'writeToIni' => getenv('SWSNIPPET_WRITE_TO_INI') == '1' ? true : false,
        'showSnippetPlaceholder' => getenv('SWSNIPPET_SHOW_SNIPPET_PLACE_HOLDER') == '1' ? true : false,
    ],
    'errorHandler' => [
        'throwOnRecoverableError' => false,
    ],
    'db' => [
        'username' => getenv('SWDB_USER'),
        'password' => getenv('SWDB_PASS'),
        'dbname' => getenv('SWDB_DATABASE'),
        'host' => getenv('SWDB_HOST'),
        'port' => getenv('SWDB_PORT'),
        'charset' => 'utf8',
        'adapter' => 'pdo_mysql',
    ],
    'es' => [
        'prefix' => 'sw_shop',
        'enabled' => false,
        'write_backlog' => true,
        'number_of_replicas' => null,
        'number_of_shards' => null,
        'wait_for_status' => 'green',
        'client' => [
            'hosts' => [
                'localhost:9200',
            ],
        ],
    ],
    'front' => [
        'noErrorHandler' => getenv('SWFRONT_NO_ERROR_HANDLER') == '1' ? true : false,
        'throwExceptions' => getenv('SWFRONT_THROW_EXCEPTIONS') == '1' ? true : false,
        'disableOutputBuffering' => getenv('SWFRONT_DISABLE_OUTPUT_BUFFERING') == '1' ? true : false,
        'showException' => getenv('SWFRONT_SHOW_EXCEPTION') == '1' ? true : false,
        'charset' => getenv('SWFRONT_CHARSET'),
    ],
    'config' => [],
    'store' => [
        'apiEndpoint' => 'https://api.shopware.com',
    ],
    'plugin_directories' => [
        'Default' => $this->AppPath('Plugins_Default'),
        'Local' => $this->AppPath('Plugins_Local'),
        'Community' => $this->AppPath('Plugins_Community'),
        'ShopwarePlugins' => $this->DocPath('custom_plugins'),
    ],
    'template' => [
        'compileCheck' => true,
        'compileLocking' => true,
        'useSubDirs' => true,
        'forceCompile' => false,
        'useIncludePath' => true,
        'charset' => 'utf-8',
        'forceCache' => false,
        'cacheDir' => $this->getCacheDir() . '/templates',
        'compileDir' => $this->getCacheDir() . '/templates',
    ],
    'mail' => [
        'charset' => 'utf-8',
    ],
    'httpcache' => [
        'enabled' => getenv('SWHTTPCACHE_ENABLED') == '1' ? true : false,
        'lookup_optimization' => getenv('SWHTTPCACHE_LOOKUP_OPTIMIZATION') == '1' ? true : false,
        'debug' => getenv('SWHTTPCACHE_DEBUG') == '1' ? true : false,
        'default_ttl' => getenv('SWHTTPCACHE_DEFAULT_TTL'),
        'private_headers' => ['Authorization', 'Cookie'],
        'allow_reload' => getenv('SWHTTPCACHE_ALLOW_RELOAD') == '1' ? true : false,
        'allow_revalidate' => getenv('SWHTTPCACHE_ALLOW_REVALIDATE') == '1' ? true : false,
        'stale_while_revalidate' => getenv('SWHTTPCACHE_STALE_WHILE_REVALIDATE'),
        'stale_if_error' => getenv('SWHTTPCACHE_STALE_IF_ERROR') == '1' ? true : false,
        'cache_dir' => $this->getCacheDir() . '/html',
        'cache_cookies' => explode('|', getenv('SWHTTPCACHE_CACHE_COOKIES')),
    ],
    'session' => [
        'cookie_lifetime' => 0,
        'cookie_httponly' => 1,
        'gc_probability' => 1,
        'gc_divisor' => 200,
        'save_handler' => 'db',
        'use_trans_sid' => 0,
        'locking' => true,
    ],
    'phpsettings' => [
        'error_reporting' => E_ALL & ~E_USER_DEPRECATED,
        'display_errors' => getenv('SWPHPSETTINGS_DISPLAY_ERRORS'),
        'date.timezone' => getenv('SWPHPSETTINGS_DATE_TIMEZONE'),
    ],
    'cache' => [
        'frontendOptions' => [
            'automatic_serialization' => true,
            'automatic_cleaning_factor' => 0,
            'lifetime' => 3600,
            'cache_id_prefix' => md5($this->getCacheDir()),
        ],
        'backend' => 'auto', // e.G auto, apcu, xcache, redis
        'backendOptions' => [
            'hashed_directory_perm' => 0777 & ~umask(),
            'cache_file_perm' => 0666 & ~umask(),
            'hashed_directory_level' => 3,
            'cache_dir' => $this->getCacheDir() . '/general',
            'file_name_prefix' => 'shopware',
        ],
    ],
    'hook' => [
        'proxyDir' => $this->getCacheDir() . '/proxies',
        'proxyNamespace' => $this->App() . '_Proxies',
    ],
    'model' => [
        'autoGenerateProxyClasses' => false,
        'attributeDir' => $this->getCacheDir() . '/doctrine/attributes',
        'proxyDir' => $this->getCacheDir() . '/doctrine/proxies',
        'proxyNamespace' => $this->App() . '\Proxies',
        'cacheProvider' => 'auto', // supports null, auto, Apcu, Array, Wincache, Xcache and redis
        'cacheNamespace' => null, // custom namespace for doctrine cache provider (optional; null = auto-generated namespace)
    ],
    'backendsession' => [
        'name' => 'SHOPWAREBACKEND',
        'cookie_lifetime' => 0,
        'cookie_httponly' => 1,
        'use_trans_sid' => 0,
        'locking' => false,
    ],
    'template_security' => [
        // @deprecated with 5.3, config switch will be removed with 5.4
        'enabled' => true,
        'php_modifiers' => include __DIR__ . '/smarty_functions.php',
        'php_functions' => include __DIR__ . '/smarty_functions.php',
    ],
], $customConfig);
