<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$router = $app->make('router');

// Simulate GET /admin/login through the full stack
$request = Illuminate\Http\Request::create('/admin/login', 'GET');
try {
    $response = $app->make(Illuminate\Contracts\Http\Kernel::class)->handle($request);
    echo "status: " . $response->getStatusCode() . "\n";
    $content = $response->getContent();
    echo "has Admin Login: " . (str_contains($content, 'Admin Login') ? 'yes' : 'no') . "\n";
    if ($response->getStatusCode() !== 200) {
        // strip tags but keep text to see the exception details
        // dump livewire/laravel error payload if present
        if (preg_match('/data-debug-page="([^"]+)"/', $content, $m)) {
            echo "debug payload: " . $m[1] . "\n";
        }
        // Laravel 11+ error page: exception message appears near "InvalidArgumentException"
        $plain = strip_tags($content);
        $pos = strpos($plain, 'InvalidArgumentException');
        if ($pos !== false) {
            echo "exception: " . substr($plain, $pos, 400) . "\n";
        }
        // also search for "No hint path" text
        $pos2 = strpos($plain, 'No hint path');
        if ($pos2 !== false) {
            echo "hint error: " . substr($plain, $pos2, 200) . "\n";
        }
    }
} catch (\Throwable $e) {
    echo "THROWN: " . get_class($e) . ": " . $e->getMessage() . "\n";
    echo "at " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}
