sudo apt install mkcert libnss3-tools
mkcert -install
mkcert local.dev
sudo mv local.dev.pem /etc/ssl/certs/
sudo chown -c root:root /etc/ssl/certs/local.dev*
sudo mv local.dev-key.pem /etc/ssl/private/
sudo chown -c root:ssl-cert /etc/ssl/private/local.dev-key.pem
grep -i -r "SSLCertificateFile" /etc/apache2/
sudoedit /etc/apache2/sites-available/default-ssl.conf
sudoedit /etc/hosts
apachectl configtest
sudo a2enmod ssl
sudo a2ensite default-ssl
systemctl restart apache2
mkcert -CAROOT
