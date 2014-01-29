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
    define('skipUpdationCycle_Id', 15);
    $smarty->assign("skipUpdationCycle_Id", skipUpdationCycle_Id);
    
    /***********project status id define**************/
    define("UNDER_CONSTRUCTION_ID_1","1");   //Under Construction
    define("CANCELLED_ID_2","2"); //Cancelled
    define("OCCUPIED_ID_3","3"); //Occupied
    define("READY_FOR_POSSESSION_ID_4","4");  //Ready for Possession
    define("ON_HOLD_ID_5","5"); //On Hold
    define("NOT_LAUNCHED_ID_6","6"); //Not Launched
    define("LAUNCHED_ID_7","7"); //Launch
    define("PRE_LAUNCHED_ID_8","8"); //Pre Launch
?>
