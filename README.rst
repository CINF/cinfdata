::

    _____  _           ___     _       _
   /  __ \|_|         / _/    | |    _| |_
   | /  \/ _ _ ___  _/ /_  __ | | __|_   _|__ _
   | |    | | ' _ \|_   _|/ _`  |/ _` | |/ _` |
   | \__/\| |  | | | | | | (_|  | (_| | | (_| |
   \_____/|_|__/ \_| | |  \__,__|\__,_|\_\__,_|
                    _/ /
                   /__/ 

Cinfdata Web Presentation
=========================

About Cinfdata
--------------

This project sets up a web-based interface to parse and present SQL data from a server.
It is used in conjunction with `PyExpLabSys<https://github.com/CINF/PyExpLabSys>`_ and has certain
assumptions for the structure of the SQL database for generic views.

This repository (cinfdata) contains the shared code for presenting the data.
Site-specific settings and subpages (setups/chambers/groupings) are located in a separate git archive.
The location is specified in <cinfdata>/global_settings.xml under the tag <cinfdata_setup>. NB: it
should not contain a trailing "/".

The bash script <cinfdata>/new_chamber.bash takes an argument <setup_name> and creates a folder in
<cinfdata_setup>/<setup_name> with symbolic links to the shared codespace.
If setup folders are copied from somewhere else or otherwise contains broken links, everything in
<cinfdata_setup> can be relinked by running the bash script <cinfdata>/fix_symlinks.bash.
Both bash scripts should be run from the <cinfdata> directory.

If <cinfdata> is the DocumentRoot, an alias can be created in VirtualHost to link to <cinfdata_setup>.

Installation
------------
