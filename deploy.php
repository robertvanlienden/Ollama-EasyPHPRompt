<?php
namespace Deployer;

require 'recipe/symfony.php';

// Config

add('shared_files', [
    'public/.htaccess',
    'public/.htpasswd',
]);
add('shared_dirs', []);
add('writable_dirs', []);

set('writable_mode', 'chmod');

// Hosts

host('')
    ->set('remote_user', 'u43014p86923')
    ->set('http_user', 'u43014p86923')
    ->set('port', 7685)
    ->setDeployPath('/home/u43014p86923/domains/jordy.robertvanlienden.dev')
    ->setSshArguments([
        '-o UserKnownHostsFile=/dev/null',
        '-o StrictHostKeyChecking=no',
    ]);

// Hooks
task('deploy:update_code', function () {
    upload(__DIR__.'/', '{{release_path}}');
});

after('deploy:failed', 'deploy:unlock');
