# Setting up a Linux server with Debian

----------------------- Install Pakages ----------------------
# Install PHP on the server
apt-get install php

# Example: Install Apache2
apt-get install apache2

# Install pip for Python 3
apt-get update
sudo apt install python3-pip


# Install Magika
pip install magika

----------------------- Source code files -----------------------
# Create a folder to upload files into:

mkdir /var/www/html/uploads

# copy index.html and Magika.php to /var/www/html/

# Adjust permissions : allow servers like Apache or Nginx to access website files
sudo chown -R www-data:www-data /var/www/html/uploads
# Adjust permissions : read, write, and execute permissions
sudo chmod -R 755 /var/www/html/uploads


----------------------- upload large files ------------------------

# To upload large files, modify the PHP configuration:
# Open the PHP configuration file
nano /etc/php/[version]/[server]/php.ini

# Modify the following settings to allow larger file uploads:
upload_max_filesize = 15M
post_max_size = 15M

# Save the changes and exit the editor and restart the service :
/etc/init.d/apache2 restart

# Additionally, make Size changes inside:
nano /var/www/html/magika.php
