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

python_webserver_command="python3 -m http.server 8381"
  
py_serv_pid=$(cat pid)
if [ -z "$py_serv_pid" ]; then :

   printf "${WHITE}No pid found, no server to stop. ${NC}\n"
else
    
    printf "${WHITE} Stopping web server pid: $py_serv_pid. ${NC}\n"
    #kill SIGTERM(15), SIGINT(2), SIGQUIT(3), SIGABRT(6) , SIGKILL(9) 

    killed=$(kill -15 $py_serv_pid)
 #   echo " [$killed] " 
#    echo "">pid
fi