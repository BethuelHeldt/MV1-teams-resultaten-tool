<!doctype html>
<?php
session_start();
$unit_arr = array("AVO", "VRMG", "BO", "DIG", "CREA","SLB","FLEX");

$expertise_arr = array(
    "ONP", "COM", "VRMG", "DTP", "WRP", "WEB", "UXD", "MGR", "NED", "ENG", "REK", "TAVA", "UWT", "KEU", "SLB", "FLEX"
);

$msg = $_GET['mc'];//komt uit upload.php
$msg_tekst = "";
if ($msg == ""){//geen upload nog
    $msg_tekst = "Je moet eerst een csv bestand uploaden.";
} elseif ($msg == "1"){//upload gelukt
    $msg_tekst = "Je hebt een csv bestand geupload.";
} elseif ($msg == "2"){//upload niet gelukt
    $msg_tekst = "Er ging iets mis. Had je wel een csv bestand geselecteerd?";
}
?>
<html>

<head>
    <meta charset="utf-8">
    <title>Teams opdrachten</title>

    <meta name="keywords" content="Opdrachten Mediacollege Mediavormgever">
    <meta name="description" content="Teams Assignments interpreteren">
    <meta name="author" content="Bethuel Heldt">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../js/jquery-3.6.0.min.js"></script>
    <link href="../css/style_all.css" rel="stylesheet" type="text/css">
    <link href="../css/style-opdrachten-student.css" rel="stylesheet" type="text/css">

</head>

<body>
    <div class="nav">
        <a href="../teams-opdrachten-student/" class="btn selected">Opdrachten per student</a>
        <a href="../teams-opdrachten-klas/" class="btn">Opdrachten per klas</a>
        <a href="../teams-studiepunten/" class="btn">Studiepunten per klas</a>
    </div>
    <div class="opdrachten_container">

        <section class="formulier">
            <h2>CSV bestand uploaden</h2>
            <p>Download de resultaten van een student vanuit de <strong>Klasse tegel</strong> in Teams. Ga naar <strong>Cijfers/Grades</strong> en klik daar op de naam van een student. Klik vervolgens op 'Exporteren naar Excel' (rechtsboven in Teams). Upload het csv bestand hieronder.</p>
            <form action="../upload.php" method="post" enctype="multipart/form-data" id="uploadform">
                <label for="fileToUpload" class="custom-file-upload btn">
                    Selecteer een csv bestand
                </label>
                <input type="hidden" name="return_page" id="return_page" value="teams-opdrachten-student/index.php">
                <input type="file" name="fileToUpload" id="fileToUpload" accept=".csv" placeholder="Selecteer een csv bestand" class="btn">
                <button type="submit" name="submit" id="opslaan_knop" class="btn">Upload bestand</button>
            </form>
            <h3><? echo $msg_tekst ?></h3>
        </section>
