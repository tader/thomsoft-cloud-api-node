DESTDIR =

TARGETDIR = $(DESTDIR)/usr/share/thomsoft-cloud/api
DOCPATH   = $(DESTDIR)/usr/share/doc/thomsoft-cloud-api-node

TOPFILES  = README.txt 

install-api:
	install -d -m 755 $(TARGETDIR)/
	install -m 644 src/node.php $(TARGETDIR)/

	install -d -m 755 $(DOCPATH)/
	install -m 644 $(TOPFILES) $(DOCPATH)/

