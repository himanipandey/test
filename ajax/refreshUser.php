<?php
    session_start();
    include("../dbConfig.php");
    include("../appWideConfig.php");
    include("../builder_function.php");
    include("../modelsConfig.php"); 

    $team	= $_REQUEST['team'];
    $adminArr = ProptigerAdmin::find('all', array('conditions' => array("status = 'Y' and department = '".$team."'"),'order' => 'FNAME ASC'));
    ?>
        <select name='user' id='user' style='width:120px;'>
            <option value = "">Select User</option>
        <?php
            foreach ($adminArr as $obj) {
            ?>
               <option value = "<?php echo $obj->adminid; ?>"><?php echo $obj->fname; ?></option>
            <?php
            }
        ?>
        </select>
