<?php

include("../appWideConfig.php");
include("../dbConfig.php");
include("../listing_function.php");
include("../function/functions_listing.php");
include("../httpful.phar");

try {

    $uriLogin = ADMIN_USER_LOGIN_API_URL;
    $responseLogin = \Httpful\Request::post($uriLogin)->sendsJson()->body('')->send();

    $header = $responseLogin->headers;
    $header = $header->toArray();
    $cookie = $header['set-cookie'];
    $cookie_new = "";
    for ($i = 0; $i < strlen($cookie); $i++) {
        if ($cookie[$i] == ';') {
            break;
        }
        $cookie_new = $cookie_new . $cookie[$i];
    }

    if ($cookie_new != '') {

        $fields = "seller,seller.fullName,id,listing,listing.createdAt,listing.facingId,listing.jsonDump,listing.description,listing.remark,listing.homeLoanBankId,listing.flatNumber,listing.noOfCarParks,listing.negotiable,listing.transferCharges,listing.plc,listing.towerId,listing.phaseId,property,property.propertyId,property.project.name,property.projectId,property.project.builder,property.project.locality,property.project.locality.suburb,property.project.locality.suburb.city,listingAmenities.amenity,listingAmenities.amenity.amenityMaster,label,masterAmenityIds,name,unitType,unitName,size,currentListingPrice,localityId,floor,pricePerUnitArea,price,otherCharges,jsonDump,latitude,longitude,amenityDisplayName,isDeleted,bedrooms,bathrooms,amenityId";
        $uriListing = LISTING_API_URL . "?listingCategory=Resale&fields={$fields}";

        $responseLists = \Httpful\Request::get($uriListing)->addHeader("COOKIE", $cookie_new)->send();

        if ($responseLists->body->statusCode == "2XX") {
            $data = $responseLists->body->data;
            $tbsorterArr['total_rows'] = $responseLists->body->totalCount;
            $tbsorterArr['headers'] = array("Serial", "Listing Id", "City", "Broker Name", "Project", "Listing", "Price", "Created Date", "Save");

            $tbsorterArr['rows'] = array();
            foreach ($data as $k => $v) {
                $seller_id = $v->seller->id;
                $company_id = '';
                if ($seller_id) {
                    $Sql = "SELECT c.name, c.id FROM company c inner join company_users cu on c.id=cu.company_id WHERE cu.user_id=" . $seller_id . " and c.status = 'Active' and cu.status='Active' ";
                    $Sel = array();
                    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in fetching data from company_users');
                    $cnt = 0;
                    if (mysql_num_rows($ExecSql) > 0) {
                        $Res = mysql_fetch_assoc($ExecSql);
                        $broker_name = $Res['name'];
                        $broker_id = $Res['id'];
                    }
                    $v->seller->brokerName = $broker_name;
                    $v->seller->brokerId = $broker_id;
                }

                $tmp['json'] = htmlentities(json_encode($v));
                $tmp['val'] = $v;
                array_push($resaleListings, $tmp);
                if ($v->currentListingPrice->pricePerUnitArea != 0) {
                    $price = "Price Per Unit Area - " . $v->currentListingPrice->pricePerUnitArea;
                } else {
                    $price = "Price - " . $v->currentListingPrice->price;
                }
                if ($v->currentListingPrice->otherCharges != 0) {
                    $price .= " ; &nbsp; Other Charges - " . $v->currentListingPrice->otherCharges;
                }
                $v->property->project->description = $v->property->project->locality->description = $v->property->project->locality->suburb->description = $v->property->project->locality->suburb->city->description = '';

                $rows = array(
                    "Serial" => $start + $k + 1,
                    "City" => $v->property->project->locality->suburb->city->label,
                    "BrokerName" => $v->seller->brokerName,
                    "Project" => $v->property->project->name . ", " . $v->property->project->builder->name,
                    "Listing" => $v->property->unitName . "-" . $v->property->size . "-" . $v->property->unitType,
                    "Price" => $price,
                    "Save" => '',
                    "ListingId" => $v->id,
                    "CreatedDate" => date("Y-m-d", ($v->createdAt) / 1000)
                );
                array_push($tbsorterArr['rows'], $rows);
            }
        }
        $pdf_content = "<table cellspacing=1 bgcolor='' cellpadding=0 width='100%' style='font-size:11px;font-family:tahoma,arial,verdana;vertical-align:middle;text-align:center;'>
    <tr bgcolor='#f2f2f2'>
    <td>Serial</td>
    <td>Listing Id</td>
    <td>City</td>
    <td>Broker Name</td>
    <td>Listing</td>
    <td>Price</td>
    <td>Created Date</td>
    </tr>";
        foreach ($tbsorterArr['rows'] as $row) {
            $pdf_content .= "<tr  bgcolor='#FFFFFF' valign='top'><td>".$row['Serial']."</td>";
            $pdf_content .= "<td>".$row['ListingId']."</td>";
            $pdf_content .= "<td>".$row['City']."</td>";
            $pdf_content .= "<td>".$row['BrokerName']."</td>";
            $pdf_content .= "<td>".$row['Listing']."</td>";
            $pdf_content .= "<td>".$row['Price']."</td>";
            $pdf_content .= "<td>".$row['CreatedDate']."</td></tr>";
        }
        $pdf_content .= "</table>";

        $filename = "excelreport-" . date('YmdHis') . ".xls";
        header('Content-type: application/ms-excel');
        header('Content-Disposition: attachment; filename=' . $filename);
        echo $pdf_content;
    }
} catch (Exception $ex) {
    die($ex->getMessage());
}