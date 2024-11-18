#!/bin/bash

COUNT=${1:-30000}
OWNERS=(
  '0xDfCc1215c82e39F89A617b1e587704090E84e536'
  '0xAA82FB0Df1B866e4F04B154dE51ACd8734D7E688'
  '0xEb4E3e9fA819E69e5Df4ea35b9C7973062C96de9'
  '0x8a11D42Be55217369ff6C5cEE519D22c60952cfB'
)
KEY="0x$(cat /dev/urandom | tr -dc A-F0-9 | head -c64)"
while [[ $COUNT -gt 0 ]]; do
  SALT=$(cat /dev/urandom | tr -dc 0-9 | head -c${2:-25})
  ADDR=$(docker run -it safe-cli:testing safe-creator --threshold 2 --owners ${OWNERS[@]} --salt-nonce $SALT --dry-run --quiet https://mainnet.optimism.io $KEY)
  echo -n "$(printf '%4d' $COUNT): $SALT: $ADDR"
  COUNT=$((COUNT - 1))
done
