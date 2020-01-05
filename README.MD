Jtrack
------

This is a simple project that can be used to store and show some basic telemetry data from IoT devices (on a map).
The point was to quickly put something together that would be simple/functional w/o lots of dependencies and "big" 
bundles.

- defines only basic User, Device and SimpleTelemetry entities.
- implements very basic user management (just Register and Login functionality, make sure you add ADMIN_ROLE to the 
  first registered user)
- implements simple "Projections" functionality for data management so the entities are not manipulated directly. 

uses: tailwindcss

*** work in progress ***

- coming: messages bus support, mercurial