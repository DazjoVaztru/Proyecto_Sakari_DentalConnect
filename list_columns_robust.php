<?php
try {
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('usuarios_sistema');
    if (empty($columns)) {
        // Fallback to raw query
        $columns = \Illuminate\Support\Facades\DB::select('DESCRIBE usuarios_sistema');
        file_put_contents('columns_output.txt', print_r($columns, true));
    } else {
        file_put_contents('columns_output.txt', print_r($columns, true));
    }
} catch (\Throwable $e) {
    file_put_contents('error_log.txt', $e->getMessage() . "\n" . $e->getTraceAsString());
}
