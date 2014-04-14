
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="tablesorter/css/theme.bootstrap.css">
<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.widgets.min.js"></script> 
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="js/tablesorter_default_table.js"></script>
<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>



<script language="javascript">

function showHier(cid, sid, slabel, pid){
  //alert(cid+sid+slabel+pid);  
  $.fancybox({
        'width'                :800,
        'height'               :800,
        'scrolling'            : 'no',
        'href'                 : "/showHierarchy.php?cityid="+cid+"&subid="+sid+"&label="+slabel+"&pid="+pid,
        'type'                : 'iframe',
        
    })
}





jQuery(document).ready(function(){

  var selectedItem;

 $( "#createAlias").submit(function() {
  //alert(selectedItem);
    var placeName = $('#searchPlace').val().trim();
    if(jQuery.isEmptyObject(selectedItem)==false && placeName!=''){

        var aliasName   = $('#alias').val().trim();
        var res = selectedItem.id.split("-");
        var tableName = res[1];
        if (tableName=='LOCALITY')
        var tableId = parseInt(res[2]);
        else if (tableName=='SUBURB')
        var tableId = parseInt(res[2]);
        else 
        var tableId = res[2];
        //alert(tableId);
        //alert("tb:"+tableName+tableId);
        //var autoadjust  = $("#autoadjust").is(':checked') ? 1 : 0;
        if(aliasName == ''){
            alert("Please provide an Alias name");
            return false;
        }
        
        //alert (prior+cityId);
        $.ajax({
            type: "POST",
            url: '/saveAliases.php',
            data: { tableName : tableName, tableId : tableId, aliasName : aliasName, task : 'createAlias' },
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
               if(msg == 5){
                   alert("Alias name is same as either city or suburb or locality. So can not be created.");
                   return false;
               }
            }
        })
    }
    else{
      alert("Please provide a location.");
    }
  });







 

 

  $.widget( "custom.catcomplete", $.ui.autocomplete, {
   /* _renderMenu: function( ul, items ) {
      var that = this;
        currentCategory = "";
        
      $.each( items, function( index, item ) {
        var res = item.id.split("-");
        var tableName = res[1];
        //console.log(index+item);
        if ( tableName != currentCategory ) {
          ul.append( "<li class='ui-autocomplete-category'><strong>" + tableName  + "</strong></li>" );
          //item.parents().html += "<strong>" + tableName  + "</strong>";
          currentCategory = tableName;
        }
        that._renderItemData( ul, item );

      });
    },

    _renderItemData: function( ul, item ) {
    var that = this;
    that._renderItem( ul, item ).data( "ui-autocomplete-item", item );
    },
*/
  _renderItem: function( ul, item ) {
    var res = item.id.split("-");
        var tableName = res[1];
    return $( "<li>" )
      .append( $( "<a>" ).text( item.label + "........." +tableName ) )
      .appendTo( ul );
  },
  

  });


 $( "#searchPlace" ).catcomplete({
     // q = $("#searchPlace").val();
      //alert("hello");
      source: function( request, response ) {
        $.ajax({
          url: "http://nightly-build.proptiger-ws.com/app/v1/typeahead?query="+$("#searchPlace").val()+"&typeAheadType=(locality or city or suburb)&rows=10",
          dataType: "json",
          data: {
            featureClass: "P",
            style: "full",
           
            name_startsWith: request.term
          },
          success: function( data ) {
            //alert(data);
            response( $.map( data.data, function( item ) {              
                return {
                label: item.displayText,
                value: item.label,
                id:item.id,
                }
              
            }));
          }
        });
      },
      
      select: function( event, ui ) {
        selectedItem = ui.item;
        //alert(selectedItem.label);
        //log( ui.item ?
         // "Selected: " + ui.item.label :
          //"Nothing selected, input was " + this.value);
      },
      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      },

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
                <TD vAlign=top align=middle class="backgorund-rt" ><BR>

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
              

            <div class="ui-widget"><td width="20%" align="right"><label for="search">Create Alias For: </label></td>
            <td width="30%" align="left"><input id="searchPlace"></td></div>
            </tr>
            <tr>
            <td width="20%" align="right"><label for="search">Alias: </label></td>
             <td width="30%" align="left"><input id="alias"></td></div>         
              
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
      <tr>
        <td>
          <TABLE cellSpacing=1 cellPadding=4 width="50%" align=center border=0 class="tablesorter">
                        <form name="form1" method="post" action="">
                          <thead>
                                <TR class = "headingrowcolor">
                                  <th  width=10% align="center">Serial</th>
                                  <th  width=15% align="center">Entity Type</th>
                                  <th  width=15% align="center">Entity Name</th>
                                  <TH  width=15% align="center">Alias</TH>
                                  
                                </TR>
                              
                          </thead>
                          <tbody>
                               
                                {$i=0}
                               
                                {foreach from=$aliasesArr key=k item=v}
                                    {$i=$i+1}
                                    {if $i%2 == 0}
                                      {$color = "bgcolor = '#F7F7F7'"}
                                    {else}                            
                                      {$color = "bgcolor = '#FCFCFC'"}
                                    {/if}
                                <TR {$color}>
                                  <TD align=center class=td-border>{$i}</TD>
                                  {if isset($v.c_label)}
                                  <TD align=center class=td-border>City</TD>
                                  <TD align=center class=td-border>{$v.c_label}</TD>
                                  {elseif isset($v.l_label)}
                                  <TD align=center class=td-border>Locality</TD>
                                  <TD align=center class=td-border>{$v.l_label}, {$v.l_clabel}</TD>
                                  {elseif isset($v.s_label)}
                                  <TD align=center class=td-border>Suburb</TD>
                                  <TD align=center class=td-border><a href="#" onclick="showHier('{$v.s_cid}','{$v.s_id}','{$v.s_label}','{$v.s_pid}');">{$v.s_label}, {$v.s_clabel}</a></TD>
                                  {/if}
                                  <TD align=center class=td-border>{$v.alias_name}</TD>                                  
                                </TR>
                                {/foreach}
                                <!--<TR><TD colspan="9" class="td-border" align="right">&nbsp;</TD></TR>-->
                          </tbody>
                          <tfoot>
                                                        <tr>
                                                            <th colspan="21" class="pager form-horizontal" style="font-size:12px;">
                                                                
                                                                <button class="btn first"><i class="icon-step-backward"></i></button>
                                                                <button class="btn prev"><i class="icon-arrow-left"></i></button>
                                                                <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                                                                <button class="btn next"><i class="icon-arrow-right"></i></button>
                                                                <button class="btn last"><i class="icon-step-forward"></i></button>
                                                                <select class="pagesize input-mini" title="Select page size">
                                                                    <option value="10">10</option>
                                                                    <option value="20">20</option>
                                                                    <option value="50">50</option>
                                                                    <option selected="selected" value="100">100</option>
                                                                </select>
                                                                <select class="pagenum input-mini" title="Select page number"></select>
                                                            </th>
                                                        </tr>
                           </tfoot>
                        </form>
                    </TABLE>
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

