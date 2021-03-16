dropdb prod
createdb prod
psql -c "\i /var/develop/backups/zilrsoft/db/prod_2021-03-11.Thursday.sql"
php artisan command:accounting_daily_update_accounts_snapshots_command
