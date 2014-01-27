#!/bin/bash

cd $(dirname $0)
mkdir download
pushd download

wget http://geolite.maxmind.com/download/geoip/database/GeoLiteCity_CSV/GeoLiteCity-latest.zip
wget http://download.maxmind.com/download/geoip/database/asnum/GeoIPASNum2.zip


unzip GeoIPASNum2.zip
unzip GeoLiteCity-latest.zip
cp *csv /tmp/
cp */*csv /tmp/

popd
mysql geoip < create.sql
mysql geoip < procedure_init.sql
