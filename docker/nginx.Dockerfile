FROM nginx:1.21

# Configure nginx
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

RUN rm -rf /var/www && mkdir -p /var/www
WORKDIR /var/www

CMD ["nginx", "-g", "daemon off;"]
