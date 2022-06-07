export XDEBUG_CONFIG="client_port=9003"
export XDEBUG_MODE="debug,develop,coverage"
php -dxdebug.start_with_request=yes ./vendor/bin/phpunit --testdox-html ./logs/tests/test.html --coverage-html ./logs/coverage/