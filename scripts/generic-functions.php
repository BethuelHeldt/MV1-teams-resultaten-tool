<?php 

function nlDate($datum){ 
/* 
 // AM of PM doen we niet aan 
 $parameters = str_replace("A", "", $parameters); 
 $parameters = str_replace("a", "", $parameters); 

$datum = date($parameters); 
*/ 
 // Vervang de maand, klein 
    $datum = str_replace("january", "januari", $datum); 
    $datum = str_replace("february", "februari", $datum); 
    $datum = str_replace("march", "maart", $datum); 
    $datum = str_replace("april", "april", $datum); 
    $datum = str_replace("may", "mei", $datum); 
    $datum = str_replace("june", "juni", $datum); 
    $datum = str_replace("july", "juli", $datum); 
    $datum = str_replace("august", "augustus", $datum); 
    $datum = str_replace("september", "september", $datum); 
    $datum = str_replace("october", "oktober", $datum); 
    $datum = str_replace("november", "november", $datum); 
    $datum = str_replace("december", "december", $datum); 

    // Vervang de maand, hoofdletters 
    $datum = str_replace("January", "Januari", $datum); 
    $datum = str_replace("February", "Februari", $datum); 
    $datum = str_replace("March", "Maart", $datum); 
    $datum = str_replace("April", "April", $datum); 
    $datum = str_replace("May", "Mei", $datum); 
    $datum = str_replace("June", "Juni", $datum); 
    $datum = str_replace("July", "Juli", $datum); 
    $datum = str_replace("August", "Augustus", $datum); 
    $datum = str_replace("September", "September", $datum); 
    $datum = str_replace("October", "Oktober", $datum); 
    $datum = str_replace("November", "November", $datum); 
    $datum = str_replace("December", "December", $datum); 

    // Vervang de maand, kort 
    $datum = str_replace("Jan", "Jan", $datum); 
    $datum = str_replace("Feb", "Feb", $datum); 
    $datum = str_replace("Mar", "Maa", $datum); 
    $datum = str_replace("Apr", "Apr", $datum); 
    $datum = str_replace("May", "Mei", $datum); 
    $datum = str_replace("Jun", "Jun", $datum); 
    $datum = str_replace("Jul", "Jul", $datum); 
    $datum = str_replace("Aug", "Aug", $datum); 
    $datum = str_replace("Sep", "Sep", $datum); 
    $datum = str_replace("Oct", "Ok", $datum); 
    $datum = str_replace("Nov", "Nov", $datum); 
    $datum = str_replace("Dec", "Dec", $datum); 

    // Vervang de dag, klein 
    $datum = str_replace("monday", "maandag", $datum); 
    $datum = str_replace("tuesday", "dinsdag", $datum); 
    $datum = str_replace("wednesday", "woensdag", $datum); 
    $datum = str_replace("thursday", "donderdag", $datum); 
    $datum = str_replace("friday", "vrijdag", $datum); 
    $datum = str_replace("saturday", "zaterdag", $datum); 
    $datum = str_replace("sunday", "zondag", $datum); 

    // Vervang de dag, hoofdletters 
    $datum = str_replace("Monday", "Maandag", $datum); 
    $datum = str_replace("Tuesday", "Dinsdag", $datum); 
    $datum = str_replace("Wednesday", "Woensdag", $datum); 
    $datum = str_replace("Thursday", "Donderdag", $datum); 
    $datum = str_replace("Friday", "Vrijdag", $datum); 
    $datum = str_replace("Saturday", "Zaterdag", $datum); 
    $datum = str_replace("Sunday", "Zondag", $datum); 

    // Vervang de verkorting van de dag, hoofdletters 
    $datum = str_replace("Mon",    "Maa", $datum); 
    $datum = str_replace("Tue", "Din", $datum); 
    $datum = str_replace("Wed", "Woe", $datum); 
    $datum = str_replace("Thu", "Don", $datum); 
    $datum = str_replace("Fri", "Vri", $datum); 
    $datum = str_replace("Sat", "Zat", $datum); 
    $datum = str_replace("Sun", "Zon", $datum); 

    return $datum; 
}

?>