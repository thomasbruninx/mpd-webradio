#!/bin/bash

mkdir lib

# Download dependencies
wget -O lib/less.min.js https://cdn.jsdelivr.net/npm/less@4.2.0/dist/less.min.js
wget -O lib/jquery.min.js https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js
wget -O lib/bootstrap.min.js https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js
wget -O lib/bootstrap.min.css https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css

wget https://github.com/FloFaber/MphpD/releases/download/v1.2.5/mphpd-v1.2.5.zip -O temp.zip
unzip temp.zip -d lib
rm temp.zip
