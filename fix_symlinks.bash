#!/bin/bash
# Script used to relink symlinks in chamber folders

# Prompt for a reply of whether to continue or not
prompt(){
    continue="k"
    while [ $continue != "y" ] && [ $continue != "n" ];do
	read -n 1 continue
	echo -en "\b"
    done
    if [ $continue == "y" ];then
	echo -e "yes\n"
    else
	echo -e "no\nEXITING. Aborted by user! \n"
	exit 2
    fi
}

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

    echo "Linking python files"
    ln -sfn $cinfdata/sym-files2/*.py .
    if [ $? -eq 0 ]; then
	echo -e "...OK\n\n"
    else
	echo "Something went wrong with the links. Exiting!"
	exit 12
    fi

    echo "Linking php files"
    ln -sfn $cinfdata/sym-files2/*.php .
    if [ $? -eq 0 ]; then
	echo -e "...OK\n\n"
    else
	echo "Something went wrong with the links. Exiting!"
	exit 12
    fi
done
