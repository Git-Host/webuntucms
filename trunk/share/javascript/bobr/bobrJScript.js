/**
 * @author Rbas
 */
var ajax = {
	
	// vytvori objekt
	XMLHttpRequest : function()
	{
		this.ajaxRequest = false; 
	    
		if (window.XMLHttpRequest) {
			this.ajaxRequest = new XMLHttpRequest();
		} else if (window.ActiveXObject) {
			this.ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
		}
	},
	
	action : function(url, id, data)
	{
		
		this.XMLHttpRequest();
		if(!this.ajaxRequest) return false;
		if(data){
			data = '?q=' + data; 
		} else {
			data = false;
		}
		try {
			this.updateHtml(id, '<img src="/share/other/spinner.gif" />');
		}catch(e){
			alert(e);
		}
		
		try {
			this.ajaxRequest.open("post", url, true);
			this.ajaxRequest.setRequestHeader("X-Requested-With", "XMLHttpRequest");
			this.ajaxRequest.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			
			this.ajaxRequest.onreadystatechange = function( ) { ajax.callBack( id ) }
			this.ajaxRequest.send( data );
			
			return true;

		} catch (e) {
			alert(e);
			return false;
		}
	},
	
	callBack : function ( id )
	{
		if (this.ajaxRequest.readyState == 4 && this.ajaxRequest.status == 200) { 
		  this.updateHtml(id, this.ajaxRequest.responseText ) ;
        }
	},
	
	updateHtml: function(id, html)
	{
		var element = document.getElementById(id);
		if (element) element.innerHTML = html;
	},

}

var dragNDrop = {
	poziceX : 0,
	poziceY : 0,
	
	mouseAction : function ( e )
	{

		if(e){ e = e; }
			else{ e = window.event; }
				
		if(e.pageX){ x = e.pageX; }
			else{ x = clientX; }
				
		if (e.pageY){ y = e.pageY; }
			else { y = e.clientY; }
				
		if(e.target){ cil = e.target; }
			else{ cil = e.srcElement; }
	},
	
	processMouseDown : function ( e )
	{
		try {
			this.e = dragNDrop.mouseAction(e);
				
			this.addCallback("mousemove",this.processMouseMove);
			this.addCallback("mouseup",this.processMouseUp);
			this.poziceX = this.e.x - parseInt(this.e.cil.style.left);
			this.poziceY = this.e.y - parseInt(this.e.cil.style.top);

		}catch (f){
			alert( dragNDrop.mouseAction(e) );
			return false;
		}
		/*
		
		this.e = dragNDrop.mouseAction(e);
				
		this.addCallback("mousemove",this.processMouseMove);
		this.addCallback("mouseup",this.processMouseUp);
		
		this.poziceX = this.e.x - parseInt(this.e.cil.style.left);
		this.poziceY = this.e.y - parseInt(this.e.cil.style.top);
			*/	
	},
	
	addCallback : function ( type, callback )
	{
		if (document.addEventListener){
			document.addEventListener(type, callback, false);
		}else if (document.attachEvent){
			document.attachEvent("on"+type, callback, false);
		}
	},
	
	clearCallback : function ( type, callback )
	{
		if (document.removeEventListener){
			document.removeEventListener(type, callback, false);
		}else if(document.detachEvent){
			document.detachEvent("on" + type, callback, false);
		}
	},
	
	processMouseMove : function (e) 
	{
		var e = dragNDrop.mouseAction(e);
		var x = e.x - poziceX;
		e.cil.style.left = x + "px";
		var y = e.y - poziceY;
		e.cil.style.top = y + "px";
	},

	processMouseUp : function (e)
	{
		var e = dragNDrop.mouseAction(e);
		
		this.clearCallback("mousemove", this.processMouseMove);
		this.clearCallback("mouseup", this.processMouseUp);
		/*
		var kosik = document.getElementById("kosik");
		var x = parseInt(kosik.style.left);
		var y = parseInt(kosik.style.top);
		var sirka = parseInt(kosik.style.width);
		var vyska = parseInt(kosik.style.height);
		*/
	},
}

var poziceX;
			var poziceY;
			
			function UdalostMysi(e){
				if(e){
					this.e = e;
				}else{
					this.e = window.event;
				}
				
				if(e.pageX){
					this.x = e.pageX;
				}else{
					this.x = clientX;
				}
				
				if (e.pageY){
					this.y = e.pageY;
				}else {
					this.y = e.clientY;
				}
				
				if(e.target){
					this.cil = e.target;
				}else{
					this.cil = e.srcElement;
				}
			}
			
			function pridejCallback(typ, callback){
				if (document.addEventListener){
					document.addEventListener(typ, callback, false);
				}else if (document.attachEvent){
					document.attachEvent("on"+typ, callback, false);
				}
			}
			function zpracujMouseDown(e){
				var e = new UdalostMysi(e);
				pridejCallback("mousemove",zpracujMouseMove);
				pridejCallback("mouseup",zpracujMouseUp);
				poziceX = e.x - parseInt(e.cil.style.left);
				poziceY = e.y - parseInt(e.cil.style.top);
				document.getElementById("cilovyDiv").innerHTML = "";				
			}
			
			function zpracujMouseMove(e){
				var e = new UdalostMysi(e);
				var x = e.x - poziceX;
				e.cil.style.left = x + "px";
				var y = e.y - poziceY;
				e.cil.style.top = y + "px";
			}
			
			function zpracujMouseUp(e){
				var e = new UdalostMysi(e);
				
				
				odeberCallback("mousemove", zpracujMouseMove);
				odeberCallback("mouseup", zpracujMouseUp);
				/*
				var kosik = document.getElementById("kosik");
				var x = parseInt(kosik.style.left);
				var y = parseInt(kosik.style.top);
				var sirka = parseInt(kosik.style.width);
				var vyska = parseInt(kosik.style.height);
				*/
			}
			
			function odeberCallback(typ, callback){
				if (document.removeEventListener){
					document.removeEventListener(typ, callback, false);
				}else if(document.detachEvent){
					document.detachEvent("on" + typ, callback, false);
				}
			}