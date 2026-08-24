# ============================================================
# Dockerfile for Deploying Sailor WordPress to Render.com
# ============================================================
FROM wordpress:php8.2-apache

# Set large upload limits (512MB) & PHP settings
RUN echo "upload_max_filesize = 512M" > /usr/local/etc/php/conf.d/uploads.ini \
 && echo "post_max_size = 512M" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "memory_limit = 512M" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "max_execution_time = 600" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "max_input_time = 600" >> /usr/local/etc/php/conf.d/uploads.ini

# Copy custom sailor theme
COPY . /var/www/html/wp-content/themes/sailor/

# Copy TiDB Cloud compatibility drop-in to wp-content/db.php
COPY db.php /var/www/html/wp-content/db.php

# Ensure Apache file permissions
RUN chown -R www-data:www-data /var/www/html/wp-content/ \
 && chmod -R 755 /var/www/html/wp-content/

EXPOSE 80
CMD ["apache2-foreground"]
