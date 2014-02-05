<link rel="stylesheet" type="text/css" href="tablesorter/css/theme.bootstrap.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<script type="text/javascript" src="/js/jquery/jquery-1.4.4.min.js"></script> 
<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.9.custom.min.js"></script> 
<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.widgets.min.js"></script> 
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="js/tablesorter_default_table.js"></script>
<script type="text/javascript" src="js/typeahead.js/dist/typeahead.bundle.js"></script>


<script language="javascript">
function chkConfirm() 
{
    return confirm("Are you sure! you want to delete this record.");
}
function selectCity(value){
  window.location.href="{$dirname}/locality_near_places_priority.php?&citydd="+value;
}
function selectSuburb(value){
  var cityid = $('#citydd').val();
    window.location.href="{$dirname}/locality_near_places_priority.php?citydd="+cityid+"&suburb="+value;
}
function selectLocality(value){ 
    var cityid = $('#citydd').val();
  window.location.href="{$dirname}/locality_near_places_priority.php?citydd="+cityid+"&locality="+value;
}
function selectNearPlaceTypes(value){ 
    var cityid = $('#citydd').val();
    var locality_id = $('#loc').val();
    var suburb_id = $('#sub').val();
  window.location.href="{$dirname}/locality_near_places_priority.php?citydd="+cityid+"&locality="+locality_id+"&near_place_type="+value;
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


function nearPlacePriorityEdit(id,type)
{
    var cityid      = $('#citydd').val();
    var localityid  = $('#loc').val();
    var suburbid    = $('#sub').val();
    var priority    = $('#priority'+id).val();
    var status      =  $('#status'+id).val();
//alert(cityid+priority+status);
    $.ajax({
            type: "POST",
            url: '/saveNearPlacePriority.php',
            data: { nearPlaceId: id, prio:priority, cityId:cityid, loc:localityid, sub:suburbid, status:status },
            success:function(msg){
               if(msg == 1){
                   alert("Successfully updated");
                   location.reload(true); 
               }
               if(msg == 2){
                   alert("Error Wrong Near Place selected");
                   return false;
               }
               if(msg == 4){
                   alert("Please enter valid Priority. Priority should be numeric and between 0 to 6.");
                   return false;
               }
            }
        })

    /*
    var url = '/setNearPlacePriority.php?cityId='+cityid+'&localityid='+localityid+'&suburbid='+suburbid+'&type='+type+'&id='+id+'&priority='+priority+'&status='+status+'&mode=edit';
    $.fancybox({
        'width'                :720,
        'height'               :200,
        'scrolling'            : 'yes',
        'href'                 : url,
        'type'                : 'iframe'
    })  */
}

function projectPriorityDelete(id,type)
{
    var cityid      = $('#citydd').val();
    var localityid  = $('#loc').val();
    var suburbid    = $('#sub').val();
    var r = confirm("Are you sure you want to reset");
    if (r == true)
    {
        $.ajax({
          type: "POST",
          url: '/deletePriority.php',
          data: { mode:'project', cityId:cityid, localityid:localityid, suburbid: suburbid, type:type, id:id },
          success:function(msg){
            if(msg == 1){
                 alert("Priority Successfully deleted");
                 window.location.reload(true); 
             }
          }
      })
    }
    else
    {
        alert("OK");
    } 
    
}


function openMap(lat, lon)
{
var url = 'https://maps.google.com/maps?q= '+lat+','+lon;
window.open(url,'1390911428816','width=700,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');return false;

 //alert (lat+lon);
    /*var url = '/https://maps.google.com/maps?q= '+lat+','+lon;
    alert (url);
    $.fancybox({
        'width'                :800,
        'height'               :800,
        'scrolling'            : 'yes',
        'href'                 : url,
        'type'                : 'iframe'
    })*/
}


</script>




<script type="text/javascript">

jQuery(document).ready(function(){
$( "#createAlias").submit(function() {
        var aliasName   = $('#searchid').val();
       // alert("hello");
        //var autoadjust  = $("#autoadjust").is(':checked') ? 1 : 0;
        if($('#searchid').val() === ''){
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

var query = $(".search").val();
var aliases = new Bloodhound({
   datumTokenizer: function(d) { return Bloodhound.tokenizers.whitespace(d.value); },
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  remote: '../data/films/queries/%QUERY.json'
});

aliases.initialize();

$('.example-countries .typeahead').typeahead(null, {
  name: 'aliases',
  displayKey: 'name',
  source: countries.ttAdapter()
});

});

$(function(){
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
});
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

        <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center border=0>












          <form id="createAlias" onsubmit="return false;">
            <div>
        
        
            <tr>
              <td width="20%" align="right-top" ><font color = "red">*</font>Alias Name : </td>

              <td width="30%" align="left">
                  <table>
                    <tr>
                      <div><input type=text class="search" id="searchid" name="aliasName"  value="" style="width:250px;"></div>
                  </tr>
                  <tr>
                      <div id="result"></div>
                  </tr>
                  </table>

              </td> 
              <td align="left" >
                 <input type="submit" value="Submit" style="cursor:pointer">
              </td>
            </tr>
          
            
        
        
        
          
          
        
            </div>
          </form>








          </TABLE>
<!--      </fieldset>-->
              </td>
      </tr>
    </TABLE>                    
        </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>