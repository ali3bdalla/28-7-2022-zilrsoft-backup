@servers(['web' => 'development@zilrsoft.com'])
{{--@servers(['prod' => 'ivprod@inventoryx.com'])--}}

@setup
    $repository = 'git@gitlab.com:3li3bdalla/zilrsoft.git';
    $releases_dir = '/var/www/vhosts/development/releases';
    $app_dir = '/var/www/vhosts/development';
    $current_dir = $app_dir . '/current';
    $release = date('YmdHis');
    $branch = 'development';
    $new_release_dir = $releases_dir .'/'. $release;
@endsetup

@story('deploy')
    clone_repository
    composer_install
    npm_install
    npm_run_prod
    update_symlinks
    migrate
    cache
@endstory

@task('clone_repository')
    echo 'Cloning repository'
    [ -d {{ $releases_dir }} ] || mkdir {{ $releases_dir }}
    git clone --branch {{ $branch }} --depth 1 {{ $repository }} {{ $new_release_dir }}
    cd {{ $new_release_dir }}
    git reset --hard {{ $commit }}
@endtask

@task('composer_install')
    echo "composer install ({{ $release }})"
    cd {{ $new_release_dir }}
    composer install --prefer-dist --no-scripts -q -o
@endtask

@task('npm_install')
    echo "npm install ({{ $release }})"
    cd {{ $new_release_dir }}
    npm install --quiet --no-progress
@endtask

@task('npm_run_dev')
    echo "npm run dev ({{ $release }})"
    cd {{ $new_release_dir }}
    npm run dev --quiet --no-progress
@endtask

@task('npm_run_prod')
    echo "npm run prod ({{ $release }})"
    cd {{ $new_release_dir }}
    npm run prod --silent --no-progress
    echo "Deleting node_modules folder ({{ $release }})"
    rm -rf node_modules
@endtask

@task('update_symlinks')
    echo "Create folder"
    [ -d {{ $app_dir }}/storage ] || mkdir {{ $app_dir }}/storage
    [ -d {{ $app_dir }}/storage/framework ] || mkdir {{ $app_dir }}/storage/framework
    [ -d {{ $app_dir }}/storage/framework/sessions ] || mkdir {{ $app_dir }}/storage/framework/sessions
    [ -d {{ $app_dir }}/storage/framework/views ] || mkdir {{ $app_dir }}/storage/framework/views
    [ -d {{ $app_dir }}/storage/framework/cache ] || mkdir {{ $app_dir }}/storage/framework/cache

    echo "Linking storage directory"
    rm -rf {{ $new_release_dir }}/storage
    ln -nfs {{ $app_dir }}/storage {{ $new_release_dir }}/storage

    echo 'Linking .env file'
    ln -nfs {{ $app_dir }}/.env {{ $new_release_dir }}/.env

    echo 'Linking current release'
    ln -nfs {{ $new_release_dir }} {{ $current_dir }}
@endtask

@task('migrate')
    echo "Migrate db"
    cd {{ $current_dir }}
    php artisan migrate
@endtask

@task('cache')
    echo "Cleaning cache"
    cd {{ $current_dir }}
    php artisan route:cache
    php artisan config:cache
    php artisan view:cache
@endtask
