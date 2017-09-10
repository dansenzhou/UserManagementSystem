FROM ubuntu:14.04

RUN apt-get update && apt-get install -y software-properties-common language-pack-en-base \
    && LC_ALL=en_US.UTF-8 add-apt-repository -y ppa:ondrej/php \
    && apt-get update \
    && apt-get install -y php7.1 php7.1-fpm php7.1-pdo php7.1-mysql mcrypt php7.1-gd curl php7.1-intl php7.1-cli zip unzip php7.1-zip php-pear \
       php7.1-curl libbson-1.0 php-xml php7.1-xml php-dev php-redis php7.1-mbstring sendmail supervisor \
    && mkdir /run/php \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN sed -i -e 's/listen = \/run\/php\/php7.1-fpm.sock/listen = 0.0.0.0:9000/g' /etc/php/7.1/fpm/pool.d/www.conf \
    && sed -i -e 's/;daemonize = yes/daemonize = no/g' /etc/php/7.1/fpm/php-fpm.conf

RUN apt-get update && apt-get install -y git

# xdebug
RUN pecl install xdebug && \
    echo "zend_extension=xdebug.so" > /etc/php/7.1/mods-available/xdebug.ini && \
    ln -sf /etc/php/7.1/mods-available/xdebug.ini /etc/php/7.1/fpm/conf.d/20-xdebug.ini && \
    ln -sf /etc/php/7.1/mods-available/xdebug.ini /etc/php/7.1/cli/conf.d/20-xdebug.ini

# Install MySQL
RUN apt-get install -y mysql-client

# Install Sqlite
RUN apt-get install -y sqlite3 php7.1-sqlite3

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set aliases
ADD .bash_aliases /root/
RUN echo ". ~/.bash_aliases" >> ~/.bashrc

# Configure PHP
ADD app.ini /etc/php/7.1/fpm/conf.d/
ADD app.ini /etc/php/7.1/cli/conf.d/

ADD app.pool.conf /etc/php/7.1/fpm/pool.d/

RUN usermod -u 1000 www-data

CMD ["php-fpm7.1"]

EXPOSE 9000



