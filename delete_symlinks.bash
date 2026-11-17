#!/bin/bash
# Script used to relink symlinks in chamber folders

# Get reference directories from current dir and settings file
cinfdata=$(pwd)
cinfdata_setup=$(xmllint --xpath "string(//cinfdata_setup)" global_settings.xml)
if ! [ -d  $cinfdata_setup ]; then
    echo "Expected cinfdata_setup in $cinfdata_setup, but destination doesn't exist - aborting.."
    exit 13
fi

for dir in $cinfdata_setup/*/; do
    if [[ $dir = "$cinfdata_setup/figures/" ]]; then
	continue
    fi
    echo "$dir"
    cd $dir
    echo "Current working directory: "`pwd`

    for file in $dir/*; do
	# Delete all symlinks
	if [[ -L $file ]]; then
	    rm -f $file
	fi
    done
done
