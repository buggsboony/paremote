#!/usr/bin/env python3
import os
import os.path
import sys
from datetime import datetime
#import urllib.parse #urlencode  urllib.parse.quote("somethingTOencode")
import subprocess

#--------- WS, websocket server stuff
import asyncio
import websockets
#---------------------



class bcolors:
    HEADER = '\033[95m'
    OKBLUE = '\033[94m'
    OKCYAN = '\033[96m'
    OKGREEN = '\033[92m'
    _GREEN = '\033[0;32m'
    _YELL='\033[0;33m'
    _RED ='\033[0;31m'
    WARNING = '\033[93m'
    FAIL = '\033[91m'
    CRED = '\033[91m'
    ENDC = '\033[0m'
    _DEF ='\e[39m'
    DEF ='\033[0m'
    BOLD = '\033[1m'
    UNDERLINE = '\033[4m'
    #récent
    GREEN='\033[0;32m' 
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
    
#print(bcolors.WARNING + "Warning: No active frommets remain. Continue?" + bcolors.ENDC)

appname='paremote'
homedir = os.path.expanduser("~")
configfile=  homedir + '/.config/'+appname+'/'+appname+'.json'
logfile= homedir+ '/.config/'+appname+'/debug.txt'




#os.system('echo "error: '+sys.exc_info()[0]+'">'+logfile)


if len(sys.argv) >=2 and  ( (sys.argv[1]=='-f') or (sys.argv[1]=='force')or (sys.argv[1]=='--remove') or (sys.argv[1]=='remove')  ) :    
    print(bcolors.WARNING + "Action : removing configfile'"+configfile+"'" + bcolors.ENDC)
    os.remove(configfile)

os.system('date>'+logfile)

os.system('echo starting web server >'+logfile)
print(bcolors.WHITE+' Starting webserver..')
os.system('./startserver.sh')

# core program :
WS_PORT=8365
os.system('echo Starting websocket python server>'+logfile)
print(bcolors.CYAN+'Starting websocket server on port '+ str(WS_PORT) + bcolors.ENDC)

async def receive(ws, path):
    try:
       async for message in ws:
            await ws.send('Serveur : je recois ceci: '+message)
            print('message reçu : "'+message+"'")
    except:
        print('Quelque chose a mal tourné !')
        print(sys.exc_info()[0])


asyncio.get_event_loop().run_until_complete(
    websockets.serve(receive, 'localhost', WS_PORT))
asyncio.get_event_loop().run_forever() #N'aura pas besoin d'être fermé.














# ---------- ending -----------------------------

os.system('echo Terminé>>'+logfile)
print(bcolors.WHITE+ appname+ "=> Terminé.."+bcolors.ENDC)
exit(1)

