<?php 
$json = $_GET['json'];
//echo "<PRE>";
//var_dump($json);

//$j1 = json_decode($json);
//$j = json_encode($j1);

//$json = strval($json);
//var str = implode(",", $j);
//echo $json;
////$json = json_encode($json);
// $json;

?>

<link rel="stylesheet" type="text/css" href="js/Jit/Examples/css/base.css">

<link rel="stylesheet" type="text/css" href="js/Jit/Examples/css/Spacetree.css">

<script type="text/javascript" src="js/jquery.js"></script>

<script type="text/javascript" src="js/Jit/jit.js"></script>

<script type="text/javascript" src="js/Jit/jit-yc.js"></script>

<style type="text/css">

#dhtmltooltip{
position: absolute;
left: -300px;
width: 150px;
border: 1px solid black;
padding: 2px;
background-color: lightyellow;
visibility: hidden;
z-index: 100;
/*Remove below line to remove shadow. Below line should always appear last within this CSS*/

}

#dhtmlpointer{
position:absolute;
left: -300px;
z-index: 101;
visibility: hidden;
}

</style>

</head>

<body onload="init();">
<div id="container">



<div id="infovis" style="position:absolute; left:5px; background-color:white;" >
   
    <form>
<input type="hidden" name="Language" id="json" value="<?php echo htmlspecialchars($json); ?>">
</form> 
</div>





</div>

</body>
</html>




<script type="text/javascript">

