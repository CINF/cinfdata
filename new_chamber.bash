#!/bin/bash
# Script used to make a new chamber folder

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

if [ $# -ne 1 ]; then
    echo "This script needs exactly one argument, the folder name for the new chamber, to proceed"
    exit 30
fi

echo "Make new folder named: $1"
echo "Ok to preceed (y/n)?"
mkdir $cinfdata_setup/$1
prompt

cd $cinfdata_setup/$1
echo "Current folder is: "`pwd`
echo "Ready to make links (y/n)?"
prompt

echo "Linking python files"
ln -s $cinfdata/sym-files2/*.py .
if [ $? -eq 0 ]; then
    echo -e "...OK\n\n"
else
    echo "Something went wrong with the links. Exiting!"
    exit 12
fi

echo "Linking php files"
ln -s $cinfdata/sym-files2/*.php .
if [ $? -eq 0 ]; then
    echo -e "...OK\n\n"
else
    echo "Something went wrong with the links. Exiting!"
    exit 12
fi
