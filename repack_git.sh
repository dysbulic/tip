#!/bin/bash

OUT="lib/git/objects/pack"
mkdir -p "$OUT"

mv .git/objects/pack/* "$OUT"
for file in "$OUT"/*.pack; do
  git unpack-objects -r < $file
done
#rm "$OUT"/*
