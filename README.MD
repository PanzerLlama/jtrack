Jtrack
------

***Work in progress...***

This is a simple project based on Symfony 4.4 that can be used to store and show some basic telemetry data from IoT 
devices (on a map). The point was to quickly put something together that would be simple/functional w/o lots of 
dependencies and "big" bundles.

I needed it to store some data sent by Arduino based devices so the SimpleTelemetry is based on the data coming from
certain sensors. So that would be something one needs to adhere to own format (just create own Telemetry object 
implementing TelemetryInterface).

Data is sent from IoT devices just by simple GET requests.

- defines only basic User, Device and SimpleTelemetry entities.
- implements very basic user management (just Register and Login functionality, make sure you add ADMIN_ROLE to the 
  first registered user)
- implements basic "projections" functionality for data management so the entities are not manipulated directly. 

uses: tailwindcss, mercure, openlayers (maps)

# Setup & requirements

Besides getting required npm/composer packages:

Install mercure: https://github.com/dunglas/mercure/releases

Create a JWT token for mercure, you can do it here: https://jwt.io/#debugger-io?token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InB1Ymxpc2giOlsiKiJdfX0.iHLdpAEjX4BqCsHJEegxRmO-Y6sMxXwNATrQyRNt3GY
This JWT **TOKEN** should be stored in the MERCURE_JWT_TOKEN environment variable. Make note of the key used as it is 
required by mercure (see below).

Start mercure:
path_to_mercure/mercure --jwt-key='aVerySecretKey' --addr='localhost:3000' --allow-anonymous --cors-allowed-origins='*'



