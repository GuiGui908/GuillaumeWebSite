var msg={def:{className:null,style:{},delay:0,modal:!1,modalColor:"#fff",modalOpacity:.7,title:null,context:null,button:"ok",positon:null,onOpen:null,onClose:null,ieMaxWidth:400,ajaxContent:null,ajaxAbortDelay:20,remember:!0,action:"close",faitesmoipaschieraveclesvirgules:null},closeMsgIn:null,msgContentRec:"Pas de message",msgOptionsRec:{position:!1},presets:{},modalId:"modalObj",ieVer:function(){if("Microsoft Internet Explorer"==navigator.appName){var e=/.*MSIE ([^;]+);.*/i;msg.ie=parseFloat(navigator.appVersion.replace(e,"$1"))}else msg.ie=null},mousePos:function(e){msg.ie?msg.ie<8?(msg.mouseX=e.x+document.documentElement.scrollLeft,msg.mouseY=e.y+document.documentElement.scrollTop):(msg.mouseX=e.x+document.body.scrollLeft,msg.mouseY=e.y+document.body.scrollTop):(msg.mouseX=e.pageX,msg.mouseY=e.pageY)},getCoords:function(){this.coords={},this.ie?this.ie>=6?(this.coords.posX=document.documentElement.scrollLeft,this.coords.posY=document.documentElement.scrollTop,this.coords.visibleW=document.documentElement.clientWidth,this.coords.visibleH=document.documentElement.clientHeight):(this.coords.posX=document.body.scrollLeft,this.coords.posY=document.body.scrollTop,this.coords.visibleW=document.body.clientWidth,this.coords.visibleH=document.body.clientHeight):(this.coords.posX=window.pageXOffset,this.coords.posY=window.pageYOffset,this.coords.visibleW=window.innerWidth,this.coords.visibleH=window.innerHeight)},setPosition:function(e,t){var o=document.getElementById("msgBox");if(o){t||setTimeout(function(){msg.setPosition(e,!0)},100),this.getCoords();var s=e?e:":";s=s.split(/:/),"undefined"==typeof s[1]&&(s[1]="");var n,i=s[0],l=s[1],a=/^-?[0-9]+(px)?$/i;if("mouse"===i){var m=msg.mouseX+o.offsetWidth+10;o.style.left=(m>this.coords.visibleW?msg.mouseX-(m-this.coords.visibleW)-10:msg.mouseX+10)+"px"}else a.test(i)?(n=/-/.test(i),i=parseInt(i,10),n?o.style.right=Math.abs(i)-this.coords.posX+"px":o.style.left=this.coords.posX+i+"px"):o.style.left=(this.coords.visibleW-o.offsetWidth)/2+this.coords.posX+"px";if("mouse"===l){var d=msg.mouseY-(o.offsetHeight+5);o.style.top=(d<this.coords.posY?msg.mouseY+10:d)+"px"}else a.test(l)?(n=/-/.test(l),l=parseInt(l,10),n?o.style.bottom=Math.abs(l)-this.coords.posY+"px":o.style.top=this.coords.posY+l+"px"):o.style.top=(this.coords.visibleH-o.offsetHeight)/2+this.coords.posY+"px"}},autoSetPosition:function(){window.attachEvent?(window.attachEvent("onresize",function(){msg.setPosition(msg.msgOptionsRec.position)}),window.attachEvent("onscroll",function(){msg.setPosition(msg.msgOptionsRec.position)})):(window.addEventListener("resize",function(){msg.setPosition(msg.msgOptionsRec.position)},!1),window.addEventListener("scroll",function(){msg.setPosition(msg.msgOptionsRec.position)},!1))},transfer:function(e,t){if("object"==typeof e&&"object"==typeof t){var o=null;for(o in e)t[o]=e[o]}},checkOptions:function(e){return e||(e={}),e.modalOpacity||0===e.modalOpacity||delete e.modalOpacity,e.modalOpacity&&(e.modalOpacity<0?e.modalOpacity=0:e.modalOpacity>1&&(e.modalOpacity=1)),e},checkContent:function(e){return e&&(e.innerHTML?e=e.innerHTML:e+=""),e},preset:function(e,t,o){t=this.checkContent(t),o=this.checkOptions(o),this.presets[e]={msgContent:t,settings:o}},reload:function(e,t,o){if(this.presets[e]){var s=t?t:this.presets[e].msgContent,n={};this.transfer(this.presets[e].settings,n),this.transfer(o,n),this.open(s,n)}else this.open('param&eacute;trage "'+e+'" inconnu.',{button:"ok"})},replaceAlert:function(){document.getElementById&&(window.alert=function(e,t){msg.reload("alert",e,t)})},uploadfiles:function(){var e=document.getElementById("upInput").files;if(0==e.length)return!1;for(var t=0,o=0;o<e.length;o++){if(e[o].size>8388608)return alert("Vous ne pouvez pas importer de fichier de plus de 8Mo !!\n"+e[o].name+" fait "+(e[o].size/1048576).toFixed(2)+" Mo"),!1;t+=e[o].size}return t>8388608?(alert("Vous ne pouvez pas uploader plus de 8Mo d'un coup.\nLa somme des tailles des fichiers est trop grande ("+(t/1048576).toFixed(2)+" Mo"),!1):(t/=1048576,t+=document.getElementById("totalSize").innerHTML,t>999?(alert("Les fichiers que vous avez sélectionné sont trop gros.\nLa capacité maximale de stockage est d' 1Go, et "+document.getElementById("totalSize").innerHTML+" Mo sont déjà utilisés"),!1):(document.getElementById("FormUp").submit(),void msg.close()))},creerep:function(){var e=document.getElementById("dirNewFolder");""==e.value?document.getElementById("aBtnSubmit").href="#":document.getElementById("aBtnSubmit").href+=e.value,msg.close()},close:function(){(document.getElementById(this.modalId)||document.getElementById("msgBox"))&&(document.getElementById(this.modalId)&&document.getElementsByTagName("body")[0].removeChild(document.getElementById(this.modalId)),document.getElementsByTagName("body")[0].removeChild(document.getElementById("msgBox")),clearTimeout(this.closeMsgIn),"function"==typeof this.msgOptionsRec.onClose&&this.msgOptionsRec.onClose())},ajaxUpdate:function(e,t){var o=null;if(window.XMLHttpRequest)o=new XMLHttpRequest;else if(window.ActiveXObject)try{o=new ActiveXObject("Msxml2.XMLHTTP")}catch(s){o=new ActiveXObject("Microsoft.XMLHTTP")}else o=!1,alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");var n;o.onreadystatechange=function(){4==o.readyState&&(clearTimeout(n),document.getElementById("msgContent").innerHTML=o.responseText,msg.setPosition())},o.open("GET",e,!0),n=setTimeout(function(){var e="readyState = "+o.readyState+"<br >status = "+o.status+"<br >statusText = "+o.statusText+"<br >";msg.open(e,{title:"abandon de la requ&ecirc;te ajax",delay:0}),o.abort()},1e3*t.ajaxAbortDelay),o.send(null)},build:function(e,t){if(t.modal){var o=document.getElementsByTagName("body")[0].appendChild(document.createElement("div"));o.id=this.modalId,o.style.height=this.ie>0&&this.ie<6?document.body.scrollHeight+"px":document.documentElement.scrollHeight+"px",o.style.backgroundColor=t.modalColor,this.ie?o.style.filter="alpha(opacity="+parseInt(100*t.modalOpacity,10)+")":o.style.opacity=t.modalOpacity}var s=document.getElementsByTagName("body")[0].appendChild(document.createElement("div"));if(s.id="msgBox",s.className="msgBox",t.className&&(s.className+=" "+t.className),t.context){var n=/(\$msg\$)/g;e=t.context.replace(n,e)}var i='<div id="msgContent">'+e+"</div>",l="";t.title&&(l='<div id="msgTitle">'+t.title+"</div>");var a="";(t.button||0===t.button)&&(a='<div id="closeBtn"><a id="aBtnSubmit" href="'+t.link+'" onclick="msg.'+t.action+'();">'+t.btnOk+"</a>");var m="";(t.button||0===t.button)&&(m='<a href="#" onclick="msg.close();return false;">'+t.btnNop+"</a></div>"),s.innerHTML=l+"\n"+i+"\n"+a+m,this.transfer(t.style,s.style),this.ie&&this.ie<7&&s.offsetWidth>t.ieMaxWidth&&(s.style.width=t.ieMaxWidth+"px"),"creerep"===t.action&&(document.getElementById("dirNewFolder").focus(),document.getElementById("dirNewFolder").addEventListener("keypress",function(e){13==e.keyCode&&document.getElementById("aBtnSubmit").click(),27==e.keyCode&&msg.close()})),this.setPosition(t.position),t.ajaxContent&&this.ajaxUpdate(t.ajaxContent,t)},open:function(e,t){e=this.checkContent(e),t=this.checkOptions(t),document.getElementById("msgBox")&&msg.close();var o={};this.transfer(this.def,o),this.transfer(t,o),e?o.remember&&(this.msgContentRec=e):e=this.msgContentRec,e?o.remember&&(this.msgOptionsRec=o):(this.transfer(this.msgOptionsRec,o),o.modal=!1,o.delay=0,o.button="ok",o.remember=!1),this.build(e,o);var s=o.delay;clearTimeout(this.closeMsgIn),s=1e3*s,s>0&&(this.closeMsgIn=setTimeout(function(){msg.close()},s)),"function"==typeof o.onOpen&&o.onOpen()},init:function(){this.ieVer(),msg.autoSetPosition(),window.attachEvent?document.attachEvent("onmousemove",msg.mousePos):window.addEventListener("mousemove",msg.mousePos,!1)},faitesmoipaschieraveclesvirgules:null};msg.init(),msg.preset("alert","!",{modal:!0,title:"attention !",button:"ok",context:'<img style="float:left;padding:0 .5em 0 0" src="Ressources/images/alert.gif" />$msg$'}),msg.preset("loading","veuillez patienter...",{modal:!0,button:null,title:"chargement en cours",context:'<span style="padding-left:20px;background:transparent url(Ressources/images/loader.gif) no-repeat left center;">$msg$</span>'}),msg.preset("tip",null,{delay:0,button:null,position:"mouse:mouse",remember:!1});