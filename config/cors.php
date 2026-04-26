<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    // 'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // 'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    // 'allowed_origins_patterns' => [],

    // 'allowed_headers' => ['*'],

    // 'exposed_headers' => [],

    // 'max_age' => 0,

    // 'supports_credentials' => false,

'paths' => [
    'api/*',           // Semua API routes
    'sanctum/csrf-cookie',  // CSRF untuk SPA
    'login',           // Custom login route
    'logout',          // Custom logout route
],

'allowed_methods' => ['*'],  // GET, POST, PUT, DELETE, etc

// REACT DEVELOPMENT
'allowed_origins' => [
    'http://localhost:3000',     // React default
    'http://127.0.0.1:3000',     // React alternate
    'http://localhost:5173',     // Vite default
    'http://127.0.0.1:5173',     // Vite alternate
],

// // Hanya method yang dibutuhkan
'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

// PRODUCTION
// 'allowed_origins' => [
//     'https://yourapp.com',
//     'https://www.yourapp.com',
// ],

'allowed_origins_patterns' => [],

'allowed_headers' => [
    '*',                    // Semua header
    'Authorization',        // Bearer Token
    'X-Requested-With',     // AJAX detection
    'X-API-Version',        // Custom version
    'X-Tenant-ID',          // Multi-tenant
],

'exposed_headers' => [
    'X-API-Version',
    'X-Total-Count',        // Pagination
],

'max_age' => 86400,     // 24 jam cache CORS

'supports_credentials' => true,  //

];
