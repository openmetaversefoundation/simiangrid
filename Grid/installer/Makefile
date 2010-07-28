#
# Jijawi
#
# LICENSE
#
# This source file is subject to the new BSD license that is bundled
# with this package in the file LICENSE.txt.
# If you did not receive a copy of the license and are unable to
# obtain it through the world-wide-web, please send an email
# to mail@dasprids.de so I can send you a copy immediately.
#
# @category   Jijawi
# @copyright  Copyright (c) 2009 Ben Scholzen "DASPRiD" (http://www.dasprids.de)
# @license    http://jijawi.org/license/new-bsd    New BSD License
# @version    $Id: Makefile 17 2009-07-18 18:13:20Z dasprid $
#

TARGETFILE = install.php

all: $(TARGETFILE)

$(TARGETFILE): data.tar.b64
	{ cat Jijawi/Loader.php ; \
	  awk '/-----BEGIN BOOTSTRAP-----/,/-----END BOOTSTRAP-----/' bootstrap.php | tail -n+2 | head -n-1 | \
	  sed -e '/\/\* -----BEGIN DATA----- \*\/.*\/\* -----END DATA----- \*\// {r data.tar.b64' -e 'd;}' ; \
	} > $@

data.tar.b64: data.tar
	{ echo -n "'" ; \
	  uuencode -m installer-data < data.tar | sed -e 's/begin-base64 644 installer-data//' ; \
	  echo -n "'" ; \
	} > $@

data.tar:
	tar -cf $@ * --exclude bootstrap.php --exclude config.xml.dist --exclude Jijawi/Loader.php \
	             --exclude Makefile --exclude data.tar --exclude data.tar.b64 --exclude $(TARGETFILE) \
	             --exclude .svn
	
clean:
	rm -f $(TARGETFILE) data.tar data.tar.b64

