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


PREFIX=../cinfdata_setup
# Assume a hardcoded directory named "cinfdata_setup" in the subdirectory
if ! [ -d  $PREFIX ]; then
    echo "cinfdata_setup doesn't exist in the proper location"
    exit 13
else
    echo "existence of cinfdata_setup verified"
fi

if [ $# -ne 1 ]; then
    echo "This script needs exactly one argument, the folder name for the new chamber, to proceed"
    exit 30
fi

echo "Make new folder named: $1"
echo "Ok to preceed (y/n)?"
mkdir $PREFIX/$1
prompt

cd $PREFIX/$1
echo "Current folder is: "`pwd`
echo "Ready to make links (y/n)?"
prompt

echo "Linking python files"
ln -s ../../cinfdata/sym-files2/*.py .
if [ $? -eq 0 ]; then
    echo -e "...OK\n\n"
else
    echo "Something went wrong with the links. Exiting!"
    exit 12
fi

echo "Linking php files"
ln -s ../../cinfdata/sym-files2/*.php .
if [ $? -eq 0 ]; then
    echo -e "...OK\n\n"
else
    echo "Something went wrong with the links. Exiting!"
    exit 12
fi
