@servers(['localhost' => '127.0.0.1'])

@story('deploy')
run_composer
deploy_app
@endstory

@task('run_composer')
echo "Installing composer dependencies"
composer install --prefer-dist --no-scripts -q -o
@endtask

@task('deploy_app')
echo "Running Migrations"
php artisan migrate --force
echo "Seeding Database"
php artisan db:seed --force
@endtask