<?php
if ($_SESSION["csv_file"] == "") {
    $title_tag = 'Teams opdrachten per student';
?>
        <h1 class="pag_titel">Ma opdrachten per student</h1>
    </div>
<?php
} else {
    
    //titels maken
    $bestandsnaam = $_SESSION["csv_file"];

    //module naam uit bestandsnaam halen
    $pos_module = strpos($bestandsnaam, "Module");
    $pos_module = $pos_module + 8; //8 charackters na 'Module 4' start de naam als het goed is
    $naam_module = substr($bestandsnaam, 0, $pos_module);

    //naam student uit bestandsnaam halen
    $pos_cijfer = strpos($bestandsnaam, "cijfer");
    $lengte_naam = $pos_cijfer - $pos_module;
    $naam_student = substr($bestandsnaam, $pos_module+2, $lengte_naam-2);

    $title_tag = $naam_student . " | " . $naam_module;
    //opdracht vars
    $aantal_niet_ingeleverd = 0;
    $aantal_opdrachten = 0;
    $aantal_onvoldoende = 0;
    $aantal_voldoende = 0;
    $aantal_niet_nagekeken = 0;
    $aantal_niet_conform = 0;
?>
        
        <h2 class="naam_module">Opdrachten <?=$naam_module;?></h2>
        <h1 class="naam_student"><?=$naam_student;?></h1>
        <div class="btns">
            <button data-unit="unit_alles" class="btn btn_alles btn_filter">alle units</button>
            <button data-unit="unit_BO" class="btn btn_bo btn_filter">BO</button>
            <button data-unit="unit_VRMG" class="btn btn_vrmg btn_filter">VRMG</button>
            <button data-unit="unit_CREA" class="btn btn_crea btn_filter">CREA</button>
            <button data-unit="unit_DIG" class="btn btn_dig btn_filter">DIG</button>
            <button data-unit="unit_AVO" class="btn btn_avo btn_filter">AVO</button>
            <button data-unit="unit_FLEX" class="btn btn_flex btn_filter">FLEX</button>
            <button data-unit="unit_SLB" class="btn btn_slb btn_filter">SLB</button>
        </div>

        <section class="opdrachten_lijst">
<?php
    
    $data = loadCSV('../uploads/' . $_SESSION['csv_file']);
    //print_r($data);
    
    foreach ($data as $row) {
        $aantal_opdrachten++;
        $datum = $row[0];
        $titel = $row[1];
        $status = $row[2];
        $beoordeling = $row[3];
        $feedback = replaceQuoteIs($row[4]);//voor de zekerheid, soms is er ineens een extra kolom door een comma in de titel of in de feedback
        
        // als beoordeling niet bepaalde woorden bevat heeft de titel waarschijnlijk een of meer comma's en schuift alle data op
        if(checkTitelComma($beoordeling) !== TRUE){
            $titel = $row[1] . ", " . $row[2]; //samenvoegen, er zat een comma  in de titel
            $status = $row[3];
            $beoordeling = $row[4];
            $feedback = replaceQuoteIs($row[5]);
        }
        //zoeken naar 2de komma in titel, komt voor!!!
        if(checkTitelComma($beoordeling) !== TRUE){
            $titel = $row[1] . ", " . $row[2] . ", " . $row[3]; //samenvoegen, er zat een comma  in de titel
            $status = $row[4];
            $beoordeling = $row[5];
            $feedback = replaceQuoteIs($row[6]);
        }
        //Voor de zekerheid nog zoeken naar 3de komma in titel, komt voor!!!
        if(checkTitelComma($beoordeling) !== TRUE){
            $titel = $row[1] . ", " . $row[2] . ", " . $row[3] . ", " . $row[4]; //samenvoegen, er zat een comma  in de titel
            $status = $row[5];
            $beoordeling = $row[6];
            $feedback = replaceQuoteIs($row[7]);
        }


        //datum/tijd
        //$dateString = $row[0];
        $dateTimeObj = date_create($datum);
        $date = date_format($dateTimeObj, 'l d F Y');
        $dag = nlDate($date); 
        $tijd = date_format($dateTimeObj, 'H:i');

        //de html output
        echo '<div class="opdracht ' . maBeoordeling($beoordeling,$status,'class',TRUE) . ' unit_' . unit($titel) . ' expertise_' . expertise($titel) . '" id="opdracht_' . $aantal_opdrachten . '" style="order: ' . (1000-$aantal_opdrachten) . ';">';
        echo '  <div class="opdracht-item deadline"><span class="dag">' . $dag. '</span><span class="tijd">' . $tijd . '</span></div>';
        echo '  <div class="opdracht-item naam"><span class="opdracht_nr">' . $aantal_opdrachten . '</span> ' . $titel . '</div>';
        echo '  <div class="opdracht-item status">' . status($beoordeling,$status,FALSE) . '</div>';
        echo '  <div class="opdracht-item unit"><span class="expertise-code ' . expertise($titel) . '">' . unit($titel) . ' ' . (expertise($titel) != unit($titel) ? expertise($titel):'') . '</span></div>';
        echo '  <div class="opdracht-item beoordeling">';
        if (replaceQuoteIs($beoordeling) == "/4" || replaceQuoteIs($beoordeling) == "Geen punten") {
            echo status($beoordeling,$status,TRUE) . '<br>';
        } 
        echo maBeoordeling($beoordeling,$status,'tekst',FALSE);
        echo '</div>';
        if($feedback != ""){
            echo '  <div class="opdracht-item feedback"><label for="fb-toggle_' . $aantal_opdrachten . '" class="fb_btn">Lees de feedback</label><input type="checkbox" id="fb-toggle_' . $aantal_opdrachten . '" name="fb_toggle" class="fb-toggle" /><div class="fb">' . $feedback . '</div></div>';
        } else {
            echo '  <div class="opdracht-item feedback">Er is (nog) geen feedback gegeven op deze opdracht</div>';
        }
        echo '</div>';
    }

?>
        </section>

        <section class="opdrachten_info">
            <h2>Samenvatting</h2>
            <h3>Bestand: <?=$bestandsnaam;?></h3>
            <p>Er zijn (tot nu toe) <?=$aantal_opdrachten;?> opdrachten in deze module. <br>Daarvan zijn er:</p>
            <ul>
                <li><a data-toon=".onvoldoende" href="onvoldoende" class="toon_onv"><?=$aantal_onvoldoende;?> onvoldoende</a></li>
                <li><a data-toon=".goed, .voldoende, .excellent" href="voldoendes" class="toon_vol"><?=$aantal_voldoende;?> voldoende, goed of exellent.</a></li>
                <li><a data-toon=".niet_ingeleverd" href="niet ingeleverd" class="toon_ni"><?=$aantal_niet_ingeleverd;?> nog niet ingeleverd. </a></li>
                <li><a data-toon=".todo_docent" href="niet nagekeken" class="toon_nn"><?=$aantal_niet_nagekeken;?> nog niet na gekeken.</a></li>
                <li><a data-toon=".beoordeling_niet_conform" href="beoordeling niet duidelijk" class="toon_nd"><?=$aantal_niet_conform;?> beoordeling niet duidelijk.</a></li>
            </ul>
            <button type="button" class="btn" onclick="window.print();">Printen</button>
        </section>
    </div>

<?php
}
?>

    <script>
        $(function(){

            //bij file selecteren meteen form submitten
            $('#fileToUpload').change(function(e){
                //$('#uploadform').submit();
                $('#opslaan_knop').click();
            });

            //zet in tilte de naam_student en naam_module
            $('title').text('<?=$title_tag;?>');

            $('.btn_filter:not(.btn_alles)').click(function(e){ 
                $this = $(this);
                $('.btn_filter').removeClass('selected');
                $this.addClass('selected');
                $class = $this.data('unit');
                $('.opdracht').addClass('hide');
                $('.'+$class).removeClass('hide');
            });

            $('.btn_filter.btn_alles').click(function(e){ 
                $('.opdracht').removeClass('hide');
                $('.btn_filter').removeClass('selected');
            });

            $('.opdrachten_info a').click(function(e){
                e.preventDefault();
                $this = $(this);
                $class = $this.data('toon');
                $('.opdracht').addClass('hide');
                $($class).removeClass('hide');
            });
        });
    </script>