/***********************************************
* Cool DHTML tooltip script II- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

var offsetfromcursorX=12 //Customize x offset of tooltip
var offsetfromcursorY=10 //Customize y offset of tooltip

var offsetdivfrompointerX=10 //Customize x offset of tooltip DIV relative to pointer image
var offsetdivfrompointerY=14 //Customize y offset of tooltip DIV relative to pointer image. Tip: Set it to (height_of_pointer_image-1).

document.write('<div id="dhtmltooltip"></div>') //write out tooltip DIV
document.write('<img id="dhtmlpointer" src="images/arrow2.gif">') //write out pointer image

var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
if (ie||ns6)
var tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""

var pointerobj=document.all? document.all["dhtmlpointer"] : document.getElementById? document.getElementById("dhtmlpointer") : ""

function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function ddrivetip(thetext, thewidth, thecolor){
if (ns6||ie){
if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
tipobj.innerHTML=thetext
enabletip=true
return false
}
}

function positiontip(e){
if (enabletip){
var nondefaultpos=false
var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
//Find out how close the mouse is to the corner of the window
var winwidth=ie&&!window.opera? ietruebody().clientWidth : window.innerWidth-20
var winheight=ie&&!window.opera? ietruebody().clientHeight : window.innerHeight-20

var rightedge=ie&&!window.opera? winwidth-event.clientX-offsetfromcursorX : winwidth-e.clientX-offsetfromcursorX
var bottomedge=ie&&!window.opera? winheight-event.clientY-offsetfromcursorY : winheight-e.clientY-offsetfromcursorY

var leftedge=(offsetfromcursorX<0)? offsetfromcursorX*(-1) : -1000

//if the horizontal distance isn't enough to accomodate the width of the context menu
if (rightedge<tipobj.offsetWidth){
//move the horizontal position of the menu to the left by it's width
tipobj.style.left=curX-tipobj.offsetWidth+"px"
nondefaultpos=true
}
else if (curX<leftedge)
tipobj.style.left="5px"
else{
//position the horizontal position of the menu where the mouse is positioned
tipobj.style.left=curX+offsetfromcursorX-offsetdivfrompointerX+"px"
pointerobj.style.left=curX+offsetfromcursorX+"px"
}

//same concept with the vertical position
if (bottomedge<tipobj.offsetHeight){
tipobj.style.top=curY-tipobj.offsetHeight-offsetfromcursorY+"px"
nondefaultpos=true
}
else{
tipobj.style.top=curY+offsetfromcursorY+offsetdivfrompointerY+"px"
pointerobj.style.top=curY+offsetfromcursorY+"px"
}
tipobj.style.visibility="visible"
if (!nondefaultpos)
pointerobj.style.visibility="visible"
else
pointerobj.style.visibility="hidden"
}
}

function hideddrivetip(){
if (ns6||ie){
enabletip=false
tipobj.style.visibility="hidden"
pointerobj.style.visibility="hidden"
tipobj.style.left="-1000px"
tipobj.style.backgroundColor=''
tipobj.style.width=''
}
}

document.onmousemove=positiontip;

</script>

<script language="javascript">

var labelType, useGradients, nativeTextSupport, animate;

(function() {
  var ua = navigator.userAgent,
      iStuff = ua.match(/iPhone/i) || ua.match(/iPad/i),
      typeOfCanvas = typeof HTMLCanvasElement,
      nativeCanvasSupport = (typeOfCanvas == 'object' || typeOfCanvas == 'function'),
      textSupport = nativeCanvasSupport 
        && (typeof document.createElement('canvas').getContext('2d').fillText == 'function');
  //I'm setting this based on the fact that ExCanvas provides text support for IE
  //and that as of today iPhone/iPad current text support is lame
  labelType = (!nativeCanvasSupport || (textSupport && !iStuff))? 'Native' : 'HTML';
  nativeTextSupport = labelType == 'Native';
  useGradients = nativeCanvasSupport;
  animate = !(iStuff || !nativeCanvasSupport);
})();

var Log = {
  elem: false,
  write: function(text){
    if (!this.elem) 
      this.elem = document.getElementById('log');
    this.elem.innerHTML = text;
    this.elem.style.left = (500 - this.elem.offsetWidth / 2) + 'px';
  }
};


function init(){
    //init data
    var j = $('#json').val().replace(/'/g, '"');
    var json =   jQuery.parseJSON(j); // JSON.stringify("<?php echo $j;?>");// ;
     //json = JSON.stringify(json);
    //var json = res.substring(1, res.length-1);
   /*
   var json = {'id':'node02','name':'Delhi','children':[{'id':'node1001','name':'ad'},{'id':'node1002','name':'Delhi Central'},{'id':'node1003','name':'Delhi East'},{'id':'node1004','name':'Delhi South'},{'id':'node1005','name':'Delhi to Dwarka'},{'id':'node1006','name':'Dwarka'},{'id':'node1007','name':'ficti suburb','children':[{'id':'node2008','name':'ficti ficti 1 suburb'},{'id':'node2009','name':'ficti ficti suburb','children':[{'id':'node3010','name':'ficti ficti ficti suburb'},{'id':'node3011','name':'ficti ficti ficti1 suburb'}]}]},{'id':'node1012','name':'hello suburb'},{'id':'node1013','name':'hhhi'},{'id':'node1014','name':'hi'},{'id':'node1015','name':'New Delhi'},{'id':'node1016','name':'North Delhi'},{'id':'node1017','name':'West Delhi'}]};
    */
     //alert(json);
    //init Node Types
    //Create a node rendering function that plots a fill
    //rectangle and a stroke rectangle for borders
    $jit.ST.Plot.NodeTypes.implement({
      'stroke-rect': {
        'render': function(node, canvas) {
          var width = node.getData('width'),
              height = node.getData('height'),
              pos = this.getAlignedPos(node.pos.getc(true), width, height),
              posX = pos.x + width/2,
              posY = pos.y + height/2;
          this.nodeHelper.rectangle.render('fill', {x: posX, y: posY}, width, height, canvas);
          this.nodeHelper.rectangle.render('stroke', {x: posX, y: posY}, width, height, canvas);
        }
      }
    });


    

    
    //end
    //init Spacetree
    //Create a new ST instance
    var st = new $jit.ST({
        //id of viz container element
        injectInto: 'infovis',
        //set distance between node and its children
        levelDistance: 50,
        //set an X offset
        
        constrained: false,
        levelsToShow: 5,
        offsetX: 300, 
        offsetY: 50,
        width: 800,
        height: 1600,
        //set node, edge and label styles
        //set overridable=true for styling individual
        //nodes or edges
        Node: {
            overridable: true,
            type: 'stroke-rect',
            height: 20,
            width: 100,

            //canvas specific styles
            CanvasStyles: {
              overridable: true,
              fillStyle: '#daa',
              strokeStyle: '#ffc',
              lineWidth: 2
            }
        },
        Edge: {
            overridable: true,
            type: 'line',
            color: '#000',
            lineWidth: 1
        },
        Label: {
            overridable: true,
            type: labelType,
            style: 'bold',
            size: 10,
            color: '#333'
        },

        Tips: {
  $extend: true,
  
  enable: true,
  type: 'auto',
  offsetX: 20,
  offsetY: 20,
  force: false,
  onShow: $.empty,
  onHide: $.empty
},

        
        //This method is called on DOM label creation.
        //Use this method to add event handlers and styles to
        //your node.
        onCreateLabel: function(label, node){
            
            label.innerHTML = node.name;
            label.id = node.id
            //set label styles
            var style = label.style;
            style.width = 60 + 'px';
            style.height = 17 + 'px';            
            style.color = '#333';
            style.fontSize = '0.8em';
            style.textAlign= 'center';
            style.paddingTop = '3px';
            var d = $(label);
           ///*
            d.setStyle('cursor', 'pointer')
              .set('html', node.name).addEvent('mouseover',  function() {
             // alert("hi");
              ddrivetip("landmark tagged");
            });
             d.addEvent('mouseout',  function() {
              hideddrivetip("");
            });
          //*/
        },
        onPlaceLabel: function(label, node) {
          var style = label.style;
          style.width = node.getData('width') + 'px';
          style.height = node.getData('height') + 'px';            
          style.color = node.getLabelData('color');
          style.fontSize = node.getLabelData('size') + 'px';
          style.textAlign= 'center';
          style.paddingTop = '3px';
          var d = $(label);
          alert("hi");
           ///*
            d.setStyle('cursor', 'pointer')
              .set('html', node.name).addEvent('mouseover',  function() {
             // alert("hi");
              ddrivetip("landmark tagged");
            });
             d.addEvent('mouseout',  function() {
              hideddrivetip("");
            });
          //*/
        },
        onBeforePlotNode: function(node) {
        

      if(node.data.placeType=='city') {         
         node.setCanvasStyle('fillStyle', '#FF7F50'); 
         node.setCanvasStyle('strokeStyle', '#FF7F50');        
      } 
      else if(node.data.placeType=='suburb') { 
        node.setCanvasStyle('fillStyle', '#C0C0C0'); 
      }
      else if (node.data.placeType=='locality'){
        node.setCanvasStyle('fillStyle', '#00FF00'); 
      }

    }, 
    });
    //load json data
    st.loadJSON(json);
    //compute node positions and layout
    st.compute();
    //emulate a click on the root node.
    st.onClick(st.root);
    //end
    
    //Add Select All/None actions
    var nodeAll = $jit.id('select-all-nodes'),
        nodeNone = $jit.id('select-none-nodes'),
        edgeAll = $jit.id('select-all-edges'),
        edgeNone = $jit.id('select-none-edges'),
        labelAll = $jit.id('select-all-labels'),
        labelNone = $jit.id('select-none-labels');
    $jit.util.each([nodeAll, edgeAll, labelAll], function(elem) {
      elem.onclick = function() {
        var pn = elem.parentNode.parentNode.parentNode; //table
        var inputs = pn.getElementsByTagName('input');
        for(var i=0, l=inputs.length; i<l; i++) {
          if(inputs[i].type == 'checkbox') {
            inputs[i].checked = true;
          }
        }
      };
    });
    $jit.util.each([nodeNone, edgeNone, labelNone], function(elem) {
      elem.onclick = function() {
        var pn = elem.parentNode.parentNode.parentNode; //table
        var inputs = pn.getElementsByTagName('input');
        for(var i=0, l=inputs.length; i<l; i++) {
          if(inputs[i].type == 'checkbox') {
            inputs[i].checked = false;
          }
        }
      };
    });
    //get checkboxes
    var nWidth = $jit.id('n-width'),
        nHeight = $jit.id('n-height'),
        nColor = $jit.id('n-color'),
        nBorderColor = $jit.id('n-border-color'),
        nBorderWidth = $jit.id('n-border-width'),
        eLineWidth = $jit.id('e-line-width'),
        eLineColor = $jit.id('e-line-color'),
        lFontSize = $jit.id('l-font-size'),
        lFontColor = $jit.id('l-font-color');
    
    //init Morphing Animations
    var button = $jit.id('update'),
        restore = $jit.id('restore'),
        rand = Math.random,
        floor = Math.floor,
        colors = ['#33a', '#55b', '#77c', '#99d', '#aae', '#bf0', '#cf5', 
                  '#dfa', '#faccff', '#ffccff', '#CCC', '#C37'],
        colorLength = colors.length;
    //add click event for restore
    $jit.util.addEvent(restore, 'click', function() {
      if(init.busy) return;
      init.busy = true;
      
      st.graph.eachNode(function(n) {
        //restore width and height node styles
        n.setDataset('end', {
          width: 60,
          height: 20
        });
        //restore canvas specific styles

        
        n.setCanvasStyles('end', {
          fillStyle: '#999',
          strokeStyle: '#ffc',
          lineWidth: 2
        });
     
        //restore font styles
        n.setLabelDataset('end', {
          size: 10,
          color: '#333'
        });
        //set adjacencies styles
        n.eachAdjacency(function(adj) {
          adj.setDataset('end', {
            lineWidth: 1,
            color: '#ffc'
          });
        });
      });
      st.compute('end');
      st.geom.translate({x:-130, y:0}, 'end');
      st.fx.animate({
        modes: ['linear', 
                'node-property:width:height',
                'edge-property:lineWidth:color',
                'label-property:size:color',
                'node-style:fillStyle:strokeStyle:lineWidth'],
        duration: 1500,
        onComplete: function() {
          init.busy = false;
        }
      });
    });
    //add click event for updating styles
    $jit.util.addEvent(button, 'click', function() {
      if(init.busy) return;
      init.busy = true;
      
      st.graph.eachNode(function(n) {
        //set random width and height node styles
        nWidth.checked && n.setData('width', floor(rand() * 40 + 20), 'end');
        nHeight.checked && n.setData('height', floor(rand() * 40 + 20), 'end');
        //set random canvas specific styles
        nColor.checked && n.setCanvasStyle('fillStyle', colors[floor(colorLength * rand())], 'end');
        nBorderColor.checked && n.setCanvasStyle('strokeStyle', colors[floor(colorLength * rand())], 'end');
        nBorderWidth.checked && n.setCanvasStyle('lineWidth', 10 * rand() + 1, 'end');
        //set label styles
        lFontSize.checked && n.setLabelData('size', 20 * rand() + 1, 'end');
        lFontColor.checked && n.setLabelData('color', colors[floor(colorLength * rand())], 'end');
        //set adjacency styles
        n.eachAdjacency(function(adj) {
          eLineWidth.checked && adj.setData('lineWidth', 10 * rand() + 1, 'end');
          eLineColor.checked && adj.setData('color', colors[floor(colorLength * rand())], 'end');
        });
      });
      st.compute('end');
      st.geom.translate({x:-130, y:0}, 'end');
      st.fx.animate({
        modes: ['linear', 
                'node-property:width:height',
                'edge-property:lineWidth:color',
                'label-property:size:color',
                'node-style:fillStyle:strokeStyle:lineWidth'],
        duration: 1500,
        onComplete: function() {
          init.busy = false;
        }
      });
    });
    //end
}

</script>