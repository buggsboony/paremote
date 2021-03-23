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
    </style>    
<body>

<?php

    $ip = $_SERVER["HTTP_HOST"];
    $ip = $_SERVER["REMOTE_ADDR"]; //histoire d'etre sur d'obtenir une vraie adresse.
 

echo "hostname =>"; var_dump( $ip);
?>
    
    <div>Pulse Audio Remote Control</div>

    <button onclick="connect();">CONNECT</button>
    <button onclick="hello();">Say hello</button>
    <button onclick="closesock();">CLOSE</button>

    <div id="chatLog">
        Chat logs
    </div>


    <script>

var socket;
var WS_PORT = 8365;
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
            if(ws === null ) alert("Non connecté");
            if(str == undefined) str ="hello server";
            ws.send(str);
        }


        function sendCommand(str,command)
        {
            if(ws === null ) alert("Non connecté");
            if(str == undefined) str ="com "+command;
            ws.send(str);
        }


        function message(msg) {
            var log = document.getElementById('chatLog');
            log.innerHTML+=(msg + '</p>');
        }


        connect();
    </script>
</body>
</html>