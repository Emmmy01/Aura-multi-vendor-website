# Use an official PHP image from the Docker Hub
FROM php:8.0-apache

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy the current directory contents into the container
COPY . .

# Install any dependencies (e.g., Composer, PHP extensions)
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev && docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install gd && docker-php-ext-install mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
