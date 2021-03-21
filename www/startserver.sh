#!/bin/bash
python_webserver_command="python3 -m http.server 8381"
echo "Start web server in '$(pwd)'"
#$python_webserver_command &
#write process id to file

echo "COMMAND:[$python_webserver_command]"
#$(ps -ef | grep \"$python_webserver_command\" -m1 | awk{'print\$2'})
grep_results=$(ps -ef | grep -i "$python_webserver_command" -m1)
    
$("$grep_results") | awk {'print'}

    
echo "Server PID is $py_serv_pid"
echo $py_serv_pid>pid