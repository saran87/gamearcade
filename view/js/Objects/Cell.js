//////////////////////////////////////////////////////
// Class: Cell										//
// Description:  This will create a cell object		// 
// (board square) that you can reference from the 	//
// game. 											//
// Arguments:										//
//		size - tell the object it's width & height	//
//		??
//		??
//		??
//		??
//////////////////////////////////////////////////////
	
	
// Cell constructor
function Cell(myParent,id,size,col,row) {
	this.parent = myParent;
	this.id = id;
	this.size = size;
	this.col = col;
	this.row = row;
	//initialize the other instance vars
	this.occupied = '';
	this.state = 'alive';
	this.x = this.col * this.size;
	this.y = this.row * this.size;
	//this.color = (((this.row+this.col)%2) == 0) ? 'black' : 'white'
	this.color = 'white';
	this.droppable = (((this.row+this.col)%2) == 0) ? true : false
	
	//create it...
	this.object = this.create();
	this.parent.appendChild(this.object);
	this.myBBox = this.getMyBBox();
	
}

//////////////////////////////////////////////////////
// Cell : Methods									//
// Description:  All of the methods for the			// 
// Cell Class (remember WHY we want these to be		//
// seperate from the object constructor!)			//
//////////////////////////////////////////////////////
Cell.prototype={
	//create it...
	create:function(){
		var rectEle=document.createElementNS(svgns,'rect');
		rectEle.setAttributeNS(null,'x',this.x+'px');
		rectEle.setAttributeNS(null,'y',this.y+'px');
		rectEle.setAttributeNS(null,'width',this.size+'px');
		rectEle.setAttributeNS(null,'height',this.size+'px');
		rectEle.setAttributeNS(null,'class','cell_'+this.color);
		rectEle.setAttributeNS(null,'id',this.id);
		rectEle.setAttributeNS(null,'fill',"url(#bbgGrad)");
		rectEle.setAttributeNS(null,'stroke',"url(#bbgGrad)");
		rectEle.setAttributeNS(null,'stroke-width',"1");
		rectEle.onclick=function(){board.dropPiece(this);};
		return rectEle;
	},
	//get my bbox
	getMyBBox:function(){
		return this.object.getBBox();
	},
	//get CenterX
	getCenterX:function(){
		return (BOARDX+this.x+(this.size/2) );
	},
	//get CenterY
	getCenterY:function(){
		return (BOARDY+this.y+(this.size/2) );
	},
	//set a cell to occupied
	isOccupied:function(pieceId){
		this.occupied=pieceId;
		//for testing purposes only!
		this.changeFill('alert');
	},
	//set cell to empty
	notOccupied:function(){
		this.occupied='';
		//for testing purposes only!
		this.changeFill(this.color);
	},
	changeFill:function(toWhat){
		document.getElementById(this.id).setAttributeNS(null,'class','cell_'+toWhat);
	},
	PI:3.1415697
}
