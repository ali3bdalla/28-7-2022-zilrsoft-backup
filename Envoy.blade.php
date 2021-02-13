@servers(['prod' => 'prod@135.181.117.189','dev' => 'dev@135.181.117.189'])
{{--  --}}
@setup
$repository = 'git@gitlab.zilrsoft.com:3li3bdalla/zilrsoft.git';
$activeEnv = $environment;
if ($environment && $environment === 'production')
{
$releases_dir = '/var/www/com.zilrsoft/releases';
$app_dir = '/var/www/com.zilrsoft';
$branch = 'master';
$push_on = 'prod';
}
else
{
$releases_dir = '/var/www/com.zilrsoft.dev/releases';
$app_dir = '/var/www/com.zilrsoft.dev';
$branch = 'development';
$push_on = 'dev';
}

$current_dir = $app_dir . '/current';
$release = date('YmdHis');
$new_release_dir = $releases_dir .'/'. $release;
@endsetup

@story('deploy')
clone_repository
composer_install
npm_install
npm_run_dev
update_symlinks
migrate
cleanup_releases
@endstory

@task('clone_repository',['on' => [$push_on]])
echo 'Cloning repository'
[ -d {{ $releases_dir }} ] || mkdir {{ $releases_dir }}
git clone --branch {{ $branch }} --depth 1 {{ $repository }} {{ $new_release_dir }}
cd {{ $new_release_dir }}
git reset --hard {{ $commit }}
@endtask

@task('composer_install',['on' => [$push_on]])
echo "composer install ({{ $release }})"
cd {{ $new_release_dir }}
composer install --prefer-dist --no-scripts -q -o
@endtask

@task('npm_install',['on' => [$push_on]])
echo "npm install ({{ $release }})"
cd {{ $new_release_dir }}
npm install --quiet --no-progress
@endtask

@task('npm_run_dev',['on' => [$push_on]])
echo "npm run dev ({{ $release }})"
cd {{ $new_release_dir }}
npm run dev --quiet --no-progress
@endtask

@task('npm_run_prod',['on' => [$push_on]])
echo "npm run prod ({{ $release }})"
cd {{ $new_release_dir }}
npm run prod --quiet --no-progress
@endtask

@task('update_symlinks',['on' => [$push_on]])
echo "Create folder"
[ -d {{ $app_dir }}/storage ] || mkdir {{ $app_dir }}/storage
[ -d {{ $app_dir }}/storage/framework ] || mkdir {{ $app_dir }}/storage/framework
[ -d {{ $app_dir }}/storage/framework/sessions ] || mkdir {{ $app_dir }}/storage/framework/sessions
[ -d {{ $app_dir }}/storage/framework/views ] || mkdir {{ $app_dir }}/storage/framework/views
[ -d {{ $app_dir }}/storage/framework/cache ] || mkdir {{ $app_dir }}/storage/framework/cache
chmod 777 {{ $new_release_dir }}/vendor/mpdf/mpdf/tmp
echo "Linking storage directory"
rm -rf {{ $new_release_dir }}/storage
ln -nfs {{ $app_dir }}/storage {{ $new_release_dir }}/storage

echo 'Linking .env file'
ln -nfs {{ $app_dir }}/.env {{ $new_release_dir }}/.env

echo 'Linking current release'
ln -nfs {{ $new_release_dir }} {{ $current_dir }}


echo "Generate Storage Link in public"
cd {{ $new_release_dir }} 
php artisan storage:link
@endtask

@task('migrate',['on' => [$push_on]])
echo "Migrate db"
cd {{ $current_dir }}
php artisan migrate --force
@endtask

@task('cache',['on' => [$push_on]])
echo "Cleaning cache"
cd {{ $current_dir }}
php artisan route:cache
php artisan config:cache
php artisan view:cache
@endtask


@task('cleanup_releases',['on' => [$push_on]])
echo "cleanup releases ({{ $release }})"
cd {{ $releases_dir }}
ls -1d 20* | head -n -5 | xargs -d '\n' rm -Rf
@endtask
