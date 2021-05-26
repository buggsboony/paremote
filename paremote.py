#!/usr/bin/env python3
import os
import os.path
import sys
from datetime import datetime
#import urllib.parse #urlencode  urllib.parse.quote("somethingTOencode")
import subprocess

#Retrieve IP adresse :
import socket
import netifaces  #pip install import netifaces
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


# get local ip adresse
def getLocalIPs():
    ips=[]
    interfaces=netifaces.interfaces()  #['lo', 'enp2s0f2', 'wlp3s0']
    for iface in interfaces :
        if iface.lower() != 'lo' :        
            print('Traitement de '+iface)
            addr=netifaces.ifaddresses(iface)
            if netifaces.AF_INET in addr:   
                #print('check afinet pour ', addr) 
                afinet=addr[netifaces.AF_INET]
                print('afinet:',afinet)
                if len(afinet) >= 0 :
                    netip=afinet[0]
                    if 'addr' in netip:
                        ip=netip['addr']                    
                        print( 'Found IP :\n'+ ip ,"\n" )
                        ips.append(ip)
            else:
                print('Rien d\'interessant pour '+iface)
        else:
            print('Avoid loopback interface')


    return ips


local_ips=getLocalIPs()
#print("local_ips=>",local_ips)
#exit()






# core program :
WS_PORT=8364
WS_HOST=local_ips[0] #"192.168.0.183" # Yet localhost, mais doit correspondre au WS dans le HTML

#'''
#os.system('echo "error: '+sys.exc_info()[0]+'">'+logfile)


if len(sys.argv) >=2 and  ( (sys.argv[1]=='-f') or (sys.argv[1]=='force')or (sys.argv[1]=='--remove') or (sys.argv[1]=='remove')  ) :    
    print(bcolors.WARNING + "Action : removing configfile'"+configfile+"'" + bcolors.ENDC)
    os.remove(configfile)

os.system('date>'+logfile)

os.system('echo starting web server >'+logfile)
print(bcolors.WHITE+' Starting webserver..')
os.system('./startserver.sh &')  #Start PHP web server and continue ...

os.system('echo Starting websocket python server>'+logfile)
print(bcolors.CYAN+'Starting WS(WebSocket) => '+bcolors.LCYAN+WS_HOST+":"+ str(WS_PORT) + bcolors.ENDC)



#''' Fonction d'analyse :
def analyse(full):
    response = ('analyse de ----'+full+'----')
    parts = full.split() #Sépare par whitespaces , meme le \n et \t sont reconnus
    com = parts[0]
    return response

#'''

async def receive(ws, path):
    try:
       async for message in ws:
            print("message reçu : ["+message+"]")
            response=analyse(message)
            await ws.send('WSServeur : ACK: '+response)
    except:
        print('Quelque chose a mal tourné !')
        print(sys.exc_info()[0])


asyncio.get_event_loop().run_until_complete(
    websockets.serve(receive, WS_HOST, WS_PORT))
asyncio.get_event_loop().run_forever() #N'aura pas besoin d'être fermé.














# ---------- ending -----------------------------

os.system('echo Terminé>>'+logfile)
print(bcolors.WHITE+ appname+ "=> Terminé.."+bcolors.ENDC)
exit(1)

