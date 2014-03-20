<?php 
error_reporting(1);
  ini_set('display_errors','1');
  include("smartyConfig.php");
  include("appWideConfig.php");
  include("dbConfig.php");
        include("modelsConfig.php");
  include("includes/configs/configs.php");
  include("builder_function.php");
  include("function/alias_functions.php");
  AdminAuthentication();
  include('suburbManageProcess.php');


$cityId = $_GET['cityid'];
if(isset($_GET['subid'])){
  $subid = $_GET['subid'];
  $label = $_GET['label'];
  $parentid = $_GET['pid'];
  $suburbarr = Array();
  $suburbarr['id'] = $subid;
  $suburbarr['label'] = $label;
  $suburbarr['parent_id'] = $parentid;
  $arr = getHierArr($cityId, $suburbarr);
  $json = json_encode($arr[0]);
  $loc_counter = $arr[1];
}
else{
 $arr = getHierArr($cityId);
 $json = json_encode($arr[0]);

  $loc_counter = $arr[1];
}
$node_max = $arr[2]+1;

//echo $loc_counter;
 //echo $str;
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
<input type="hidden" name="Language" id="tree-height" value="<?php echo $loc_counter; ?>">
<input type="hidden" name="Language" id="tree-width" value="<?php echo $node_max; ?>">

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
    //var j = $('#json').val().replace(/'/g, '"');
    
    var json =   jQuery.parseJSON($('#json').val()); // JSON.stringify("<?php echo $j;?>");// ;
    var loc_counter = $('#tree-height').val();
    var treeHeight = loc_counter*50;
    if(treeHeight<600) treeHeight=600;
    var node_max = $('#tree-width').val();
    var treeWidth = node_max*250;
    if(treeWidth<600) treeWidth=600;
    //alert(treeHeight);
     //json = JSON.stringify(json);
   
     //console.log(json);
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
        levelsToShow: 8,
        offsetX: (treeWidth/2)-100, 
        offsetY: 100,
        width: 800,
        height: treeHeight,
        width: treeWidth,
        
        //set node, edge and label styles
        //set overridable=true for styling individual
        //nodes or edges
        Node: {
            overridable: true,
            
            height: 0,
            width: 150,
            autoHeight: false,
            autoWidth: true,
            align: 'left',
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
            type: "HTML",
            style: 'bold',
            size: 10,
            color: '#333'
        },
        

        

        
        //This method is called on DOM label creation.
        //Use this method to add event handlers and styles to
        //your node.
        
        onCreateLabel: function(label, node){
            
            label.innerHTML = node.name;
            label.id = node.id
            console.log(label);
            //set label styles
            var style = label.style;
            style.width = 60 + 'px';
            style.height = 17 + 'px';            
            style.color = '#333';
            style.fontSize = '0.8em';
            style.textAlign= 'center';
            style.paddingTop = '3px';
            var d = $(label);
            d.mouseover(function(){
              ddrivetip(node.data.alias);
            });
            d.mouseout(function(){
              hideddrivetip("");
            });
            /*d.setStyle('cursor', 'pointer')
              .set('html', node.name).addEvent('mouseover',  function() {
              //alert("hi inside onCreateLabel");
              ddrivetip(label.id);
            });
             d.addEvent('mouseout',  function() {
              hideddrivetip("");
            });
           */
            
        },
        onPlaceLabel: function(label, node) {
          //console.log(node.name);
          var style = label.style;
          style.width = node.getData('width') + 'px';
          style.height = node.getData('height') + 'px';            
          style.color = node.getLabelData('color');
          style.fontSize = node.getLabelData('size') + 'px';
          style.textAlign= 'center';
          style.paddingTop = '3px';
          var d = $(label);
          
        },
        onBeforePlotNode: function(node) {
        
            //console.log(node.name);
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
      //st.onClick(node.id);
    }, 
    

    

    });
    //load json data
    st.loadJSON(json);
    //compute node positions and layout
    st.compute('end');
    //emulate a click on the root node.
    st.select(st.root);
    //st.onClick(st.root);
    //end
   
    
    //end
}

</script>