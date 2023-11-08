FROM nginx:stable-alpine 

WORKDIR /var/www/html 

COPY ./dockers/prod/images/nginx/nginx.conf /etc/nginx/conf.d/default.conf

COPY project/ /var/www/html

EXPOSE 80