/*
* Copyright 2012, Saravana Kumar
* Dual licensed under the MIT or GPL Version 2 licenses.
*
*/

var doc=({
	
	CreateDiv:function(clsName){
		var element = document.createElement('div');
		element.className = clsName;
		return element;
	},
	CreateDisplayField:function(clsName,value){
		
		var newText = document.createTextNode(value);		
		var divElement = doc.CreateDiv();	
		divElement.className = clsName;
		divElement.appendChild(newText);
		return divElement;
	},
	CreateList:function(clsName,value){
		var li = document.createElement('li');
		li.className = clsName;

		if(document.all){
			li.innerText = value;
        }
		else{
			li.textContent = value;
       }
		return li;
	},
	CreateLink:function(clsName,href,value){
		var link = document.createElement('a');
		link.className = clsName;
		link.href = href;
		link.value = value;
		link.appendChild(doc.CreateSpan("",value));
		return link;
	},
	CreateImage:function(clsName,img){
		var image = document.createElement('img');
		image.className = clsName
		image.src = img;
		return image;
	},
	CreateListImage:function(clsName,img,value){
		var li = doc.CreateList(clsName,"");
		var link = doc.CreateLink("","#",value);
		var image = doc.CreateImage("",img);
		link.appendChild(image);
		li.appendChild(link);	
		return li;
	},
	CreateLabel:function(clsName,value){
		var label = document.createElement('label');
		label.className = clsName;
		
		if(document.all){
			label.innerText = value;
        }
		else{
			label.textContent = value;
       }
		return label;
	},
	CreateSpan:function(clsName,value){
		var span = document.createElement('span');
		span.className = clsName;
		if(document.all){
			span.innerText = value;
        }
		else{
			span.textContent = value;
       }
		return span;
	},
	CreateUnOrderList:function(clsName){
		var ul = document.createElement('ul');
		ul.className= clsName;
		return ul;
	},
	AppendElement: function(id,element){
		var parent = document.getElementById(id);
		parent.appendChild(element);
	},
	Clear:function(id){
		var parent = document.getElementById(id);
		var element = parent.cloneNode(false);
		var top = parent.parentNode;
		top.removeChild(parent);
		top = top.appendChild(element);
	}
});