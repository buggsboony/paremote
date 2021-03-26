<html>
    <head>        
        <title>Pulse Audio Remote Control</title>
    </head>
    
      
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, user-scalable=no">
  
    <title>Pulse Audio Remote by βoon</title>
    <style>
    
    .invisible
    { display:none;
    }
    
    #toast
    {
        display:none;
        position: absolute;
        background: rgba(21, 35, 174, 0.63);
        border: solid 1px #3A3535;
        width: 227px;
        height: 20px;
        padding: 8px;
        border-radius: 4px;
        color: rgb(240, 240, 241);
        top: 0;
        left: 148px;
        font-weight:bold;
    }
    
    #logs
    {
      width:100%;
      overflow:scroll;
    }
    

    #volume_btns button
    {
        width:90%;
        height:100px;
        padding:10px;
        margin-left:5%;
        margin-right:5%;
        margin-bottom:10px;
        font-size: 60px;
        color: #32FFDE
        ;
        background-color:   #560700;
    }


    #record_btns button
    {
        width:25%;
        height:60px;
        padding:10px;
     
        margin-bottom:10px;
        font-size: 20px;
        color: #32FFDE
        ;
        background-color:   #560700;
    }

    body{
        background-color:#3A3535;
    color:#A9AEAB;
    }

    textarea{
        width:90%;
        color:#EFEFC4;
        background-color: #0E0D19;

        height:140px;
        padding:10px;
        padding-top:4px;
        padding-bottom:0px; /* Pour éviter le scroll vertical */
        margin-left:5%;
        margin-right:5%;
        margin-bottom:10px;
    }
    .numeric_field
    {
        width:20px;
        padding:2px;
        border: solid 1px #777;
        background-color:#A9AEAB;
        color:bisque;
    }

    .text_field
    {
       height:40px;
    }
    </style>    
<body>

<?php

    $ip = $_SERVER["HTTP_HOST"];
    $ip = $_SERVER["SERVER_NAME"]; //histoire d'etre sur d'obtenir une vraie adresse.
 
    //echo "<pre>SERVER Infos: \n"; var_dump( $_SERVER ); echo "</pre>";
    //echo "hostname =>"; var_dump( $ip);
?>
    
    <h4>Pulse Audio Remote Control by Boon</h4>

    

<textarea>
[boony@atuf ~]$ pactl set-sink-volume @DEFAULT_SINK@ +10%
[boony@atuf ~]$ pactl set-sink-volume @DEFAULT_SINK@ -10%
Volume de l'entrée (Source , Capture)
[boony@atuf ~]$ pactl set-source-volume @DEFAULT_SOURCE@ -10%    
[boony@atuf ~]$ pactl set-source-volume @DEFAULT_SOURCE@ +10%

Enregistrer du son via pacmd pulseaudio tool

Lister le microphones :
pacmd list-sources | grep -i alsa_input

[boony@atuf ~]$ pacmd list-sources | grep -i alsa_output -A 2
        name: <alsa_output.pci-0000_00_1f.3.analog-stereo.monitor>
        driver: <module-alsa-card.c>
        flags: DECIBEL_VOLUME LATENCY DYNAMIC_LATENCY


        Enregistrer le son sortant avec FFMPEG  … press Q to stop.
ffmpeg -f pulse -i default output.wav
2021-02-27 12:12:21 - Tenter d'arreter ffmpeg recording :
SIGTERM(15), SIGINT(2), SIGQUIT(3), SIGABRT(6) , SIGKILL(9)
2021-02-27 12:17:41 - et ça fonctionne : 

ubuuny@cuube:~$ ps -ef | grep -i ffmpeg | grep -v grep
ubuuny    131165    7377  1 12:14 pts/0    00:00:00 ffmpeg -f pulse -i default record01.wav
ubuuny@cuube:~$ kill -3 131165



</textarea>


    <label>Load playback command :</label>
    <textarea id="playback_command" class="text_field">pactl load-module module-loopback latency_msec=1 sink=0</textarea>

    <label>Record command  :</label>
    <textarea id="record_command" class="text_field">ffmpeg -f pulse -i default output_{{timestamp}}.wav</textarea>



    <button onclick="connect();">CONNECT</button>
    <button onclick="hello();">Say hello</button>
    <button onclick="closesock();">CLOSE</button>

    <div id="chatLog">
        Chat logs
    </div>



    <div id="record_btns">    
    <button onclick="playback();" style="" >playback</button>
    
    
    <button onclick="startRecording();" style="" >Rec</button>
    <button onclick="stopRecording();" style="" >Stop</button>
    </div>

<hr/>

<div id="volume_btns">
    <button onclick="volume(+volume_step);">Vol+</button>
    <button onclick="volume(-volume_step);">Vol-</button>
</div>



    <script>
try{
       
var volume_step=10;



var socket;
var WS_PORT = 8364;
var WS_IP = "<?php echo $ip;?>";
      
        var ws=null;
        function connect()
        {
            try{
                supportsWebSockets = 'WebSocket' in window || 'MozWebSocket' in window;
                if(!supportsWebSockets)
                {
                    throw "Websocket not supported !";
                }else 
                {
                    //alert("websocket is supported");
                }
                var wsurl='ws://'+WS_IP+':'+WS_PORT;
                message(wsurl);
                         ws = new WebSocket(wsurl); 
                        ws.onmessage = function(event)
                        {message("message received"); 
                        message("DATA : "+event.data);  // ws.close();
                        } 

                        ws.onopen = function(e){ message("open"); console.warn(e,"onoppen(e)");} 
                        ws.onclose = function(e){message("close");}
                        ws.onerror = function(e){message("error");}
                    }catch(eee){ alert(eee.message)}
    
        }//connect

        function closesock()
        {
            ws.close();
        }

        function hello(str)
        {           
            if(ws === null ){ alert("Non connecté"); return ;}
            if(str == undefined) str ="hello server";
            ws.send(str);
        }


        function sendCommand(command)
        {
            var str ="EXEC:\n"+command;
            if(ws === null ){ alert("Non connecté"); return ;}          
            ws.send(str);
        }


        function message(msg) {
            var log = document.getElementById('chatLog');
            log.innerHTML+=(msg + '</p>');
        }


        function playback()
        {
            var el=document.getElementById("playback_command");
            sendCommand(el.value);
        }
        function startRecording()
        {
            var el=document.getElementById("record_command");
            sendCommand(el.value);
        }
        function stopRecording()
        {           
            sendCommand("STOP_REC");
        }

                
        connect();
    }catch(ge)
    {
        alert("Aouch ! "+ge.message);
    }
    </script>
</body>
</html>