<?php
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OpCache limpiado.";
} else {
    echo "OpCache no está habilitado.";
}
