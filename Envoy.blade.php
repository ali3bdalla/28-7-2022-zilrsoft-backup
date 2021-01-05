@servers(['web' => 'deployer@zilrsoft.com'])

@setup
    $repository = 'git@gitlab.com:3li3bdalla/zilrsoft.git';
    if ($environment && $environment === 'production')
    {
        $releases_dir = '/var/www/vhosts/zilrsoft/production/releases';
        $app_dir = '/var/www/vhosts/zilrsoft/production';
        $branch = 'master';
    }
    else
    {
        $releases_dir = '/var/www/vhosts/zilrsoft/development/releases';
        $app_dir = '/var/www/vhosts/zilrsoft/development';
        $branch = 'development';
    }

    $current_dir = $app_dir . '/current';
    $release = date('YmdHis');
    $new_release_dir = $releases_dir .'/'. $release;
@endsetup

@story('deploy')
    clone_repository
    composer_install
    npm_install
    update_symlinks
    migrate
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

