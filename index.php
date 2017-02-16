<title>Sands Of The Coliseum Edit</title>
<link rel="stylesheet" type="text/css" href="soc.css">

<div class="blok">
<div class="modal">
    <div class="content">
        <div class="h2">Sands Of The Coliseum Edit</div>
        <label> Player Token</label><input name='token' type="name" class='i1' style="width: 100%" autocomplete="on" placeholder="player token" />
        <center><button class="b1" onclick="get()">GET DATA</button></center>
        <div id='edit'>
        <!--div class="e1"><div style="width:100px">Money</div><div><input class="i2" /></div></div-->
        <div class='h1'>How do I know my token</div>
        <ol style='float:left;margin-right:10px;'>
            <li> Open your game, and make some progress
            <li> Right click -> Inspect element (Ctrl+Shift+I) 
            <li> Choose Network menu
            <li> Save your game
            <li> Look for request to <font style="font-family: monospace;">http://api.playerio.com/api/88</font>, click on it
            <li> In Request Headers you will see Playertoken
            <li> That is your token
        </ol>
        <img src='hehe.png' width='30%' style="min-width: 240px" />

        </div>
        <div class="footer" >
            Supported sites : kongregate.com, armorgames.com
        </div>
    </div>
</div>
</div>

<script type="text/javascript" src='soc.js'></script>
<script type="text/javascript">

var soc;
var token = document.getElementsByName('token')[0];
var edit = document.getElementById("edit");

document.getElementsByName('token')[0].addEventListener('keypress',
    function(e){
        if(!checkEnter(e)){
            get();
        }
    });

get=()=>{
    token.value=token.value.trim();
    if(token.value.length==0)return;
    if(token.value.length!=88){
        alert('WRONG TOKEN');
        return;
    }
    edit.innerHTML="<center><img src='3.gif' width='100px'></center>";
    soc = new SOC_EDIT("soc",token.value);
    soc.init();
}

</script>