</body>
</html>

<?php
//functions

function loadCSV($file) {
    $rows = array();//stop de csv in een array
    
    if (($handle = fopen($file, "r")) !== FALSE) {
        fgetcsv($handle);// skip eerste row
        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
            array_push($rows, $data);
        }
        fclose($handle);
    }

    return array_reverse($rows); // verander de volgorde van de array zodat de eerste opdracht bovenaan komt. Met flexobox omdraaien kan ook maar dan kan je geen gebruik maken van page-break
}

function unit($str){

    GLOBAL $unit_arr;
    foreach($unit_arr as $a) {
        if (stripos($str,$a) !== false) return $a ;
    }
    
    //AVO is: TAVA, ENG, NED of REK, dus aparte Array
    $avo_arr = array("TAVA","ENG","NED","REK");
    foreach($avo_arr as $a) {
        if (stripos($str,$a) !== false) return "AVO" ;
    }
    
    //return $str;
}   

function expertise($str){
    //return;
    //haal expertise uit opdracht naam
    GLOBAL $expertise_arr;
    $expertise;
    $eerste_deel = strtolower(substr($str, 0, 11));//in de eerste 11 characters moet wel de expertise staan. BV:  F2M4 DIG Motion G
    //F2M4 BO Motion Graphics = 23 characters
    foreach($expertise_arr as $a) {
        if (stripos($eerste_deel, $a) !== false) {
            $expertise = str_replace(' ','', $a);
            $expertise = str_replace('-','', $a);
            return str_replace(' ','', $expertise);
        }
    }
    //return $str;
}   

