<?php
    /*************stages*******/
    define("NewProject_stage","NewProject");
    define("NoStage_stage","NoStage");
    define("SecondaryPriceCycle_stage","SecondaryPriceCycle");
    define("UpdationCycle_stage","UpdationCycle");
    
     /**************phases*******/
    define("Audit1_phase","Audit1");
    define("Audit2_phase","Audit2");
    define("Complete_phase","Complete");
    define("DataCollection_phase","DataCollection");
    define("DcCallCenter_phase","DcCallCenter");
    define("NewProject_phase","NewProject");
    define("NoStage_phase","NoStage");
    define("Revert_phase","Revert");
    /**************phases ids constant*******/
    define("phaseId_1","1");   //DataCollection
    define("phaseId_2","2"); //NewProject
    define("phaseId_3","3"); //DcCallCenter
    define("phaseId_4","4");  //Audit1
    define("phaseId_5","5"); //Audit2
    define("phaseId_6","6"); //Complete
    define("phaseId_7","7"); //NoStage
    define("phaseId_8","8"); //Revert
    
    
    define("NoStage_phase","NoStage");
    define("Revert_phase","Revert");
    
    /********skip updation cycle id define*****/
    define('skipUpdationCycle_Id', 13);
    $smarty->assign("skipUpdationCycle_Id", skipUpdationCycle_Id);
?>
