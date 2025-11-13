web: php -S 0.0.0.0:$PORT -t public public/index.php
release: php artisan optimize:clear && php artisan migrate --force && php artisan storage:link || true