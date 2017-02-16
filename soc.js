var sites={
	 "kong" :"Kongregate"
	,"armor" :"ArmorGames"
};

function SOC_EDIT(name,token){
	this.name=name;
	this.token=token;
	this.arr;
	this.div=document.createElement('div');

	this.init=()=>{
		SOC_EDIT.get_data(this);
	}

	this.kirim=()=>{
		var edit2=document.getElementById('edit2');
	    edit2.innerHTML="<center><img src='3.gif' width='50px'><br/>SENDING</center>";

		SOC_EDIT.send_data(this.arr);
	}
}
var SOC_EDIT;

SOC_EDIT.get_data=(a)=>{
	var request;
	if(window.XMLHttpRequest){
		request=new XMLHttpRequest();
	}else{
		request=new ActiveXObject("Microsoft.XMLHTTP");
	}
	request.onreadystatechange=function(){
		if(request.readyState==4){
			t=request.responseText;
			//SOC_EDIT.response_data(request.responseText,this);
			SOC_EDIT.tampilawal(a,t);
			
		}
	}

	request.open("GET","soc_edit_1.0.php?g&t="+a.token);
	//request.open("GET","dumy.php");
	//request.setRequestHeader('Content-Type',"application/x-www-form-urlencoded");
	request.send();
}

SOC_EDIT.send_data=(a)=>{
	var request;
	if(window.XMLHttpRequest){
		request=new XMLHttpRequest();
	}else{
		request=new ActiveXObject("Microsoft.XMLHTTP");
	}
	request.onreadystatechange=function(){
		if(request.readyState==4){
			t=request.responseText;
			document.getElementById('edit2').innerHTML=t;
			
		}
	}

	request.open("POST","soc_edit_1.0.php");
	//request.open("GET","dumy.php");
	request.setRequestHeader('Content-Type',"application/x-www-form-urlencoded");
	//request.send("s="+JSON.stringify(a));
	request.send("s="+a['data']+"&t="+a['token']);
}

SOC_EDIT.tampilawal=(a,t)=>{
	var edit = document.getElementById('edit');
	try{
		a.arr=JSON.parse(t);
	}
	catch(e){
		edit.innerHTML="<div class='h1'>An Error Occurred </div> <sub>can not parse response data</sub><br/> It may caused by network connection, or wrong token. Please try again.";
		return;
	}
	a.div.innerHTML=a.arr['data'];
	school=a.div.getElementsByTagName('School')[0];
	if(school==undefined){
		edit.innerHTML="<div class='h1'>An Error Occurred</div> <sub>can not find School tag</sub><br/> It may caused by network connection, or wrong token. Please try again.";
		return;
	}

	edit.innerHTML='';
	
	var _isi='';
	_isi+="<div class='h1'>"+soc.arr.name+" on "+ sites[soc.arr.site] +" <font style='font-size:50%;font-weight:normal'> "+(new Date())+" </font></div>";
	_isi+='<div class="footer" style="background:#f88;margin:2px;font-size:13px" >Be careful. Some values are in integer with range -2,147,483,648 to 2,147,483,647. If you fill it with value that greater than max integer value, it will be negative.</div>';
	_isi+="<textarea class='ta1' style='' onchange='"+soc.name+".arr[\"data\"]=this.value'>"+soc.arr['data'].split`>`.join`>\n` +"</textarea>";
	_isi+='<div class="box2" id="edit2"></div>';
	_isi+='<center><button class="b1" onclick="'+soc.name+'.kirim()">SUBMIT</button></center>';
	edit.innerHTML+=_isi;


}

var all_skill={
	 SKD0 :"shield bash"
	,SKC2 :"boost morale"
	,SKW0 :"power attack"
	,SKR0 :"throw"
	,SKA0 :"combo 2x"
	,SKC0 :"intimidation"
	,SKW2 :"armor break"
	,SKR5 :"cure"
	,SKC5 :"morale all"
	,SKC6 :"intimidation all"
	,SKD3 :"protect"
	,SKW4 :"berzerk"
	,SKA4 :"combo 4x"
	,SKC7 :"cure all"
	,SKR6 :"net"

	,SKR1 :"life boost"
	,SKR2 :"bleed reduce"
	,SKR4 :"dodge plus"
	,SKW1 :"dexterity boost"
	,SKW3 :"strike of the will"
	,SKW5 :"critical boost"
	,SKA3 :"blood rage"
	,SKA2 :"strength boost"
	,SKA1 :"speed bost"
	,SKD4 :"regeneration"
	,SKD2 :"counter attack boost"
	,SKD1 :"defense boost"
	,SKC1 :"charisma plus"
	,SKC4 :"crowd appeal boost"
}

proper=a=>{
	a=a.split``
	a=a.map(b=>b.charCodeAt()<97?" "+b:b);
	return a.join``;
}

SOC_EDIT.response_data=(t,y)=>{
	if(JSON.parse(t))y.arr=JSON.parse(t);
	eval(y.name+".tampilawal()");
	//y.div=document.createElement('div');
	//y.div.innerHTML=y.arr;

	//var div=document.createElement('div');
	//div.innerHTML=y.arr;

	//document.write(div.innerHTML);

	//for(i of div.getElementsByTagName('slave')){
		//document.write(i.outerHTML+"<BR><BR><BR>");
	//}
}


//if(document.querySelector('form')) document.querySelector('form').onkeypress = checkEnter;
function checkEnter(e){
	e = e || event;
	var txtArea = /textarea/i.test((e.target || e.srcElement).tagName);
	return txtArea || (e.keyCode || e.which || e.charCode || 0) !== 13;
}

