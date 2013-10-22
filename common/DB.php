<?php
/**
 * User: swapnil
 * Date: 7/6/13
 * Time: 11:49 PM
 * To change this template use File | Settings | File Templates.
 */

class Db
{
    private $Label;
    private $Host;
    private $User;
    private $Pass;
    private $DbNm;
    private $DbConn;
    private $Debug = FALSE;
    private $IsConnected = FALSE;


    public function Db($Label, $Host, $User, $Pass, $DbNm, $Debug = TRUE)
    {
        $this->Label = $Label;
        $this->Host  = $Host;
        $this->User  = $User;
        $this->Pass  = $Pass;
        $this->DbNm  = $DbNm;
        $this->Debug = $Debug;
    }

    private function Error_Log($Message, $Force = FALSE)
    {
        if ( $this->Debug || $Force === TRUE )
        {
            error_log("     " . $Message . "     ");
        }
    }

    private function Connect()
    {

        if ($this->DbConn !== NULL){
            if (mysql_ping($this->DbConn))
                    return;
            else{
                    $this->Error_Log('Disconnecting');
                    $this->Disconnect();
            }
        }

//      if ( $this->IsConnected ) return;

        $___ts = microtime(TRUE);
        $this->DbConn = mysql_connect($this->Host, $this->User, $this->Pass, TRUE);
        $___te = microtime(TRUE);

        if ( !$this->DbConn )
        {
            $this->Error_Log('ERROR::DB:: mysql_connect ' . $this->Label . ' ' . $this->Host . ' ' . $this->DbNm . ' ' . $this->User . ' ' . ($___te - $___ts) . 's' . " >> Error: " . mysql_error(), TRUE);
            exit;
        }
        else
            $this->Error_Log('DB:: mysql_connect ' . ($___te - $___ts) . 's');

        $___ts = microtime(TRUE);
        $___mysql_select_db_result = mysql_select_db($this->DbNm, $this->DbConn);
        $___te = microtime(TRUE);

        if ( !$___mysql_select_db_result )
        {
            $this->Error_Log('ERROR::DB:: mysql_select_db ' . $this->Label . ' ' . $this->Host . ' ' . $this->DbNm . ' ' . $this->User . ' ' . ($___te - $___ts) . 's' . " >> Error: " . mysql_error(), TRUE);
            exit;
        }
        else
            $this->Error_Log('DB:: mysql_select_db ' . ($___te - $___ts) . 's');

        unset($___ts, $___te, $___mysql_select_db_result);

        $this->IsConnected = TRUE;
    }

    private function Mysql_Query($Sql)
    {
        $this->Connect();

        $___ts = microtime(TRUE);

        $Result = mysql_query($Sql, $this->DbConn);

        $___te = microtime(TRUE);

        if ( $Result === FALSE )
        {
            $this->Error_Log('ERROR::DB:: mysql_query ' . $this->Label . ' ' . ($___te - $___ts) . "s     " . $Sql . " >> Error: " . mysql_error(), TRUE);
            exit;
        }
        else
            $this->Error_Log('DB:: mysql_query ' . $this->Label . ' ' . ($___te - $___ts) . "s     " . $Sql);

        unset($___ts, $___te);

        return $Result;
    }

    public function Query($Sql)
    {
        $Response = NULL;

        $DbResult = $this->Mysql_Query($Sql);

        while ( $DbRow = mysql_fetch_assoc($DbResult) )
            $Response[] = $DbRow;

        mysql_free_result($DbResult);

        return $Response;
    }

    public function Row($Sql)
    {
        $DbRow = NULL;

        $DbResult = $this->Mysql_Query($Sql);

        if ( mysql_num_rows($DbResult) > 0 )
            $DbRow = mysql_fetch_assoc($DbResult);

        mysql_free_result($DbResult);

        return $DbRow;
    }

    public function Cell($Sql)
    {
        $DbCell = NULL;

        $DbResult = $this->Mysql_Query($Sql);

        if ( $DbRow = mysql_fetch_array($DbResult) )
            $DbCell = $DbRow[0];

        mysql_free_result($DbResult);

        return $DbCell;
    }

    public function Cells($Sql) 
    {
        $Response = NULL;

        $DbResult = $this->Mysql_Query($Sql);

        while ( $DbRow = mysql_fetch_array($DbResult) )
            $Response[] = $DbRow[0];

        mysql_free_result($DbResult);

        return $Response;
    }

    public function Map($Sql)
    {
        $Response = NULL;

        $DbResult = $this->Mysql_Query($Sql);

        while ( $DbRow = mysql_fetch_array($DbResult) )
            $Response[$DbRow[0]] = $DbRow[1];

        mysql_free_result($DbResult);

        return $Response;
    }

    public function Execute($Sql)
    {
        /*
        if ( in_array(strtoupper(substr($Sql, 0, 6)), array('UPDATE', 'INSERT')) )
        {
            global $DbS, $DbM;
            $DbS = $DbM;
        }
        */

        $this->Mysql_Query($Sql);

        return mysql_affected_rows($this->DbConn);
    }

    public function Insert($Sql)
    {
        $this->Mysql_Query($Sql);

        return mysql_insert_id($this->DbConn);
    }

    public function Disconnect()
    {
        if ( $this->IsConnected )
            mysql_close($this->DbConn);
        // if ($this->DbConn !== NULL)
            // if (mysql_ping($this->DbConn))

        $this->IsConnected = FALSE;
    }

    public function Sbind()
    {
        $numargs = func_num_args();
        $php = '';

        for ( $i = 0; $i < $numargs; $i++ )
        {
            $fagi = func_get_arg($i);
            if ( $i == 0 ) //the first parameter is not to be escaped
            {
                $php .= '"'.$fagi.'",';
            }
            else
            {
                if ( is_string($fagi) || $fagi == null || $fagi == NULL )
                {
                    if ( $fagi == null || $fagi == NULL )
                        $php .= "\"''\",";
                    else
                        $php .= "\"'".mysql_real_escape_string($fagi)."'\",";
                }
                else
                {
                    $php .= ($fagi?$fagi:0).',';
                }
            }
        }
        $ret = '';
        $php = '$ret=sprintf('.rtrim($php,',').');';    //  this $ret is correct. don't remove / edit it !

        eval($php);

        return $ret;    //  this $ret is correct. don't remove / edit it !
    }
}

