#!/bin/bash

screen -S queues -t mercure -dm bash -c "cd /home/lbuszczynski; mercure --jwt-key='71692F657282C11C3B1683268D276' --addr=':3000' --allow-anonymous --cors-allowed-origins='*'"
screen -S queues -x -X screen -t publisher bash -c "cd /var/www/html/jtrack.dry-pol.pl/; bin/console mercure:publish --frequency=30"
screen -S queues -x -X screen -t emulator bash -c "cd /var/www/html/jtrack.dry-pol.pl/; php bin/console emulator --frequency=60"
