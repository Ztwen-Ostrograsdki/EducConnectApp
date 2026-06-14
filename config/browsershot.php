<?php
// config/browsershot.php

return [
    'node_binary'   => env('BROWSERSHOT_NODE_PATH',   'node'),
    'npm_binary'    => env('BROWSERSHOT_NPM_PATH',    'npm'),
    'chrome_binary' => env('BROWSERSHOT_CHROME_PATH', 'chrome'),
    'no_sandbox'    => env('BROWSERSHOT_NO_SANDBOX',  false),
    'timeout'       => env('BROWSERSHOT_TIMEOUT',     60000),

    /*
    |--------------------------------------------------------------------------
    | Arguments Chromium additionnels selon l'OS
    |--------------------------------------------------------------------------
    | Sur Windows : --disable-gpu évite les crashs fréquents en mode headless
    | Sur Linux   : --no-sandbox + --disable-setuid-sandbox sont requis
    */
    'chromium_args' => env('APP_ENV') === 'production'
        ? ['--no-sandbox', '--disable-setuid-sandbox', '--disable-dev-shm-usage']
        : ['--disable-gpu'],
];