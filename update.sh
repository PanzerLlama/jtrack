#!/bin/bash

SOURCE="/media/data/code/php/jtrack/"
TARGET="lbuszczynski@35.246.172.69:/var/www/html/jtrack.dry-pol.pl/"
EXCLUDE="/media/data/code/php/jtrack/rsync-exclude-list.txt"

BACKUP_DIR="../backup_"$(date +%Y%m%d_%H%M)

RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m'

rsync -azvht --delete --backup --backup-dir=$BACKUP_DIR -e ssh $SOURCE $TARGET --exclude-from $EXCLUDE --dry-run

printf "${GREEN}"
printf "\n\nBETA SANDBOX server\n"
printf "${NC}"
echo "--------------------------"
printf "1) update server\n"
printf "2) update server and create backup in $BACKUP_DIR\n"
printf "\n\n"

read -p "Select option: " action

if [ $action == "1" ]; then
  echo "Updating...";
  rsync -azvht --delete -e ssh $SOURCE $TARGET --exclude-from $EXCLUDE
  echo 'Done!';
elif [ $action == "2" ]; then
  echo "Updating with backup...";
  rsync -azvht --delete --backup --backup-dir=$BACKUP_DIR -e ssh $SOURCE $TARGET --exclude-from $EXCLUDE
  echo 'Done!';
else
  printf "${RED}"
  echo "Unknown option.";
  printf "${NC}"
fi