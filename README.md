nanobox-adapter-single-server
=============================

A Symfony project created on April 29, 2017, 6:58 am.

# Installation process

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

## TODO

- Make sure the iptables config is saved across reboots
- The systemd service won't start on boot