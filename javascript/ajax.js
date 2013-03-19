/*************Ajax code************/	
		function GetXmlHttpObject()
		{
			var xmlHttp=null;
			try
			{
					// Firefox, Opera 8.0+, Safari
					xmlHttp=new XMLHttpRequest();
			}
			catch (e)
			{
				//Internet Explorer
			try
			{
				xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{
				xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
		}
		return xmlHttp;
	}

	/*******************project drop down for add new order***************************/
	function project_refresh(builderid)
	{
		//alert("here"+builderid);
		xmlHttpbuilder=GetXmlHttpObject()
		var url="ajax/project_list.php?&builderid="+builderid;
		//alert(url);
		xmlHttpbuilder.open("GET",url,false);
		xmlHttpbuilder.send(null);
		var pricingresult=xmlHttpbuilder.responseText;
		if(pricingresult)
		{
			//alert(xmlHttpbuilder.responseText);
			document.getElementById('projectdiv').innerHTML=xmlHttpbuilder.responseText;
			
		}
		
	}
	
	/*************project type and area drop down refresh in new order add**************************/
	function type_refresh(projectid)
	{
		//alert("here"+builderid);
		xmlHttpproject=GetXmlHttpObject()
		var url="ajax/project_type.php?&projectid="+projectid;
		//alert(url);
		xmlHttpproject.open("GET",url,false);
		xmlHttpproject.send(null);
		var pricingresult=xmlHttpproject.responseText;
		if(pricingresult)
		{
			
			document.getElementById('type_area_rate').innerHTML=xmlHttpproject.responseText;
			
		}
		
	}

	
	/************************pricing ajax**************************/

	 function projectcngfun()
	{
		
		var pidchk	=	document.getElementById("projectcngf").value;	
//document.getElementById("addrowcount").innerHTML="20";
		if(pidchk == '')
			alert("Please select project");
		else
			projectcngprice(pidchk);
	}
	function projectcngprice(projectid)
	{
		var projectid	=	document.getElementById("projectcngf").value;
		
			xmlHttpprice=GetXmlHttpObject()
		if (xmlHttpprice==null)
		{
			alert ("Browser does not support HTTP Request")
			return;
		}
		
		var url="ajax/price_detail.php?&projectid="+projectid;
		
		xmlHttpprice.open("GET",url,false);
		xmlHttpprice.send(null);
		var pricingresult=xmlHttpprice.responseText;
		//alert(xmlHttpprice.responseText);
	   // return pricingresult;
		if(pricingresult)
		{
			document.getElementById('pricediv').innerHTML=xmlHttpprice.responseText;
			chngsubmit();
		}
		else
		{
			alert("Please select project");
		}
	}
	

	/************************End pricing ajax************************/




   /***************************Delete Proj Pricing **************/


    function DeleteProjPricing(pid)
	{
		var confirmm=confirm("Do you want to delete this record?");
		if(confirmm)
		{
			xmlfordelete=GetXmlHttpObject();
		var url="ajax/ManageCMS.php?act=deleteprojprice&key="+pid;

		xmlfordelete.open("GET",url,false);
		xmlfordelete.send(null);
		var result=xmlfordelete.responseText;
		
		if(result)
			{
         projectcngfun();
			}

		}
	}

   /*********************END****************/
   





  /********************EDIT PYCH JS****************/

/*
</td><td>/td><td><INPUT TYPE="text" id="compvalue$i" style="display:none"></td><td><img id="actimg$i" 
*/

var SaveId=0;
function addEditRowPs(id)
{     var ele="sc"+id;
	document.getElementById(ele).style.display="none";
	var ele="insno"+id;
var insno=document.getElementById(ele).style.display="";

	var ele="insname"+id;
var insname=document.getElementById(ele).style.display="";

	var ele="pymntplan"+id;
var pymntplan=document.getElementById(ele).style.display="";

	var ele="compname"+id;
var compname=document.getElementById(ele).style.display="";

	//var ele="comptype"+id;
//var comptype=document.getElementById(ele).style.display="";

	var ele="compvalue"+id;
var compvalue=document.getElementById(ele).style.display="";

	var ele="actimg"+id;
var actimg=document.getElementById(ele).src="images/saveIcon.gif";

document.getElementById(ele).addEventListener("onclick",performPsEdit,false);

this.SaveId=id;
performPsSave();

}

var ProID='';
function performPsSave()
{
var id=this.SaveId;

var ele="compname"+id;
var compname=document.getElementById(ele).value;
var ele="compvalue"+id;
var compvalue=document.getElementById(ele).value;

var ele="insno"+id;
var insno=parseInt(document.getElementById(ele).innerHTML);


	var ele="insname"+id;
var insname=document.getElementById(ele).innerHTML;

	var ele="pymntplan"+id;
var pymntplan=document.getElementById(ele).innerHTML;


	var ele="o"+id;
	
var iddd=document.getElementById(ele).value;




var comptype=0;
		if(compname=="FIXED")
		{
			comptype=1;
		}


if(compname !="" && compvalue !="")
	{



					  
				//Validating COMP_NAME_SELECTION
				var SimilarIds=new Array();
				
				for(var i=0; i<1000; i++)
					{
						 ele="i"+i;
						try
						{
							
							var no=document.getElementById(ele).value;
						
							if(insno==no)
							{
								
								SimilarIds.push(i);
							}

							
						}
						catch(exception)
						{
						}

					}


					

				//***END
				   
							var val='';
							var ChkArr=new Array();
				var lst=0;
			//	alert(SimilarIds);
							  for(var i=0; i<SimilarIds.length; i++)
								{
								   ele=SimilarIds[i];
								    lst=ele;
								   ele="l"+ele;
								   val=document.getElementById(ele).value;
								   if(val !="Select" && val !="" && val !=" ")
		                           {
								   ChkArr.push(val);
								   }
                                  
								}
                                  lst=lst+1;
								 
								   ele="compname"+lst;
								   val=document.getElementById(ele).value;
								   if(val !="Select" && val !="" && val !=" ")
		                           {
								   ChkArr.push(val);
								   }



						 if(arrHasDupes(ChkArr)==false)
						 {

      

                                    xmlHttpEditS=GetXmlHttpObject();
									this.ProID=iddd;
									
									var url="ajax/paysch_edit.php?act=save&insno="+insno+"&insname="+insname+"&paymentplan="+pymntplan+"&compname="+compname+"&compvlu="+compvalue+"&comptype="+comptype+"&id="+iddd;
									xmlHttpEditS.onreadystatechange=StateChangedForSave;
									xmlHttpEditS.open("GET",url,true);

								   xmlHttpEditS.send(null);
						//		   ldr='stts'+id;
						 }
						 else
						{
							  alert("Component Name for this installment can not be same!");
						}
	}
	else
	{
		 wc++;
			 if(wc>1)
			{
		 alert("Please Enter values in all field.");
			}
	}
}


var ldr='';

var projectId=0;
var wc=0;

//projectcngf

function performPsAddIns()
{
var idn='';
var vlu='';
var projId=document.getElementById("projectcngf").value;
    for(var i=0;i<1000;i++)
	{
		try
		{
			
	  idn="i"+i;
	  vlu=document.getElementById(idn).value;
	  
		}
		catch(exception)
		{
		}

	}

vlu=parseInt(vlu);
vlu=vlu+1;

	                               xmlHttpEditPA=GetXmlHttpObject();
								

								var exid=document.getElementById("exid").value;
									var url="ajax/paysch_edit_add.php?projectId="+projId+"&vlu="+vlu+"&exid="+exid;
									xmlHttpEditPA.onreadystatechange=StateChangedForPA;
									xmlHttpEditPA.open("GET",url,true);

								   xmlHttpEditPA.send(null);
}




function StateChangedForPA()
{
if(xmlHttpEditPA.readyState==4)
	{
         document.getElementById("payschdivadd").innerHTML=xmlHttpEditPA.responseText;
		 //document.getElementById("addLink").style.display="none";
	}
}

function performPsEdit(id,projectid)
{
	
var ele="i"+id;
		//var insno=document.getElementById(ele).style.display="";
		var insnovlu=document.getElementById(ele).value;
//performINVEditAction(id,projectid)
 ldr='sttss'+insnovlu;

var SimilarIds=new Array();
		for(var i=0; i<1000; i++)
			{
				 ele="i"+i;
				try
				{
					
					var no=document.getElementById(ele).value;
					
					if(insnovlu==no)
					{
						
						//SimilarIds.push(i);
						
						performPsEditAction(i,projectid);
					}

					
				}
				catch(exception)
				{
					break;
				}

			}

}

function performPsEditAction(id,projectid)
{
var ele="i"+id;
var insno=document.getElementById(ele).style.display="";
var insnovlu=document.getElementById(ele).value;
	var ele="j"+id;
var insname=document.getElementById(ele).style.display="";
var insnamevlu=document.getElementById(ele).value;

	var ele="k"+id;
var pymntplan=document.getElementById(ele).style.display="";
var pymntplanvlu=document.getElementById(ele).value;
	var ele="l"+id;
var compname=document.getElementById(ele).style.display="";
var compnamevlu=document.getElementById(ele).value;

var ele="m"+id;
var compvlu=document.getElementById(ele).style.display="";
var compvlu=document.getElementById(ele).value;

	var ele="n"+id;
	
var idd=document.getElementById(ele).value;

projectId=projectid;




//Validating COMP_NAME_SELECTION
var SimilarIds=new Array();
for(var i=0; i<1000; i++)
	{
		 ele="i"+i;
		try
		{
			
			var no=document.getElementById(ele).value;
			
			if(insnovlu==no)
			{
				
				SimilarIds.push(i);
			}

			
		}
		catch(exception)
		{
		}

	}


	

//***END
   
   if(insnamevlu=="" || pymntplanvlu=="" || compnamevlu=="" || compvlu=="")
	{
      alert("Please Enter values in all field.");
	}
	else
	{
	
		
			var val='';
			var ChkArr=new Array();
			  for(var i=0; i<SimilarIds.length; i++)
				{
				   ele=SimilarIds[i];
				   ele="l"+ele;
				   val=document.getElementById(ele).value;
				   if(val !="Select" && val !="" && val !=" ")
		           {
				   ChkArr.push(val);
				   }

				}


	     if(arrHasDupes(ChkArr)==false)
		 {

				xmlHttpEditP=GetXmlHttpObject();
				
				var comptype=0;
				if(compnamevlu=="FIXED")
				{
					comptype=1;
				}
				else
				 {


                      if(compvlu > 101 )
					 {
						  alert("The maximum value can be 100.");
						  return;
					 }
				 }


				var url="ajax/paysch_edit.php?act=edit&compname="+compnamevlu+"&compvlu="+compvlu+"&comptype="+comptype+"&id="+idd;
				
					xmlHttpEditP.onreadystatechange=StateChanged;
					xmlHttpEditP.open("GET",url,true);

				   xmlHttpEditP.send(null);
				 //  ldr='stts'+id;
				  //var result=xmlHttpEditP.responseText;
		 }
		 else
		{
			
			 alert("Component Name for this installment can not be same!");
			
		}
				
	}



}




var ProIDdelete=0;
function performPsDelete(id,proid)
{
	this.ProIDdelete=proid;
	var cfrm=confirm("Do you want to delete this record?");
	if(cfrm)
	{
			xmlHttpEditD=GetXmlHttpObject();
			var url="ajax/paysch_edit.php?act=delete&id="+id;
					xmlHttpEditD.onreadystatechange=StateChangedForDelete;
					xmlHttpEditD.open("GET",url,true);

				   xmlHttpEditD.send(null);
	}
}


function StateChangedForDelete()
{
   if(xmlHttpEditD.readyState==4)
	{
	   
	   projectcngfun_edit(ProIDdelete);
	}
        
}



function StateChangedForSave()
{
   if(xmlHttpEditS.readyState==4)
	{
	   //alert(xmlHttpEditS.responseText);

	   //alert(ProID);
	   projectcngfun_edit(ProID);
	}
        
}

function StateChanged()
{

		      document.getElementById(ldr).style.display="";
              document.getElementById(ldr).src="images/small_loader.gif";
	    if(xmlHttpEditP.readyState==4)
        {
//alert(xmlHttpEditP.responseText);
             projectcngfun_edit(projectId);
             document.getElementById(ldr).title="Updated";
			 document.getElementById(ldr).src="images/ok.png";
			   document.getElementById(ldr).style.display="";
			  setTimeout("hideLoader1('"+ldr+"')",5000);

		}
}


function hideLoader(ldr)
{

    document.getElementById(ldr).title="Updated";
    document.getElementById(ldr).src="images/ok.png";
     
 

	 setTimeout("showThk('"+ldr+"')",5000);

}

function showThk(ldr)
{
 //document.getElementById(ldr).style.display="none";
invoicefun_edit(ProIdEdt);
	  
}




function hideLoader1(ldr)
{

    document.getElementById(ldr).title="Updated";
    document.getElementById(ldr).src="images/ok.png";
     
 

	 setTimeout("showThk1('"+ldr+"')",5000);

}

function showThk1(ldr)
{
// 	   document.getElementById(ldr).style.display="none";
 paysch_edit();
	  
}





  /******************END**********************/




  /************************EDIT ORDER MGMT *****************/


   function PerformEdit(countid)
   {
	   var e="pemail"+countid;
       var ne="email"+countid;

	   var v=document.getElementById(e).value;
	
document.getElementById(ne).style.display="none";
document.getElementById(e).style.display="";


   }

  /*********************END******************/








	/************************Commission ajax**************************/

	 function comm_projectcngfun()
	{

		var pidchk	=	document.getElementById("projectcngf").value;	
		if(pidchk == '')
			alert("Please select project");
		else
			projectcngfuncall(pidchk);
	}
	function projectcngfuncall(projectid)
	{
		
		var projectid	=	document.getElementById("projectcngf").value;
		
			xmlHttpcomm=GetXmlHttpObject()
		if (xmlHttpcomm==null)
		{
			alert ("Browser does not support HTTP Request")
			return
		}
		
		var url="ajax/commission_detail.php?&projectid="+projectid;
		
		xmlHttpcomm.open("GET",url,false);
		xmlHttpcomm.send(null);
		var pricingresult=xmlHttpcomm.responseText;
		//alert(xmlHttpprice.responseText);
	   // return pricingresult;
		if(pricingresult)
		{
			document.getElementById('commdiv').innerHTML=xmlHttpcomm.responseText;
			commi_chngsubmit();
		}
		else
		{
			alert("Please select project");
		}
	}
	

	/************************End Commission ajax************************/

	/************************Payment schedule ajax**************************/

	 function paysch_projectcngfun()
	{

		var pidchk	=	document.getElementById("projectcngf").value;	
		if(pidchk == '')
			alert("Please select project");
		else
			projectcngfun_paysch(pidchk);
	}
    function paysch_edit()
	{

		var pidchk	=	document.getElementById("projectcngf").value;	
		if(pidchk == '')
			alert("Please select project");
		else
			projectcngfun_edit(pidchk);
	}

	function projectcngfun_paysch(projectid)
	{
		
		var projectid	=	document.getElementById("projectcngf").value;
		
			xmlHttppaysch1=GetXmlHttpObject()
		if (xmlHttppaysch1==null)
		{
			alert ("Browser does not support HTTP Request")
			return
		}
		var installmentno	=	document.getElementById("installmentno").value;
		var url="ajax/paysch_detail.php?&projectid="+projectid+"&installno="+installmentno;
		
		xmlHttppaysch1.open("GET",url,false);
		xmlHttppaysch1.send(null);
		var pricingresult=xmlHttppaysch1.responseText;
		//alert(xmlHttpprice.responseText);
	   // return pricingresult;
		if(pricingresult)
		{
		//alert(xmlHttppaysch1.responseText);
			document.getElementById('payschdiv').innerHTML=xmlHttppaysch1.responseText;
			//paysch_chngsubmit();

		}
		else
		{
			alert("Please select project");
		}
	}
	



	function projectcngfun_edit(projectid)
	{
		
		
		
			xmlHttppaysch3=GetXmlHttpObject()
		if (xmlHttppaysch3==null)
		{
			alert ("Browser does not support HTTP Request")
			return
		}
		
		var url="ajax/paysch_edit.php?&projectId="+projectid;
		
		xmlHttppaysch3.open("GET",url,false);
		xmlHttppaysch3.send(null);
		var pricingresult=xmlHttppaysch3.responseText;
		
		if(pricingresult)
		{
		
			document.getElementById('payschdivedit').innerHTML=xmlHttppaysch3.responseText;
			callTrick();
		

		}
		else
		{
			alert("Please select project");
		}
	}
	
	

	/*function paysch_chngsubmit()
{

	paysch_pselect = $("#pselect").val();

	if(paysch_pselect == 'pselect')
	{
		document.getElementById("paysch_chngsubmit").innerHTML = "<input type='button' name='btnSave' id='btnSave' value='Update' onclick='return paysch_submit_frm();'>";
		
	}
	else
	{
		document.getElementById("paysch_chngsubmit").innerHTML = "<input type='button' name='btnSave' id='btnSave' value='Save' onclick='return paysch_submit_frm();'>";
	}

}*/

	/************************End payment schedule ajax************************/



/****************************EDIT INVOICE *******************************/
var ccnt=0;
function addEditRowIn(id)
{
	var ele="sc"+id;
document.getElementById(ele).style.display="none";

	

	var ele="ccompname"+id;

var compname=document.getElementById(ele).style.display="";

var ele="iinstall"+id;
var comptype=document.getElementById(ele).style.display="";

	var ele="ccompvalue"+id;
var compvalue=document.getElementById(ele).style.display="";


	var ele="ccomment"+id;
var compvalue=document.getElementById(ele).style.display="";

	var ele="aactimg"+id;
var actimg=document.getElementById(ele).src="images/saveIcon.gif";

document.getElementById(ele).addEventListener("onclick",performPsEdit,false);

this.SaveId=id;

performInvSave();


}




function performInvSave()
{
	
var id=this.SaveId;
var ele="ccompname"+id;
var compname=document.getElementById(ele).value;
var ele="ccompvalue"+id;
var compvalue=document.getElementById(ele).value;
var ele="ccomment"+id;
var comment=document.getElementById(ele).value;
var ele="iinstall"+id;
var insname=document.getElementById(ele).value;
var ele="iinsno"+id;
var insno=parseInt(document.getElementById(ele).innerHTML);
var ele="proid"+id;
var proid=document.getElementById(ele).value;
var ele="payschid"+id;
var payschid=document.getElementById(ele).value;
var ele="builderid"+id;
var builderid=document.getElementById(ele).value;

//var ele="o"+id;
//var iddd=document.getElementById(ele).value;




var comptype=0;
		if(compname=="FIXED")
		{
			comptype=1;
		}


if(compname !="" && compvalue !="" && insname!="")
	{

				//Validating COMP_NAME_SELECTION
				var SimilarIds=new Array();
				
				for(var i=0; i<1000; i++)
					{
						 ele="ii"+i;
						try
						{
							
							var no=parseInt(document.getElementById(ele).value);
					        

							if(insno==no)
							{
							
								SimilarIds.push(i);
							}

							
						}
						catch(exception)
						{
						}

					}
//alert(SimilarIds);
				//***END
				   
							var val='';
							var ChkArr=new Array();
				var lst=0;
			
							  for(var i=0; i<SimilarIds.length; i++)
								{
								   ele=SimilarIds[i];
								    lst=ele;
								   ele="jj"+ele;
								   val=document.getElementById(ele).value;
								   ChkArr.push(val);
                                  
								}
                                  lst=lst+1;
								 
								   ele="ccompname"+lst;
								   //alert(ele);
								   val=document.getElementById(ele).value;
								   
								   ChkArr.push(val);


//alert(ChkArr.toString());
			 			 if(arrHasDupes(ChkArr)==false)
						 {

      

                                    xmlHttpEditINV=GetXmlHttpObject();
									
									
									var url="ajax/invoice_edit.php?act=save&insno="+insno+"&insname="+insname+"&compname="+compname+"&comment="+comment+"&compvlu="+compvalue+"&comptype="+comptype+"&proid="+proid+"&payschid="+payschid+"&builderid="+builderid;
									xmlHttpEditINV.onreadystatechange=StateChangedForINV;
									xmlHttpEditINV.open("GET",url,true);

								   xmlHttpEditINV.send(null);
						//		   ldr='stts'+id;
						 }
						 else
						{
							  alert("Component Name for this installment can not be same!");
						}
	}
	else
	{
		 wc++;
			 if(wc>1)
			{
		 alert("Please Enter values in all field.");
			}
	}

	
}



function StateChangedForINV()
{
	if (xmlHttpEditINV.readyState==4)
		{
			//alert(xmlHttpEditINV.responseText);
			invoice_edit();
		}

}

var ProIdEdt=0;
var ldr='';
function performINVEdit(id,projectid)
{
	 
	    var ele="ii"+id;
var insno=document.getElementById(ele).style.display="";
var insnovlu=document.getElementById(ele).value;
//performINVEditAction(id,projectid)
 ldr='sttss'+insnovlu;

var SimilarIds=new Array();
		for(var i=0; i<1000; i++)
			{
				 ele="ii"+i;
				try
				{

					var no=document.getElementById(ele).value;
					
					if(insnovlu==no)
					{
						
						//SimilarIds.push(i);
						
						performINVEditAction(i,projectid);
					}

					
				}
				catch(exception)
				{
					break;
				}

			}



//end
}

var hasCVError=true;
var lastMsg="";
function performINVEditAction(id,projectid)
{
	if(this.hasCVError==true)
	{
	
  if(doValidateCV()==true)
	{

		var ele="ii"+id;
		var insno=document.getElementById(ele).style.display="";
		var insnovlu=document.getElementById(ele).value;


		var ele="jj"+id;
var compnamevlu=document.getElementById(ele).value;

		var ele="kk"+id;
var compvlu=document.getElementById(ele).value;

		var ele="ll"+id;
var commentvlu=document.getElementById(ele).value;



		var ele="mm"+id;
var insnamevlu=document.getElementById(ele).value;

	var ele="pid"+id;
	
var idd=document.getElementById(ele).value;


var ele="proid"+id;
var projid=document.getElementById(ele).value;

ProIdEdt=projectid;

		var ele="stts1"+id;
		//ldr=document.getElementById(ele);

//Validating COMP_NAME_SELECTION
var SimilarIds=new Array();
		for(var i=0; i<1000; i++)
	{
				 ele="ii"+i;
		try
		{
			
			var no=document.getElementById(ele).value;
			
			if(insnovlu==no)
			{
				
				SimilarIds.push(i);
			}

			
		}
		catch(exception)
		{
		}

	}


	

//***END
   
   if(insnamevlu=="" || commentvlu=="" || compnamevlu=="" || compvlu=="")
	{
      alert("Please Enter values in all field.");
	}
	else
	{
	
		
			var val='';
			var ChkArr=new Array();
			  for(var i=0; i<SimilarIds.length; i++)
				{
				   ele=SimilarIds[i];
						   ele="jj"+ele;
				   val=document.getElementById(ele).value;
				   ChkArr.push(val);

				}




	     if(arrHasDupes(ChkArr)==false)
		 {

				xmlHttpEditINVEDT=GetXmlHttpObject();
				
				var comptype=0;
				if(compnamevlu=="FIXED")
				{
					comptype=1;

				}
 						else
						 {
							if(compnamevlu > 100)
							 {
								alert("The maximum value can be 100.");
								return;
							 }
						 }


			var url="ajax/invoice_edit.php?act=edit&compname="+compnamevlu+"&compvlu="+compvlu+"&comptype="+comptype+"&comment="+commentvlu+"&installname="+insnamevlu+"&id="+idd+"&insnum="+insnovlu+"&projid="+projid;
		   //alert(url);
					xmlHttpEditINVEDT.onreadystatechange=StateChangedForINVEDT;
					xmlHttpEditINVEDT.open("GET",url,true);

				   xmlHttpEditINVEDT.send(null);
						 
						   //alert(ldr);
				  //var result=xmlHttpEditP.responseText;
		 }
		 else
		{
			
			 alert("Component Name for this installment can not be same!");
			
		}
				
	}
	}


}
	else
	{
		//alert(lastMsg);
}
}




function StateChangedForINVEDT()
{
		
	document.getElementById(ldr).style.display="";
    document.getElementById(ldr).src="images/small_loader.gif";


   if(xmlHttpEditINVEDT.readyState==4)
	{
	  // alert(xmlHttpEditINVEDT.responseText);


	  
	   //
	  setTimeout("hideLoader('"+ldr+"')",5000);

	
	
	}
        
}






function make_sync(nm,elem)
{
	//alert(nm+"-"+elem);
    var ToSelect=elem.selectedIndex;

	var ele=document.getElementsByName(nm);
    var len= ele.length;
	for(var i=0;i<len;i++)
	{
           ele[i].selectedIndex=ToSelect;
	}
}

function invoice_edit()
{
	
		var pidchk	=	document.getElementById("projectcngf").value;	
		if(pidchk == '')
			alert("Please select project");
		else
			
			document.getElementById("l2").style.display="";
			document.getElementById("l1").style.display="";
			invoicefun_edit(pidchk);
            commission_edit();




}



function invoicefun_edit(projectid)
	{
		
		
		
			xmlHttppINE=GetXmlHttpObject()
		if (xmlHttppINE==null)
		{
			alert ("Browser does not support HTTP Request")
			return
		}
		
		var url="ajax/invoice_edit.php?&projectId="+projectid;
		
		xmlHttppINE.open("GET",url,false);
		xmlHttppINE.send(null);
		var pricingresult=xmlHttppINE.responseText;
		
		if(pricingresult)
		{
		
			document.getElementById('payschdivedit').innerHTML=xmlHttppINE.responseText;
		

		}
		else
		{
			alert("Please select the project");
		}
	}
	
	

var ProIDdelete=0;
function performInvDelete(id,proid)
{
	
	
	this.ProIDdelete=proid;
	var cfrm=confirm("Do you want to delete this record?");
	if(cfrm)
	{
			xmlHttpEditINVD=GetXmlHttpObject();
			var url="ajax/invoice_edit.php?act=delete&id="+id;
					xmlHttpEditINVD.onreadystatechange=StateChangedForINVDelete;
					xmlHttpEditINVD.open("GET",url,true);

				   xmlHttpEditINVD.send(null);
	}

	
}


function StateChangedForINVDelete()
{
   if(xmlHttpEditINVD.readyState==4)
	{
	   invoicefun_edit(ProIDdelete);
	
	}
        
}








function performInvAdd()
{

var msg=document.getElementById("amsg");

if(msg.innerHTML=="[+] Add More Invoices")
	{
	msg.innerHTML="[-] Add More Invoices";
		document.getElementById("payschdivadd").style.display="";

var idn='';
var vlu='';
//problem
var projId=1361;
    for(var i=0;i<1000;i++)
	{
		try
		{
			
	  idn="i"+i;
	  vlu=document.getElementById(idn).value;
	  
		}
		catch(exception)
		{
		}

	}

vlu=parseInt(vlu);
vlu=vlu+1;

var proj=document.getElementById("projectcngf").value;
var ins=document.getElementById("installnoModify").value;
			var exid=document.getElementById("exid").value;
	                               xmlHttpEditINVADD=GetXmlHttpObject();
								
												var url="ajax/invoice_add.php?projectid="+proj+"&ins="+ins+"&exid="+exid;
									xmlHttpEditINVADD.onreadystatechange=StateChangedForINVADD;
									xmlHttpEditINVADD.open("GET",url,true);

								   xmlHttpEditINVADD.send(null);


}
	else
	{
			msg.innerHTML="[+] Add More Invoices"
			document.getElementById("payschdivadd").style.display="none";
	}
}




function StateChangedForINVADD()
{
    if(xmlHttpEditINVADD.readyState==4)
	{
		
         document.getElementById("payschdivadd").innerHTML=xmlHttpEditINVADD.responseText;
		 //document.getElementById("addLink").style.display="none";
	}
}







/*****************************END*************************/




/*********************EDIT COMMISION **************************/
//p98
    function commisionfun_edit(projectid)
	{
		
		
		
			xmlHttppaysch3=GetXmlHttpObject()
		if (xmlHttppaysch3==null)
		{
			alert ("Browser does not support HTTP Request")
			return
		}
		
		var url="ajax/commission_edit.php?&projectId="+projectid;
		
		xmlHttppaysch3.open("GET",url,false);
		xmlHttppaysch3.send(null);
		var pricingresult=xmlHttppaysch3.responseText;
		
		if(pricingresult)
		{
		
			document.getElementById('invc').innerHTML=xmlHttppaysch3.responseText;
			document.getElementById("amsg").style.display="";
		

		}
		else
		{
			alert("Please select project");
		}
	}
	
	


function commission_edit()
{
	
		var pidchk	=	document.getElementById("projectcngf").value;	
		if(pidchk == '')
			alert("Please select project");
		else
			commisionfun_edit(pidchk);


}

function StateChangedForComEdit()
{
}


var CompProID=0;
var CompRwID=0;
function addEditRowCs(id)
{

	
	var ele="compname"+id;
var compname=document.getElementById(ele).style.display="";

	//var ele="comptype"+id;
//var comptype=document.getElementById(ele).style.display="";

	var ele="compvalue"+id;
var compvalue=document.getElementById(ele).style.display="";


	var ele="comment"+id;
var comment=document.getElementById(ele).style.display="";


	var ele="actimg"+id;
var actimg=document.getElementById(ele).src="images/saveIcon.gif";

document.getElementById(ele).addEventListener("onclick",performNewAddPc,false);


	var ele="proid"+id;
var pid=document.getElementById(ele).value;

this.CompRwID=id;
this.CompProID=pid;

performNewAddPc();

}



function performPcEdit(id)
{

if(doValidateCV()==true)
	{

	var ele="i"+id;
var compname=document.getElementById(ele).style.display="";
var compnamevlu=document.getElementById(ele).value;
	var ele="j"+id;
var compvalue=document.getElementById(ele).style.display="";
var compvaluevlu=document.getElementById(ele).value;

	var ele="k"+id;
var comment=document.getElementById(ele).style.display="";
var commentvlu=document.getElementById(ele).value;

	var ele="stts"+id;
var stts=document.getElementById(ele);//.style.display="";


	var ele="id"+id;

var idd=document.getElementById(ele).value;
	


			if(compnamevlu=="" || compvaluevlu=="" || commentvlu=="")
			{
				alert("Please Enter/Select all Data");
			}
			else
	        {
				
                   	  
				//Validating COMP_NAME_SELECTION
				var ChkArr=new Array();
				var e=0;
							  for(var i=0; i<500; i++)
								{
								   try
								   {
									
								  
									   ele="i"+i;
									   val=document.getElementById(ele).value;
									   e=i;
									 
									   if(val !="Select" && val !="" && val !=" ")
									   {
									   ChkArr.push(val);
									   }
								    }
								   catch(exception)
								   {
									   //ign
									   break;
								   }
                                  
								}
								       //e=e+1;

                                       //ele="compname"+e;
									   //val=document.getElementById(ele).value;
     								   // ChkArr.push(val);


                                  
								 



						 if(arrHasDupes(ChkArr)==false)
						 {


								stts.style.display='';
							   xmlHttpEditPc=GetXmlHttpObject();
								
								var comptype=0;
								if(compnamevlu=="FIXED")
								{
									comptype=1;
								}
									else
								 {
									  if(compvaluevlu > 101 )
									 {
										  alert("The maximum value can be 100.");
										  return;

									 }
								 }


								var url="ajax/commission_edit.php?act=edit&compname="+compnamevlu+"&compvlu="+compvaluevlu+"&comment="+commentvlu+"&id="+idd;
								//alert(url);
									xmlHttpEditPc.onreadystatechange=StateChangedForPc;
									xmlHttpEditPc.open("GET",url,true);

								   xmlHttpEditPc.send(null);

						 }
						 else
							{
							 alert("Component Name can not be same.");
							}
	       }
	}

}

function performPcDelete(id)
{


	var c=confirm("Do you want to delete this record?");

	if(c==true)
	{
                xmlHttpEditPd=GetXmlHttpObject();
				

				var url="ajax/commission_edit.php?act=delete&id="+id;
				//alert(url);
					xmlHttpEditPd.onreadystatechange=StateChangedForPd;
					xmlHttpEditPd.open("GET",url,true);

				   xmlHttpEditPd.send(null);

	}

}



var cnt=0;

function performNewAddPc()
{


var id=CompRwID;
var pid=CompProID;


var ele="compname"+id;
var compname=document.getElementById(ele).style.display="";
var compnamevlu=document.getElementById(ele).value;
	var ele="compvalue"+id;
var compvalue=document.getElementById(ele).style.display="";
var compvaluevlu=document.getElementById(ele).value;

	var ele="comment"+id;
var comment=document.getElementById(ele).style.display="";
var commentvlu=document.getElementById(ele).value;

if(cnt>0)
	{
		if(compnamevlu !="" && compvaluevlu !="" && commentvlu !="")
		{


             	  
				//Validating COMP_NAME_SELECTION
				var ChkArr=new Array();
				var e=0;
							  for(var i=0; i<500; i++)
								{
								   try
								   {
									
								  
									   ele="i"+i;
									   val=document.getElementById(ele).value;
									   e=i;
									 
									   if(val !="Select" && val !="" && val !=" ")
									   {
									   ChkArr.push(val);
									   }
								    }
								   catch(exception)
								   {
									   //ign
									   break;
								   }
                                  
								}
								       e=e+1;

                                       ele="compname"+e;
									   val=document.getElementById(ele).value;
     								   ChkArr.push(val);


                                  
								 



						 if(arrHasDupes(ChkArr)==false)
						 {

						



											var comptype=0;
											if(compnamevlu=="FIXED")
											{
												comptype=1;
											}
											else
										 {
											  if(compvaluevlu > 101 )
											 {
												  alert("The maximum value can be 100.");
													return;
											 }
										 }

								   xmlHttpEditPNS=GetXmlHttpObject();
								   var url="ajax/commission_edit.php?act=newsave&comment="+commentvlu+"&compname="+compnamevlu+"&compvlu="+compvaluevlu+"&comptype="+comptype+"&id="+pid;
								   xmlHttpEditPNS.onreadystatechange=StateChangedForPNS;
								   xmlHttpEditPNS.open("GET",url,true);
								   xmlHttpEditPNS.send(null);
						 }
						 else
						{
							 alert("Component Name can not be same.");
						}

		}
		else
		{
			alert("Plese Enter/Select Required Data");
		}
cnt=0;
	}
	else
	{
		cnt++;
	}
	
}


function StateChangedForPNS()
{
	if (xmlHttpEditPNS.readyState==4)
		{
			//alert(xmlHttpEditPNS.responseText);
			commission_edit();
		}

}

function StateChangedForPc()
{
	if (xmlHttpEditPc.readyState==4)
		{
			//alert(xmlHttpEditPc.responseText);
			commission_edit();
		}

}

function StateChangedForPd()
{
	if (xmlHttpEditPd.readyState==4)
		{
			//alert(xmlHttpEditPd.responseText);
			commission_edit();
		}
		
}
/************************END*********************************/





	/************************invoice ajax**************************/

	 function invoice_projectcngfun()
	{

		var pidchk	=	document.getElementById("projectcngf").value;	
		if(pidchk == '')
			alert("Please select project");
		else
			projectcngfun_invoice(pidchk);
	}
	function projectcngfun_invoice(projectid)
	{
		
		var projectid	=	document.getElementById("projectcngf").value;
		
			xmlHttpinvoice=GetXmlHttpObject()
		if (xmlHttpinvoice==null)
		{
			alert ("Browser does not support HTTP Request")
			return
		}
		
		var installmentno	=	document.getElementById("installmentno").value;
		var url="ajax/invoice_detail.php?&projectid="+projectid+"&installno="+installmentno;
		
		xmlHttpinvoice.open("GET",url,false);
		xmlHttpinvoice.send(null);
		var pricingresult=xmlHttpinvoice.responseText;
		//alert(xmlHttpprice.responseText);
	   // return pricingresult;
		if(pricingresult)
		{
			
			document.getElementById('invoicediv').innerHTML=xmlHttpinvoice.responseText;
			//invoice_chngsubmit();

		}
		else
		{
			alert("Please select project");
		}
	}
	

	function invoice_chngsubmit()
{



	invoice_pselect = $("#pselect").val();

	if(invoice_pselect == 'pselect')
	{
		document.getElementById("invoice_chngsubmit").innerHTML = "<input type='button' name='btnSave' id='btnSave' value='Update' onclick='return invoice_submit_frm();'>";
		
	}
	else
	{
		document.getElementById("invoice_chngsubmit").innerHTML = "<input type='button' name='btnSave' id='btnSave' value='Save' onclick='return invoice_submit_frm();'>";
	}

}

	/************************End payment schedule ajax************************/


	function deletehistory(priceid,projectid)
	{
		
			xmlHttp=GetXmlHttpObject()
		if (xmlHttp==null)
		{
			alert ("Browser does not support HTTP Request")
			return
		}
		
		var url="ajax/historyDeleteServer.php?priceid="+priceid+"&projectid="+projectid;
		//alert(url);
		xmlHttp.onreadystatechange=stateChanged
		xmlHttp.open("GET",url,true)
		xmlHttp.send(null)
	}
	function stateChanged()
	{
				//ajax-loader
		//document.getElementById('historydiv').innerHTML = "<img src='images/ajax-loader.gif'>";
		if (xmlHttp.readyState==4)
		{
			
		//alert(xmlHttp.responseText);
		
			document.getElementById('historydiv').innerHTML=xmlHttp.responseText;
		
		}
	}

	/**********************function for refresh project in order search according builder change***********************/
	function refreshprojectlist(builderid)
	{
		//alert("here"+builderid);
		
			xmlHttpord=GetXmlHttpObject()
		if (xmlHttpord==null)
		{
			alert ("Browser does not support HTTP Request")
			return
		}
		
		var url="ajax/projectrefresh.php?builderid="+builderid;
		//alert(url);
		xmlHttpord.onreadystatechange=stateChangedProject
		xmlHttpord.open("GET",url,true)
		xmlHttpord.send(null)
	}
	function stateChangedProject()
	{
		
				//ajax-loader
		//document.getElementById('projectname').innerHTML = "<img src='images/ajax-loader.gif'>";
		
		if (xmlHttpord.readyState==4)
		{
			//alert(xmlHttpord.responseText);
			var resp = xmlHttpord.responseText;
			
			if(resp.search("gout")>0){
				window.location = "index.php";
			}
			
			document.getElementById('projectname').innerHTML=xmlHttpord.responseText;
			
		
		}
	}
	

	/**************function for project refresh in project image management according builder change***********************/
	function refreshprojectlistImage(builderid)
	{
		//alert("here"+builderid);
			xmlHttpImg=GetXmlHttpObject()
		if (xmlHttpImg==null)
		{
			alert ("Browser does not support HTTP Request")
			return
		}
		
		var url="ajax/projectrefresh.php?builderid="+builderid;
		//alert(url);
		xmlHttpImg.onreadystatechange=stateChanged
		xmlHttpImg.open("GET",url,true)
		xmlHttpImg.send(null)
	}
	function stateChanged()
	{
		
				//ajax-loader
		//document.getElementById('projectname').innerHTML = "<img src='images/ajax-loader.gif'>";
		if (xmlHttpImg.readyState==4)
		{
			
		//alert(xmlHttpord.responseText);
		
			document.getElementById('projectname').innerHTML=xmlHttpImg.responseText;
		
		}
	}



	/*******************project drop down for verify detail***************************/
	function project_refresh_verify(builderid)
	{
		//alert("here"+builderid);
		xmlHttpbuilder_verify=GetXmlHttpObject()
		var url="ajax/project_list_verify.php?&builderid="+builderid;
		//alert(url);
		xmlHttpbuilder_verify.open("GET",url,false);
		xmlHttpbuilder_verify.send(null);
		var pricingresult_verify=xmlHttpbuilder_verify.responseText;
		if(pricingresult_verify)
		{
			
			document.getElementById('projectdiv_verify').innerHTML=xmlHttpbuilder_verify.responseText;
			
		}
		
	}
	
	/*************project type and area drop down refresh for verify detail**************************/
	function type_refresh_verify(projectid)
	{
		//alert("here"+builderid);
		xmlHttpproject_verify=GetXmlHttpObject()
		var url="ajax/project_type_verify.php?&projectid="+projectid;
		//alert(url);
		xmlHttpproject_verify.open("GET",url,false);
		xmlHttpproject_verify.send(null);
		var pricingresult_verify=xmlHttpproject_verify.responseText;
		if(pricingresult_verify)
		{
			
			document.getElementById('type_area_rate_verify').innerHTML=xmlHttpproject_verify.responseText;
			
		}
		
	}

	/*************Direct adjustment and cashback list refresh for verify detail**************************/
	function direct_cash_list(projectid)
	{

		xmlHttpproject_verifyDirectCash=GetXmlHttpObject()
		var url="ajax/directCashList.php?&projectid="+projectid;
		//alert(url);
		xmlHttpproject_verifyDirectCash.open("GET",url,false);
		xmlHttpproject_verifyDirectCash.send(null);
		var pricingresult_verify=xmlHttpproject_verifyDirectCash.responseText;
		if(pricingresult_verify)
		{
			try
			{
				
			document.getElementById('directCashList').innerHTML=xmlHttpproject_verifyDirectCash.responseText;
			}
			catch(exception)
			{
			}
			
		}
		
	//alert("here"+pid);
	}

function textarea_text_fill(val)
{
	//alert(val+"here");
	var mySplit	= val.split("@");
	document.getElementById("txtarea_text").value = mySplit[0];


	var bspAmount	=	(document.getElementById("txtrate_text").value)*(document.getElementById("txtarea_text").value);
	
	document.getElementById("txtbspamnt").value = bspAmount;
}



/**************bsp rate change*******************/
function bsprate(val,param)
{

//	alert("here");
	if(param == 'oncng')
	{
		
		document.getElementById("txtrate_text").value = document.getElementById("txtrate").value;
	}

	var txtarea1		=	document.getElementById("txttype").value;

	var mySplitResult	= txtarea1.split("@");

	var txtarea			=	mySplitResult[0];
	var txtarea_text	=	document.getElementById("txtarea_text").value;

	var txtrate			=	document.getElementById("txtrate").value;
	var txtrate_text	=	document.getElementById("txtrate_text").value;

	var area	=	'';
	var rate	=	'';
	//alert(txtarea_text+"before"+txtarea);
	if(txtarea_text != '')
	{
		area	=	txtarea_text;
	}
	else
	{
		area	=	txtarea;
	}

	if(txtrate_text != '')
	{
		rate	=	txtrate_text;
	}
	else
	{
		rate	=	txtrate;
	}
//alert(txtrate+"after");
	var bspAmount	=	rate*area;
	//alert(param);
	
	document.getElementById("txtbspamnt").value = bspAmount;

//txtrate_text

		document.getElementById("proselected").value = document.getElementById("txtProject").value;
		document.getElementById("proarea").value	= area;
		document.getElementById("prorate").value	= rate;
		document.getElementById("protype").value	= document.getElementById("txttype").value;


	//alert(txtarea+"------"+txtarea_text+"-----"+txtrate+"-----"+txtrate_text);
}

	/************function for set monthly target row update*****************/
	function refreshRow(levelid,Id,execchk)
	{
		//alert("here---"+levelid+"----"+Id);
		xmlHttpRowUpdate=GetXmlHttpObject();
		var url="ajax/targetSetRow.php?&levelid="+levelid;
		//alert(url);
		xmlHttpRowUpdate.open("GET",url,false);
		xmlHttpRowUpdate.send(null);
		var rowupdated=xmlHttpRowUpdate.responseText;
		if(rowupdated)
		{
			//alert(rowupdated);
			document.getElementById('masterTblData').innerHTML=xmlHttpRowUpdate.responseText;
			var val	=	document.getElementById('masterTblData').innerHTML;
			//alert(val);
			var valInArr	=	val.split("#");

				if(execchk == 4 || execchk == 3)//if executive or Sr Executive then update value
				{
					var baseid	=	Id+"base";
					document.getElementById(baseid).value = valInArr[0];

					var targetid	=	Id+"target";
					document.getElementById(targetid).value = valInArr[1];

					var jackpotid	=	Id+"jackpot";
					document.getElementById(jackpotid).value = valInArr[2];

					var super_jackpotid	=	Id+"super_jackpot";
					document.getElementById(super_jackpotid).value = valInArr[3];

					var bumper_jackpotid	=	Id+"bumper_jackpot";
					document.getElementById(bumper_jackpotid).value = valInArr[4];
				}

				var inc_baseid	=	Id+"inc_base";
				document.getElementById(inc_baseid).value = valInArr[5];

				var inc_targetid	=	Id+"inc_target";
				document.getElementById(inc_targetid).value = valInArr[6];

				var inc_jackpotid	=	Id+"inc_jackpot";
				document.getElementById(inc_jackpotid).value = valInArr[7];

				var inc_super_jackpotid	=	Id+"inc_super_jackpot";
				document.getElementById(inc_super_jackpotid).value = valInArr[8];

				var inc_bumper_jackpotid	=	Id+"inc_bumper_jackpot";
				document.getElementById(inc_bumper_jackpotid).value = valInArr[9];
				
					//calculateTargetSum1(execchk,0);
		}
	}

	/************Function for refresh heirarchy list **************/

function refreshHierarchyList(userId)
{

		xmlHttpHeirarchyList=GetXmlHttpObject()
		var url="ajax/GetHierarchyList.php?&userid="+userId;

		xmlHttpHeirarchyList.open("GET",url,false);
		xmlHttpHeirarchyList.send(null);
		var pricingresult_verify=xmlHttpHeirarchyList.responseText;
		if(pricingresult_verify)
		{
			var ManagerList = xmlHttpHeirarchyList.responseText;
				ManagerList = ManagerList.split(',');
		//alert(ManagerList);

			document.getElementById("txtHeirarchy2_1").selectedIndex = '';
			document.getElementById("txtHeirarchy3_1").selectedIndex = '';
			document.getElementById("txtHeirarchy4_1").selectedIndex = '';
			document.getElementById('txtExecutive1_1').value = '';
			document.getElementById('txtExecutive2_1').value = '';
			document.getElementById('txtExecutive3_1').value = '';
			document.getElementById('txtExecutive4_1').value = '';

		var ele=document.getElementById('txtHeirarchy2_1');
		
		var vlu=(ManagerList[0].split("@"))[0];
		vlu=parseInt(vlu);

		for(var i=0;i<ele.length;i++)
		{
			if(ele[i].value==vlu)
			{
				ele[i].selected=true;
				break;
			}
			else
			{
				ele[i].selected=false;
			}
		}
		document.getElementById('txtExecutive1_1').value = '100';

		document.getElementById('txtExecutive2_1').value = '100';

	if(ManagerList[1] != '')
	{
		var ele=document.getElementById('txtHeirarchy3_1');
				var vlu=(ManagerList[1].split("@"))[0];
				vlu=parseInt(vlu);

				for(var i=0;i<ele.length;i++)
				{
					if(ele[i].value==vlu)
					{
						ele[i].selected=true;
						break;
					}
					else
					{
						ele[i].selected=false;
					}
					}

					document.getElementById('txtExecutive3_1').value = '100';
}

	if(ManagerList[2] != '')
	{
	var ele=document.getElementById('txtHeirarchy4_1');
			var vlu=(ManagerList[2].split("@"))[0];
			vlu=parseInt(vlu);

			for(var i=0;i<ele.length;i++)
			{
				if(ele[i].value==vlu)
				{
					ele[i].selected=true;
					break;
				}
				else
				{
					ele[i].selected=false;
				}
			}

			document.getElementById('txtExecutive4_1').value = '100';
	}

			
}
}



/**********Function for discount in target*****************/
function calculateDiscount(id,levelId)//id for mgr,sr mgr,sr exec or exec id and levelid for which level user exist
{
	var discountId			=	id+"discount";
	var DisVal	=	document.getElementById(discountId).value;
	if(DisVal > 100)
	{
			alert("Discount can not be greater then 100");
			document.getElementById(discountId).focus();
			return false;
	}
	else
	{
		var discount			=	document.getElementById(discountId).value;
		var baseId				=	id+"base";
		document.getElementById(baseId).value	= ( (document.getElementById(baseId).value) - (document.getElementById(baseId).value)*(discount/100));
		//var base				=	document.getElementById(baseId).value;
		var targetId			=	id+"target";
		document.getElementById(targetId).value	=  ((document.getElementById(targetId).value) - (document.getElementById(targetId).value)*(discount/100));
		var target				=	document.getElementById(targetId).value;
		var jackpotId			=	id+"jackpot";
		document.getElementById(jackpotId).value	=  ((document.getElementById(jackpotId).value) - (document.getElementById(jackpotId).value)*(discount/100));
		var jackpot				=	document.getElementById(jackpotId).value;
		var super_jackpotId		=	id+"super_jackpot";
		document.getElementById(super_jackpotId).value	= ((document.getElementById(super_jackpotId).value) - (document.getElementById(super_jackpotId).value)*(discount/100));
		var super_jackpot		=	document.getElementById(super_jackpotId).value;
		var bumper_jackpotId	=	id+"bumper_jackpot";
		document.getElementById(bumper_jackpotId).value	= ((document.getElementById(bumper_jackpotId).value) - (document.getElementById(bumper_jackpotId).value)*(discount/100));
		//var bumper_jackpot		=	document.getElementById(bumper_jackpotId).value;
		calculateTargetSum1(levelId,0);
	}
}
/**********End Function for discount in target************/

function resetme()
{
	document.myfrm.reset();
	calculateTargetSum1(0,0);
}

var curpos = 0;

/*********Funnction for calculate target *****************/
// curpos for employee level and  
// targetSlabs take values like 0,1,2,3,4,5 - 0 for default calling,1 for base, 2 for target etc..
function calculateTargetSum1(curpos,targetSlabs) 
{
	//alert("here"+curpos);
	
	var RepLevel = Array("Exec_","SrExec_","Mgr_","SrMngr_");
	var arrTargetLevel = Array("base","target","jackpot","super_jackpot","bumper_jackpot");

	if(targetSlabs==1)	{ var arrTargetLevel = Array("base"); }
	else if(targetSlabs==2)	{ var arrTargetLevel = Array("target"); }
	else if(targetSlabs==3)	{ var arrTargetLevel = Array("jackpot"); }
	else if(targetSlabs==4)	{ var arrTargetLevel = Array("super_jackpot"); }
	else if(targetSlabs==5)	{ var arrTargetLevel = Array("bumper_jackpot"); }
	else { var arrTargetLevel = Array("base","target","jackpot","super_jackpot","bumper_jackpot"); }


	if(curpos==1)
	{
		return false;
		//var RepLevel = Array("SrExec_","Mgr_","SrMngr_");
		//var arrTargetLevel = Array("base","target","jackpot","super_jackpot","bumper_jackpot");
	}
	else if(curpos==2)
	{
		var RepLevel = Array("Mgr_","SrMngr_");		
	}
	else if(curpos==3)
	{
		var RepLevel =  Array("SrExec_","Mgr_","SrMngr_");		
	}
	else if(curpos==4 || curpos==0)
	{
		var RepLevel = Array("Exec_","SrExec_","Mgr_","SrMngr_");
	}

	var len1 = RepLevel.length;
	var len2 = arrTargetLevel.length;
	

	for(var rp=0;rp<len1;rp++)
	{
		for(var tl=0;tl<len2;tl++)
		{
			var arr_srmgr = Array();
			var arr_mgr = Array();
			var arr_srexec = Array();
			var localarr = Array();
			var mgrid = '';
			
			$("[name^="+RepLevel[rp]+"]").each(function(get_sr_exe){
				var getsrexec = $(this).attr('name');				
				localarr = getsrexec.split('_');
				mgrid = parseInt(localarr[1]);
				arr_srexec_id = parseInt(localarr[2]);

				var x = 0;
				classname = mgrid+arrTargetLevel[tl];
				//alert(classname);
				 $('.'+classname).each(
					function(index) 
					{
						x+=parseInt(this.value);
						$('#'+mgrid+arrTargetLevel[tl]).val(x);
					}			 
				);
				
			});
		}
	}		
}
/*********End Funnction for calculate target *****************/

/******************change status of payment in costumer to builder*************************/

var IdDiv = 0;
	function PayStatusChange(InvoiceId)
	{

		IdDiv	=	InvoiceId;
		xmlHttpPayStatus=GetXmlHttpObject()
		if (xmlHttpPayStatus==null)
		{
			alert ("Browser does not support HTTP Request")
			return
		}
		
		var url="ajax/RefreshPayStatus.php?InvoiceId="+InvoiceId;
		//alert(url);
		xmlHttpPayStatus.onreadystatechange=stateChangedchk
		xmlHttpPayStatus.open("GET",url,true)
		xmlHttpPayStatus.send(null)
	}
	function stateChangedchk()
	{
	
		if (xmlHttpPayStatus.readyState==4)
		{
		//alert("here");
			document.getElementById('PayStatusChange'+IdDiv).innerHTML=xmlHttpPayStatus.responseText;
		
		}
	}

	/*******************End Ajax Code*************/




/******************TRICKS****************/

//for hidding paysch_add_first_row

function callTrick()
{
   try
   {

	if(document.getElementById("insno0").style.display="none")
	{
      document.getElementById("firstrow").style.display="none"
	}
}
   catch(e)
   {
   }

}


//for validating Invoice-CUM-Commission page 
function array_unique(ar)
{
var sorter = {};
for(var i=0,j=ar.length;i<j;i++)
	{
        sorter[ar[i]] = ar[i];
    }
ar = [];
for(var i in sorter){
ar.push(i);
}
return ar;
}


function doValidateCV()
{

	var SimilarIds=new Array();
					for(var i=0; i<1000; i++)
					{
						var ele="jj"+i;
						try
						{
							
							var no=document.getElementById(ele).value;
							SimilarIds[i]=no;
							
						}
						catch(exception)
						{
							//ignore...
						}

					}


					var unique = array_unique(SimilarIds);
					
                     var KeyHasID=new Array();
					 var str="";
					for(var i=0; i<unique.length; i++)
					{
                         for(var j=0; j<SimilarIds.length; j++)
						{
							
							 if(SimilarIds[j]==unique[i])
							 {
								
								str+=j+",";
								 		
							 }
						}
     						var k=unique[i];
							KeyHasID[k]=str;
						
							str="";
					}


                    var FinalData=new Array();
			        for(key in KeyHasID)
					{
						
						var k=KeyHasID[key];
						
						try
						{
							k=k.split(",");
                             var v=0;
							for(var m=0;m<k.length;m++)
							{
								
   						        if(k[m] >=0)
								{
                                    var ele="kk"+k[m];
									var valu=document.getElementById(ele).value;
									if(valu !="")
									{
									v=v+parseFloat(valu);
									}
									else
									{
									 alert("Please Enter component value of "+key);
									 return;
									}
									

								}
								
							}
							
							   
						}
						catch(e)
						{
							if(v>0)
							{
							
							 FinalData[key]=v;
							 v=0;
							}
						}
						
					}

                   var SimilarIds=new Array();
							for(var i=0; i<1000; i++)
							{
								var ele="i"+i;
								try
								{
									
									var no=document.getElementById(ele).value;
									
									SimilarIds[i]=no;
									
								}
								catch(exception)
								{
									//ignore...
								}

							}
                           
					

					for(var i=0;i<SimilarIds.length;i++)
	                {

							for(key in FinalData)
							{

					         			// alert(FinalData[key]+"=="+SimilarIds[i]);
							    if(key==SimilarIds[i])
								{
									var vlu=document.getElementById("j"+i).value;
									if(vlu !="")
									{
										if(FinalData[key] != vlu)
										{
											this.hasCVError=false;
											this.lastMsg="The sum of commission's "+key+" value should match with the sum of invoice value.";
											alert("The sum of commission's "+key+" value should match with the sum of invoice value.");
											return false;
										}
										else
										{
											this.hasCVError=true;
										}

										this.hasCVError=true;
									}
									else
									{
                                           alert("Please enter the component value of "+key);
									}
								}
							   
							}
					}


					return true;


}


var idfordivimg = 0;
function statuschangeImg(ImgId)
{
	idfordivimg	=	ImgId;
		xmlHttpim=GetXmlHttpObject()
	if (xmlHttpim==null)
	{
		alert ("Browser does not support HTTP Request")
		return
	}
	var url="RefreshBanStat.php?ImgId="+ImgId;
	//alert(url);
	xmlHttpim.onreadystatechange=stateChanged1
	xmlHttpim.open("GET",url,true)
	xmlHttpim.send(null)
}
function stateChanged1()
{

	if(xmlHttpim.readyState==4)
	{
		
		
		document.getElementById('statusRefreshImg'+idfordivimg).innerHTML=xmlHttpim.responseText;
	
	}
}

/*********function for delete image from project plan image table*******************/

function deleteImage(imgId,imgtype,projectid,builderid)
{

	var ret = chkConfirm();
	//alert(ret);
	if(ret == true)
	{
		xmlHttpimg=GetXmlHttpObject()
		var url="ajax/RefreshImageList.php?ImgId="+imgId+"&imgtype="+imgtype+"&projectid="+projectid+"&builderid="+builderid;
		//alert(url);
		xmlHttpimg.onreadystatechange=stateChangedImg
		xmlHttpimg.open("GET",url,true)
		xmlHttpimg.send(null)
	}
}
function stateChangedImg()
{

	
	document.getElementById('imageList').innerHTML="<img src='images/ajax-loader.gif'>";
	//alert(xmlHttpimg.readyState);
	if(xmlHttpimg.readyState==4)
	{
		//alert(xmlHttpimg.responseText);
		document.getElementById('imageList').innerHTML=xmlHttpimg.responseText;
	}
}

/**********************END****************/


/************function for set monthly target row update*****************/
	
	function refreshRowForEditSetTarget(levelid,Id,execchk)	{

		xmlHttpRowUpdate=GetXmlHttpObject();
		var url="ajax/targetSetRow.php?&levelid="+levelid;
		xmlHttpRowUpdate.open("GET",url,false);
		xmlHttpRowUpdate.send(null);
		var rowupdated=xmlHttpRowUpdate.responseText;
		if(rowupdated)	{

			document.getElementById('masterTblData').innerHTML=xmlHttpRowUpdate.responseText;
			var val	=	document.getElementById('masterTblData').innerHTML;

			var valInArr	=	val.split("#");

				if(execchk == 4 || execchk == 3)//if executive or Sr Executive then update value
				{
					var baseid	=	Id+"base";
					document.getElementById(baseid).value = valInArr[0];

					var targetid	=	Id+"target";
					document.getElementById(targetid).value = valInArr[1];

					var jackpotid	=	Id+"jackpot";
					document.getElementById(jackpotid).value = valInArr[2];

					var super_jackpotid	=	Id+"super_jackpot";
					document.getElementById(super_jackpotid).value = valInArr[3];

					var bumper_jackpotid	=	Id+"bumper_jackpot";
					document.getElementById(bumper_jackpotid).value = valInArr[4];
				}

				var inc_baseid	=	Id+"inc_base";
				document.getElementById(inc_baseid).value = valInArr[5];

				var inc_targetid	=	Id+"inc_target";
				document.getElementById(inc_targetid).value = valInArr[6];

				var inc_jackpotid	=	Id+"inc_jackpot";
				document.getElementById(inc_jackpotid).value = valInArr[7];

				var inc_super_jackpotid	=	Id+"inc_super_jackpot";
				document.getElementById(inc_super_jackpotid).value = valInArr[8];

				var inc_bumper_jackpotid	=	Id+"inc_bumper_jackpot";
				document.getElementById(inc_bumper_jackpotid).value = valInArr[9];
				
		}
	}

	/*************function for cancel order*************/
	var rowid = '';
	function cancelInvoice(orderId,orderInvoiceId,installmentNo,orderNo,projectId,invoice_number,flg,rowcls)
	{
		if(flg == 'multi_delete')
		{
			var answer = confirm("Do you want to cancel invoice(s)?.");
		}
		else
		{
			var answer = confirm("Do you want to cancel invoice(s)?.");
		}
		if (answer)
		{
			//alert(orderInvoiceId);
			rowid = rowcls;
			xmlHttpCancel=GetXmlHttpObject()
			
			var url="ajax/ajax_cancel_invoice.php?orderId="+orderId+"&orderInvoiceId="+orderInvoiceId+"&installmentNo="+installmentNo+"&orderNo="+orderNo+"&projectId="+projectId+"&invoice_number="+invoice_number+"&flg="+flg;
			//alert(url);
			xmlHttpCancel.onreadystatechange=stateCancel
			xmlHttpCancel.open("GET",url,true)
			xmlHttpCancel.send(null)
		}
		else
		{
			return false;
		}
	}
		//alert(rowid);
	function stateCancel()
	{
		//alert(rowid+" here");
		//document.getElementById(rowid).innerHTML="<img src = 'images/ajax-loader_cancel.gif'>";
		//document.getElementsByClassName(rowid).innerHTML="<img src = 'images/ajax-loader_cancel.gif'>";
		$("."+rowid).html("<img src = 'images/ajax-loader_cancel.gif' width='20px' height='25px'>");
		if(xmlHttpCancel.readyState==4)
		{
			//alert(xmlHttpCancel.responseText);
			//document.getElementsByClassName(rowid).innerHTML=xmlHttpCancel.responseText;
			$("."+rowid).html(xmlHttpCancel.responseText);
			//document.getElementById(rowid).innerHTML=xmlHttpCancel.responseText;
		
		}
	}

	/***********function for tower refresh according project****************/

	var tower_ref_id = '';
	
	function refresh_tower(projectId,id)
	{
		tower_ref_id = "tower_refresh"+id;
		xmlHttptower=GetXmlHttpObject()
		var url="ajax/RefreshTower.php?projectid="+projectId+"&id="+id;
		//alert(url);
		xmlHttptower.onreadystatechange=stateChangedtower
		xmlHttptower.open("GET",url,true)
		xmlHttptower.send(null)
	
	}
	function stateChangedtower()
	{

		
		document.getElementById(tower_ref_id).innerHTML="<img src='images/ajax-loader.gif'>";
		//alert(xmlHttpimg.readyState);
		if(xmlHttptower.readyState==4)
		{
			//alert(xmlHttpimg.responseText);
			document.getElementById(tower_ref_id).innerHTML=xmlHttptower.responseText;
		}

		//document.getElementById("tower_id").value = document.getElementById("tower_id").value
		
	}