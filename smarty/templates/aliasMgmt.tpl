
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<link href="/js/jQuery-Autocomplete-master/content/styles.css" rel="stylesheet" />
<script type="text/javascript" src="/js/jquery/jquery-1.8.3.min.js"></script> 
<script type="text/javascript" src="/js/jQuery-Autocomplete-master/scripts/jquery.mockjax.js"></script>
<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.9.custom.min.js"></script> 
<script type="text/javascript" src="/js/jQuery-Autocomplete-master/src/jquery.autocomplete.js"></script>




<script language="javascript">
function chkConfirm() 
{
    return confirm("Are you sure! you want to delete this record.");
}
function selectCity(value){
  window.location.href="{$dirname}/locality_near_places_priority.php?&citydd="+value;
}


function openProjectPriorityAdd()
{
    var cityid      = $('#citydd').val();
    var localityid  = $('#loc').val();
    var suburbid    = $('#sub').val();
    var url = '/setProjectPriority.php?cityId='+cityid+'&localityid='+localityid+'&suburbid='+suburbid;
    $.fancybox({
        'width'                : 720,
        'height'               : 200,
        'scrolling'            : 'yes',
        'href'                 : url,
        'type'                 : 'iframe'
    })
}



  


jQuery(document).ready(function(){
$( "#createAlias").submit(function() {
        var aliasName   = $('#query').val();
       // alert("hello");
        //var autoadjust  = $("#autoadjust").is(':checked') ? 1 : 0;
        if($('#query').val() === ''){
            alert("Please provide an Alias name");
            return false;
        }
        
        //alert (prior+cityId);
        $.ajax({
            type: "POST",
            url: '/saveAliases.php',
            data: { aliasname: aliasName },
            success:function(msg){
               if(msg == 1){
                   alert("Alias Successfully Created.");
                   location.reload(true); 
               }
               if(msg == 2){
                   alert("Alias Already Exist.");
                   location.reload(true); 
               }
               if(msg == 3){
                   alert("Error in Creating Alias.");
                   return false;
               }
               if(msg == 4){
                   alert("No Alias Provided.");
                   return false;
               }
            }
        })
  });







});

var options, a, b;

jQuery(function(){
   options = { serviceUrl:'/findAliases.php', appendTo: '#contain' };
   a = $('#query').autocomplete(options);
});


/* $(function(){
$(".search").keyup(function() 
{ 
 
var searchid = $(this).val();
var dataString = 'search='+ searchid;
if(searchid!='')
{
    $.ajax({
    type: "POST",
    url: '/findAliases.php',
    data: dataString,
    cache: false,
    success: function(html)
    {
    $("#result").html(html).show();
    }
    });
}return false;    
});

jQuery("#result").live("click",function(e){ 
  //alert('click');
    var $clicked = $(e.target);
    
    var $name = $clicked.find('.name').html();
    alert($name);
    //var decoded = $("<div/>").html($name).text();
    //var decoded = $clicked.html();
     //alert(decoded);
    $('#searchid').val(decoded);
});

jQuery(document).live("click", function(e) { 
    var $clicked = $(e.target);
    if (! $clicked.hasClass("search")){
    jQuery("#result").fadeOut(); 
    }
});

$('#searchid').click(function(){

    jQuery("#result").fadeIn();
});
});*/
</script>





</TD>
  </TR>
    <TR>
    <TD class="white-bg paddingright10" vAlign=top align=middle bgColor=#ffffff>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><TBODY>
        <TR>
          <TD width=224 height=25>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=866>&nbsp;</TD>
  </TR>
        <TR>
          <TD class=paddingltrt10 vAlign=top align=middle bgColor=#ffffff>
        {include file="{$PROJECT_ADD_TEMPLATE_PATH}left.tpl"}
    </TD>
          <TD class=border-all vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD class=border-rt vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Create New Aliases</TD>
                      <TD align=right ></TD>
                    </TR>
      </TBODY></TABLE>
    </TD>
        </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>

             <!-- <div class="container" id="contain">

                   <p>Type Alias name:</p>
                   <div style="position: relative; height: 80px;">
                   <input type="text" name="query" id="query" style="position: absolute; z-index: 2; background: transparent;"/>
                   <input type="text" name="query" id="query-x" disabled="disabled" style="color: #CCC; position: absolute;   background: transparent; z-index: 1;"/>
                   </div>
              <div id="selction"></div>
              </div> -->

        <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center border=0>

            










             <form id="createAlias" onsubmit="return false;">
            <div>
        
        
            <tr>
              <td width="20%" align="right-top" ><font color = "red">*</font>Alias Name : </td>

              <td width="30%" align="left">
                 <!-- <div id="">
                     <input type="text" name="query" id="query" style="position: absolute; z-index: 2; background: transparent;"/>
                    </div><div id=""></div>
                    <div id="contain"></div>-->
                <div style="position: relative; height: 80px; width: auto" >
                   <input type="text" name="query" id="query"/>
                   <div id="contain" style="width: auto"></div>
                   </div>
              
              </td> 
              <td align="left" >
                 <input type="submit" value="Submit" >
              </td>
            </tr>
          
            
        
        
        
          
          
        
            </div>
          </form>


      





          </TABLE>
     </fieldset>
              </td>
      </tr>
    </TABLE>                    
        </TD>
            </TR>
          </TBODY> </TABLE>
        </TD>
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>