function checkTitelComma($tekst){
    //beoordeling begint met =" en eindigt met /4"
    $firstCharacter = substr($tekst, 0, 2);// check op =" 
    $lastCharacter = substr($tekst, -3);// check op /4" 

    //array met geldige waarden voor beoordeling
    $beo_arr = array("Geen punten", "/4", "0/4", "1/4", "2/4", "3/4", "4/4");
    //$tekst = replaceQuoteIs($tekst);
    //if (in_array($tekst, $beo_arr)){
    if ($firstCharacter == "=\"" && $lastCharacter == "/4\"" || $tekst == "=\"Geen punten\""){
        //dit is inderdaad een geldige beoordeling
        //echo "geldige beoordeling: $tekst";
        return true;
    } else {
        //dit is waarschijnlijk geen beoordeling dus titel en status samenvoegen
        //echo "LET OP: GEEN GELDIGE BEO!!!";
        return false;
    }
}
function status($b, $s, $kort){
    //return $s;
    $tekst = "";
    $tekst_kort = "";
    $beoordeling = maBeoordeling($b,$s,'tekst', FALSE);
    if ($s == "Bekeken"){
        $tekst = "Je moet deze opdracht nog inleveren.";
        $tekst_kort = "Niet ingeleverd";
    } elseif ($s == "Geretourneerd"){
        $tekst = "De opdracht is nagekeken en is beoordeeld als <span class='beoordeeld " . maBeoordeling($b,$s,'class', FALSE) . "'>" . maBeoordeling($b,$s,'tekst', FALSE) . "</span>.";
        $tekst_kort = "Geretourneerd";
    } elseif ($s == "Ingeleverd"){
        $tekst = "Je hebt de opdracht ingeleverd. De opdracht moet nog nagekeken worden door de docent.";
        $tekst_kort = "Ingeleverd";
    } elseif (substr($s, 0, 18) == "Te laat ingeleverd"){
        $tekst = "Je hebt de opdracht te laat ingeleverd. De opdracht moet nog nagekeken worden door de docent.";
        $tekst_kort = "Te laat ingeleverd";
    } elseif ($s == "Revisie nodig"){
        $tekst = "Je moet nog wat voor deze opdracht doen. Lees en verwerk de feedback en lever de opdracht opnieuw in.";
        $tekst_kort = "Feedback verwerken en opnieuw inleveren.";
    } elseif ($s == "Opnieuw ingeleverd"){
        $tekst = "Je hebt deze opdracht opnieuw ingeleverd. Check later voor de nieuwe beoordeling.";
        $tekst_kort = "Opnieuw ingeleverd";
    } else {
        $tekst = $s;
        $tekst_kort = $s;
    }
    
    return $kort ? $tekst_kort : $tekst;
}

function maBeoordeling($b,$s,$wat,$tellen){
    $b = replaceQuoteIs($b);
    $s = replaceQuoteIs($s);
    $beoordeling_tekst = $b;
    $beo_class;

    GLOBAL $aantal_niet_ingeleverd;
    GLOBAL $aantal_onvoldoende;
    GLOBAL $aantal_voldoende;
    GLOBAL $aantal_niet_nagekeken;
    GLOBAL $aantal_niet_conform;

    if($b == "0/4"){
        $beoordeling_tekst = "niet ingeleverd";
        $beo_class = "is_beoordeeld niet_ingeleverd";
        if ($tellen) $aantal_niet_ingeleverd++;
    } elseif ($b == "1/4"){
        $beoordeling_tekst = "onvoldoende";
        $beo_class = "is_beoordeeld onvoldoende";
        if ($tellen) $aantal_onvoldoende++;
    } elseif ($b == "2/4"){
        $beoordeling_tekst = "voldoende";
        $beo_class = "is_beoordeeld voldoende";
        if ($tellen) $aantal_voldoende++;
    } elseif ($b == "3/4"){
        $beoordeling_tekst = "goed";
        $beo_class = "is_beoordeeld goed";
        if ($tellen) $aantal_voldoende++;
    } elseif ($b == "4/4"){
        $beoordeling_tekst = "excellent";
        $beo_class = "is_beoordeeld excellent";
        if ($tellen) $aantal_voldoende++;
    } elseif ($b == "Bekeken"){
        $beoordeling_tekst = "Je moet deze opdracht nog inleveren.";
        $beo_class = "todo_student";
        if ($tellen) $aantal_niet_ingeleverd++;
    } elseif ($b == "/4" || $b == "Geen punten"){
        $beoordeling_tekst = "Nog niet nagekeken";
        $beo_class = "todo_docent";
        if ($tellen) $aantal_niet_nagekeken++;
    } else {
        $beoordeling_tekst = "Beoordeling is niet duidelijk.";
        $beo_class = "beoordeling_niet_conform";
        if ($tellen) $aantal_niet_conform++;
    }
    if ($wat == 'tekst') {
        return $beoordeling_tekst;
    } elseif($wat == 'class') {
        return $beo_class;
    }
}
// eerste en laatste characters weghalen die bestaan uit ="feedback tekst"
function replaceQuoteIs($str){
    //return $str;
    $firstCharacter = substr($str, 0, 2);// =" eraf halen
    $lastCharacter = substr($str, -1);// " laatste quote eraf halen
    $niets_ingevuld = $str;
    //if ($str == "=\"\""){// als ="" dan niets ingevuld
    //    return "geen $wat ingevuld";
    //} else {
        if ($firstCharacter == "=\"" ){// =" aan het begin er af halen
            $str = trim($str,"=");
            $str = trim($str,"\"");
        }
        if ($lastCharacter == "\"" ){ // " aan het einde er af halen
            $str = rtrim($str, $lastCharacter);
        }
    //}
    return $str;
}

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

//delete de geuploade file
unlink('../uploads/' . $_SESSION["csv_file"]); //delete de csv file
unset($_SESSION["csv_file"]);//verwijder de session
?>
