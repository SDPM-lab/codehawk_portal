FROM newdeveloper/apache-php-composer:28-05-2020

RUN apt-get update
RUN apt-get install root-tail -y
RUN rm -f /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
RUN apt install -y php-pear php7.4-curl php7.4-dev php7.4-gd php7.4-mbstring php7.4-zip php7.4-mysql php7.4-xml php7.4-fpm php7.4-intl
WORKDIR /var/www/html

EXPOSE 80

CMD ["/bin/bash","-c","/run.sh"]