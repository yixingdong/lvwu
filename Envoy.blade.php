@servers(['web'=>'root@182.92.162.175'])

@task('backup',['on' => 'web'])
    cd /www/app/exd
    php artisan db:backup
@endtask

@task('recovery',['on' => 'web'])
    cd /www/app/exd
    php artisan migrate:refresh --seed
@endtask

@task('up',['on'=>'web'])
    cd /www/app/exd
    php artisan up
    php artisan queue:listen
@endtask

@task('halt',['on'=>'web'])
    cd /www/app/exd
    php artisan down
@endtask

@task('clear',['on'=>'web'])
    cd /www/app/exd
    composer dump-autoload
    php artisan cache:clear
@endtask

@task('update',['on'=>'web'])
    cd /www/app/exd
    composer update
    composer dump-autoload
    php artisan cache:clear
@endtask

@task('zero',['on'=>'web'])
    cd /www/app/exd
    php artisan migrate:refresh
@endtask

@task('faker',['on'=>'web'])
    cd /www/app/exd
    php artisan tinker
    namespace App
    factory( {{$m}}::class, {{$n}})->create()
@endtask

@task('mc',['on'=>'web'])
    cd /www/app/exd
    php artisan make:model {{$m}} -m
    php artisan make:controller {{$m}}Controller
    php artisan migrate
    composer dump-autoload
@endtask


