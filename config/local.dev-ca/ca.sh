#!/bin/bash

[ -e 'rootCA.key' ] || openssl genrsa -des3 -out rootCA.key 2048
[ -e 'rootCA.pem' ] || openssl req -x509 -new -nodes -key rootCA.key -sha256 -days 1024 -out rootCA.pem
[ -e 'server.csr' ] || openssl req -new -sha256 -nodes -out server.csr -newkey rsa:2048 -keyout server.key -config <( cat server.csr.cnf )
[ -e 'server.crt' ] || openssl x509 -req -in server.csr -CA rootCA.pem -CAkey rootCA.key -CAcreateserial -out server.crt -days 500 -sha256 -extfile v3.ext
