# OXID Kategorie-Master

Für Details siehe https://shop.oxid-responsive.com/Module/OXID-Kategorie-Master.html.

Die Installationsdateien können unter https://github.com/marten-seemann/oxid-kategorie-master/releases heruntergeladen werden.

## Install

Upload the directory *kategorie-master* to the root directory of the shop (NOT the *modules* directory). Then run the *sql/install.sql* to install the required database entries.

## Build from source

Run

```
build/build.sh
```

This will create the directory *build/productive*. To install the module, upload this directory into the OXID root directory and rename it to *kategorie-master* (or whatever name you prefer).
Note that while this build script works fine, it's quite outdated, and could be solved much more elegantly using a proper asset pipeline.
