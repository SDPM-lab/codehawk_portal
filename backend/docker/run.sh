chown -R www-data:www-data /var/www/html/writable && chown -R www-data:www-data /var/www/html/public && chmod 776 -R /var/www/html/writable
cd /var/www/html && composer install
service apache2 start
tail -f /var/log/apache2/access.log