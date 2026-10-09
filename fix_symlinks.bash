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


PREFIX=../cinfdata_setup
# Assume a hardcoded directory named "cinfdata_setup" in the subdirectory
if ! [ -d  $PREFIX ]; then
    echo "cinfdata_setup doesn't exist in the proper location"
    exit 13
else
    echo "existence of cinfdata_setup verified"
fi

cwd=`pwd`
for dir in ../cinfdata_setup/*/; do
    echo "$dir"
    cd $cwd && cd $dir
    echo "Current working directory: "`pwd`

    echo "Linking python files"
    ln -sfn ../../cinfdata/sym-files2/*.py .
    if [ $? -eq 0 ]; then
	echo -e "...OK\n\n"
    else
	echo "Something went wrong with the links. Exiting!"
	exit 12
    fi

    echo "Linking php files"
    ln -sfn ../../cinfdata/sym-files2/*.php .
    if [ $? -eq 0 ]; then
	echo -e "...OK\n\n"
    else
	echo "Something went wrong with the links. Exiting!"
	exit 12
    fi
done
