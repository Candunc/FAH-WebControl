# FAHWebControl

Folding@Home Webcontrol is a web-based version of the FAHControl client, provided with [Folding@Home software](http://folding.stanford.edu/home/). It uses a PHP backend to communicate with the FAHClient running on various folding machines, and a javascript frontend to display various statistics about the machines. As this project is currently a work in progress, I am not able to provide help, nor will I be able to provide sufficient install instructions. If you do wish to help, feel free to submit an issue and/or a pull request.

*Included Files:*

* wrapper.lua: A wrapper that connects to FAHClient via tcp, grabs the info, and returns it in json format. 

## Licence 

This software is licenced to the user under GNU GPL v2.0.

However, the software uses portions from the library [POLLib](https://github.com/farzadghanei/POLlib), which is licenced under the Apache Licence v2. For more information, head to the linked repository and look at the files named [LICENCE](https://github.com/farzadghanei/POLlib/blob/master/LICENSE), [NOTICE](https://github.com/farzadghanei/POLlib/blob/master/NOTICE), AND [COPYING](https://github.com/farzadghanei/POLlib/blob/master/COPYING).

**Portions of this program are licenced from a third party library:**

_PHP Telnet Module:_
Copyright (c) 2010-2013 ParsOnline, Inc. (www.parsonline.com)

