# ============================================================
# Dockerfile for Deploying Sailor WordPress to Render.com
# ============================================================
FROM wordpress:php8.2-apache

# Set upload limits & PHP settings
RUN echo "upload_max_filesize = 64M" > /usr/local/etc/php/conf.d/uploads.ini \
 && echo "post_max_size = 64M" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/uploads.ini

# Copy custom sailor theme
COPY . /var/www/html/wp-content/themes/sailor/

# Ensure Apache file permissions
RUN chown -R www-data:www-data /var/www/html/wp-content/themes/sailor/ \
 && chmod -R 755 /var/www/html/wp-content/themes/sailor/

EXPOSE 80
CMD ["apache2-foreground"]
