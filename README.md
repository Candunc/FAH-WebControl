# FAHWebControl

Folding@Home Webcontrol is a web-based version of the FAHControl client, provided with [Folding@Home software](http://folding.stanford.edu/home/). It uses a PHP backend to communicate with the FAHClient running on various folding machines, and a javascript frontend to display various statistics about the machines. As this project is currently a work in progress, I am not able to provide help, nor will I be able to provide sufficient install instructions. If you do wish to help, feel free to submit an issue and/or a pull request.

*Included Files:*

* wrapper.lua: A wrapper that connects to FAHClient via tcp, grabs the info, and returns it in json format. 

## Installation

* Ensure the system has some sort of web stack with PHP (Apache or Nginx is okay)
* Install lua (The one in your distribution should be okay) and luarocks (From source works best)
* Install Luasocket (On Ubuntu you may get an error that is could not be installed, install unzip)
* Run the command ```'luac --out /opt/wrapper.lua wrapper.lua``` (This should speed up the backend by ~0.100 ms)
* move html/ to your web root

And that _should_ be it. I'll add better instructions once the program is more mature.

## Licence 

This software is licenced to the user under GNU GPL v2.0.

