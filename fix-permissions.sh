sudo find ./ -type d -exec chmod 775 {} \;
sudo chmod 777 -R ./storage
sudo chmod 777 -R ./bootstrap/cache
sudo find ./ -type f -exec chmod 664 {} \;
php artisan optimize:clear
