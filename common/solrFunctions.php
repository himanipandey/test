<?php
// date should be send in timestamp format.
function getDateInSolrFormat($time)
{
  if(empty($time))
    return NULL;

  return gmdate('Y-m-d\TH:i:s\Z', $time);
}

function getSolrSearchDateStr($from_time, $to_time)
{
   return "[{$from_time} TO {$to_time}]"; 
}
?>
