<?php
$docroot = dirname(__FILE__) . "/";
require_once $docroot.'cronConfig.php';
switch ($argv[1]) {
	case 'daily':
		$configArray = $dailyEmail;
		break;
	case 'weekly':
		$configArray = $weeklyEmail;
		break;
        case 'monthly':
                $configArray = $monthlyEmail;
                break;
}

foreach ($configArray as $email) {
	$result = mysql_query($email['sql']);
	$result_table = array();
	while($row=  mysql_fetch_assoc($result)){
		$result_table[] = $row;
	}
	if(count($result_table)>0 || !($email['sendifnodata']===0)){
		$to = $email['recipients'][0];
		$cc = implode(',', $email['recipients']);
		$from = 'no-reply@proptiger.com';
		$emailSubject = $email['subject'].' - '.date("d-m-Y");

		if(is_null($email['attachmentname'])){
			$emailContent = $email['message'] . "<br/><br/>" . put2DArrayIntoTable($result_table);
			sendRawEmailFromAmazon($to, $from, $cc, $emailSubject, $emailContent, '', '', $email['recipients']);
		}
		else{
			$emailContent = $email['message'] . "<br/><br/>";
			$filePath = $docroot.mt_rand().'rand.csv';
			putResultsInFile($result_table, $filePath);
			sendRawEmailFromAmazon($to, $from, $cc, $emailSubject, $emailContent, $email['attachmentname'].'-'.date("d-m-Y").'.csv', $filePath, $email['recipients']);
                        unlink($filePath);
		}
	}
}
?>
