<?php
    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("modelsConfig.php");
    include("includes/configs/configs.php");
    $optType = $_REQUEST['optType'];
    ?>
 <option value = "">Project Type</option>
<?php
    if(trim($optType) == "NonResidential"){   
    ?>
           
        <?php
            foreach ($arrCommercialType as $key=>$vComm ) {
            ?>
               <option value = "<?php echo $key; ?>"><?php echo ucfirst(strtolower($vComm)); ?></option>
            <?php
            }
        ?>
   <?php
    }else{
        ?>
        <?php
            foreach ($arrResidentialType as $key=>$vComm ) {
            ?>
               <option value = "<?php echo $key; ?>"><?php echo ucfirst(strtolower($vComm)); ?></option>
            <?php
            }
        ?>
        <?php
    }
   ?>