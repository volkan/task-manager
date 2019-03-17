FROM debian:jessie

RUN apt-get update && apt-get install -y \
    nginx

RUN rm /etc/nginx/sites-enabled/default
#VOLUME ["/nginx"]
#VOLUME nginx.conf /etc/nginx/nginx.conf
#VOLUME symfony.conf /etc/nginx/sites-available/symfony.conf

#RUN ln -s /etc/nginx/sites-available/symfony.conf /etc/nginx/sites-enabled/symfony \
#&& rm /etc/nginx/sites-enabled/default

RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf

RUN usermod -u 1000 www-data

#CMD ["nginx"]