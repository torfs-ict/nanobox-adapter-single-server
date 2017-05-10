# Single server adapter

## Description

Custom hosting endpoint adapter for Nanobox. If you have a single server available but still wish to use Nanobox for 
deployment, this app will perform the communication with the Nanobox dashboard.

We use the Symfony built-in server to run as it's actually a quite simple app, and we don't want to interfere with any
Nanobox services.

## Installation process

Install a basic Ubuntu server, with only the SSH server package selected. After this, 
follow the procedure below to set up the endpoint adapter.

```bash
sudo -s
git clone https://github.com/torfs-ict/nanobox-adapter-single-server.git /srv/nanobox-endpoint
cd /srv/nanobox-endpoint
sudo apt update
sudo apt upgrade -y
sudo apt install -y php7.0-curl php7.0-zip php7.0-xml
composer install
cp systemd.service /etc/systemd/system/nanobox-endpoint.service
systemctl daemon-reload
systemctl enable nanobox-endpoint
systemctl start nanobox-endpoint
```