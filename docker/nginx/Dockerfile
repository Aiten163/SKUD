FROM nginx:latest

RUN apt-get update && apt-get install -y \
    certbot \
    python3-certbot-nginx \
    && apt-get clean

COPY ./conf.d/ /etc/nginx/conf.d/

WORKDIR /var/www
