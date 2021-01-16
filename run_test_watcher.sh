files=$@

while true; do
	./vendor/bin/phpunit $files
	sleep 5
done
