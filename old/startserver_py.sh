#!/bin/bash
GREEN='\033[0;32m'   #printf "${LRED}File not found :'${1}'${NC}\n"
LGREEN='\033[1;32m'
WHITE='\033[1;37m'
YELL='\033[1;33m'
RED='\033[0;31m'
LRED='\033[1;31m'
MAG='\033[0;35m'
LMAG='\033[1;35m'
CYAN='\033[0;36m'
LCYAN='\033[1;36m'
NC='\033[0m' # No Color



python_webserver_command="python3 -m http.server 8381 -d www"
./stopserver.sh "$python_webserver_command"
#python_webserver_command="python"
printf "${YELL}Start web server in '$(pwd)'${NC}\n"
$python_webserver_command &
#wait for it to start
sleep 2
#write process id to file

printf "${WHITE}Server Command : [$python_webserver_command]${NC}\n"
#$(ps -ef | grep \"$python_webserver_command\" -m1 | awk{'print\$2'})
#Afficher tous les PIDs qui répondent à la recherche
#ps -ef | grep -i "$python_webserver_command"   |  awk ' {  print " "$2"  " }  '

#Récupérer le PID (le premier qui répond à la recherche)
py_serv_pid=$(ps -ef | grep -i "$python_webserver_command"  -m1 |  awk ' {  print ""$2"" }  ')
    
#$("$grep_results") | awk {'print'}

    
printf "${YELL}Web Server PID is '$py_serv_pid'${NC}\n"
echo $py_serv_pid>pid