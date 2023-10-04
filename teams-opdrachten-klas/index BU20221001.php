<!doctype html>
<?php
session_start();

$opdrachtenArray = array();

$unit_arr = array("AVO", "VRMG", "BO", "DIG", "CREA","SLB","FLEX");

$expertise_arr = array(
    "BO ONP", "BO COM", "VRMG", "CREA DTP", "CREA WRP", "CREA WEB", "DIG UXD", "DIG MGR", "NED", "ENG", "REK", "TAVA", "FLEX UWT", "FLEX KEU",
    "BO-ONP", "BO-COM", "VRMG", "CREA-DTP", "CREA-WRP", "CREA-WEB", "DIG-UXD", "DIG-MGR", "NED", "ENG", "REK", "TAVA", "FLEX UWT", "FLEX KEU"
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
    <title>Teams opdrachten overzicht klas</title>

    <meta name="keywords" content="Opdrachten Mediacollege Mediavormgever">
    <meta name="description" content="Teams Assignments interpreteren">
    <meta name="author" content="Bethuel Heldt">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../js/jquery-3.6.0.min.js"></script>
    <link href="../css/style_all.css" rel="stylesheet" type="text/css">
    <link href="../css/style-opdrachten-klas.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="nav">
        <a href="../teams-opdrachten-student/" class="btn">Opdrachten per student</a>
        <a href="../teams-opdrachten-klas/" class="btn selected">Opdrachten per klas</a>
        <a href="../teams-studiepunten/" class="btn">Studiepunten per klas</a>
    </div>
    <div class="opdrachten_container">

        <section class="formulier">
            <h2>CSV bestand uploaden</h2>
            <p>Download de resultaten van de hele klas vanuit de <strong>Klasse tegel</strong> in Teams. Klik op 'Exporteren naar Excel' bij <strong>cijfers/grades</strong>  (rechtsboven in Teams). Upload het csv bestand hieronder.</p>
            <form action="../upload.php" method="post" enctype="multipart/form-data" id="uploadform">
                <label for="fileToUpload" class="custom-file-upload btn">
                    Selecteer een csv bestand
                </label>
                <input type="hidden" name="return_page" id="return_page" value="teams-opdrachten-klas/index.php">
                <input type="file" name="fileToUpload" id="fileToUpload" accept=".csv" placeholder="Selecteer een csv bestand" class="btn">
                <button type="submit" name="submit" id="opslaan_knop" class="btn">Upload bestand</button>
            </form>
            <h3><? echo $msg_tekst ?></h3>
        </section>
<?php
if ($_SESSION["csv_file"] == "") {
    $title_tag = 'Teams opdrachten per klas';
?>
        <h1 class="pag_titel">Ma opdrachten per klas</h1>
    </div>
<?php
} else {
    

?>
        <div class="btns">
            <button data-unit="unit_alles" class="btn btn_alles btn_filter">alle units</button>
            <button data-unit="unit_BO" class="btn btn_bo btn_filter">BO</button>
            <button data-unit="unit_VRMG" class="btn btn_vrmg btn_filter">VRMG</button>
            <button data-unit="unit_CREA" class="btn btn_crea btn_filter">CREA</button>
            <button data-unit="unit_DIG" class="btn btn_dig btn_filter">DIG</button>
            <button data-unit="unit_AVO" class="btn btn_avo btn_filter">AVO</button>
            <button data-unit="unit_FLEX" class="btn btn_flex btn_filter">FLEX</button>
            <button data-unit="unit_SLB" class="btn btn_slb btn_filter">SLB</button>

            <br>
            <button data-unit="vak_BOONP" class="btn btn_vak btn_filter">ontw.pr.</button>
            <button data-unit="vak_BOCOM" class="btn btn_vak btn_filter">Com.</button>
            <button data-unit="vak_VRMG" class="btn btn_vak btn_filter">VRMG</button>
            <button data-unit="vak_CREAWRP" class="btn btn_vak btn_filter">Werkpl.</button>
            <button data-unit="vak_CREADTP" class="btn btn_vak btn_filter">DTP</button>
            <button data-unit="vak_DIGUXD" class="btn btn_vak btn_filter">UXD</button>
            <button data-unit="vak_DIGMGR" class="btn btn_vak btn_filter">MG</button>
            <button data-unit="vak_CREAWEB" class="btn btn_vak btn_filter">WEB</button>
            <button data-unit="vak_NED" class="btn btn_vak btn_filter">NED</button>
            <button data-unit="vak_ENG" class="btn btn_vak btn_filter">ENG</button>
            <button data-unit="vak_TAVA" class="btn btn_vak btn_filter">TV</button>
            <button data-unit="vak_REK" class="btn btn_vak btn_filter">REK</button>
        </div>

        <section class="opdrachten_lijst">
            <table>
<?php
    $bestandsnaam = $_SESSION['csv_file'];
    $opdracht_detail_div = '';
    $data = loadCSV('../uploads/' . $bestandsnaam);

    $class_array_student = array('nr','voornaam','achternaam','email');
    $class_array_opdracht = array('opdracht_beoordeling','punten','feedback');

    $aantal_v = 0;
    $aantal_o = 0;
    $aantal_n = 0;
    $onjuiste_beo = FALSE;
    $onjuist_punten_totaal = FALSE;

    for($row = 0; $row < count($data); $row++) {
        if($row === 0){
            echo '<thead><tr><th class="nr col_0" data-col="0"><span></span></th>';
        } else {
            echo '<tr><td class="nr col_0" data-col="0">' . $row . '</td>';
        }
        $col_nr = 0;
        $opdracht_nr = 0;
        $aantal_v = 0;
        $aantal_o = 0;
        $aantal_n = 0;

        foreach ($data[$row] as $col => $value) {
            $col_nr++;
            $mod = ($col_nr-1) % 3;//1ste 2de of 3de item van class_array_opdracht berekenen met modulo

            if($row === 0){ //bovenste rij, met titels
                if($col_nr < 4){//eerste 4 cellen zijn nr, naam en email
                    echo '<th class="' . $class_array_student[$col_nr] . ' col_'.$col_nr.'"><span>' . $value . '</span></th>';

                } else { //cellen met opdracht, totaal punten en feedback
                    if($mod == 0){// opdracht titel in array stoppen
                        array_push($opdrachtenArray, $value);
                        echo '<th class="' . $class_array_opdracht[$mod] . ' unit_' . unit($opdrachtenArray[$opdracht_nr]) . ' vak_'.expertise($opdrachtenArray[$opdracht_nr]).' col_'.$col_nr.'" data-col="'.$col_nr.'"><span>' . $value . '</span></th>';
                        $opdracht_nr++;
                    } else {
                        //echo '<th class="' . $class_array_opdracht[$mod] . '"><span>' . $value . '</span></th>';
                    }
                }
            } else { // student data in de tbody
                if($col_nr < 4){//eerste 4 cellen zijn nr, naam en email
                    echo '<td class="' . $class_array_student[$col_nr] . ' col_'.$col_nr.'" data-col="'.$col_nr.'">' . $value . '</td>';
                } else { //daarna zijn het opdracht cellen: beoordeling, punten toaal, feedback

                    if($mod === 0){//beoordeling

                        if($value == '4' OR $value == '3' OR $value == '2'){//voldoende,goed of excellent
                            $aantal_v++;
                        } elseif ($value == '1'){
                            $aantal_o++;
                        } elseif ($value == '0'){
                            $aantal_n++;
                        } elseif ($value > 0){
                            $onjuiste_beo = TRUE;
                        }

                        echo '<td class="' . $class_array_opdracht[$mod] . ' ' . maBeoordeling($value) . ' unit_' . unit($opdrachtenArray[$opdracht_nr]) . ' vak_'.expertise($opdrachtenArray[$opdracht_nr]).' col_'.$col_nr.'" data-col="'.$col_nr.'"><span class="ruwe_beoordeling">'.$value.'</span>';
                        echo '<a href="#opdracht_detail_div_' . $row . '_' . $opdracht_nr . '" class="toon_info">' .  maBeoordeling($value) . '</a>';
                        
                        $opdracht_detail_div = '<div id="opdracht_detail_div_' . $row . '_' . $opdracht_nr . '" class="opdracht_detail_div"><div class="binnen"><a href="#close" class="sluiten">sluiten</a><h1 class="naam_student">' . $data[$row][0] . ' ' . $data[$row][1] . '</h1><h2>' . $opdrachtenArray[$opdracht_nr] . '</h2><h3>Beoordeling: <span class="beoordeling '.maBeoordeling($value).'">' .  maBeoordeling($value) . '</span></h3>';
                        if (maBeoordeling($value) == 'ongeldig'){
                            $opdracht_detail_div = $opdracht_detail_div . '<p class="ongeldige_beoordeling">Deze beoordeling voldoet niet aan de criteria <i>niet ingeleverd (0), onvoldoende (1) /voldoende (2) /goed (3)/excellent (4)</i>. De beoordeling is: <strong>' . $value . '</strong></p>';
                        }
                        $opdracht_nr++;
                    } elseif ($mod === 1){//totaal aantal te behalen punten. Moet altijd 4 zijn, maar dat is niet altijd zo
                        if($value != 4 AND $value != 0){
                            $onjuist_punten_totaal = TRUE;
                            echo '<div class="beoordeling_fout">!<span>Het totaal te behalen punten voor deze opdracht is <strong>'.$value.'</strong></span></div>';
                            $opdracht_detail_div = $opdracht_detail_div . '<p class="ongeldige_beoordeling">Het totaal aantal te behalen punten voor deze opdracht is niet 4 maar <strong>'.$value.'</storng></p>';
                        }
                    } elseif ($mod === 2){//feedback
                        $opdracht_detail_div = $opdracht_detail_div . '<h3>Feedback</h3><p>' . $value . '</p></div></div>';
                        echo $opdracht_detail_div;
                        echo '</td>';
                        //echo '<td class="' . $class_array_opdracht[$mod] . '">' . $value . '</td>';
                    }
                }
            }
        }   
        
        if($row == 0){//th cellen met aantal beoordelingen
            echo '<th class="aantal aantal_v col_1000" data-col="1000"><span>excellent, goed, voldoende</span></th>';
            echo '<th class="aantal aantal_o col_1001" data-col="1001"><span>onvoldoende</span></th>';
            echo '<th class="aantal aantal_n col_1002" data-col="1002"><span>niet ingeleverd</span></th>';
            echo '</tr>';
            echo '</thead><tbody>';
        } else {
            echo '<td class="aantal aantal_v col_1000" data-col="1000">'.$aantal_v.'</td>';
            echo '<td class="aantal aantal_o col_1001" data-col="1001">'.$aantal_o.'</td>';
            echo '<td class="aantal aantal_n col_1002" data-col="1002">'.$aantal_n.'</td>';
            echo '</tr>';
        }
    }
    ?>
                </tbody>
            </table>
        </section>

        <section class="opdrachten_info">
            <h2>Overzicht klas <?=klas($bestandsnaam);?></h2>
            <h3>Bestand: <?=$bestandsnaam;?></h3>

            <ul>
                <li>Totaal zijn er <?=count($opdrachtenArray);?> opdrachten</li>
<?php
if ($onjuist_punten_totaal){
?>
                <li class="alert">Let op: er zijn opdrachten die niet het totaal van 4 punten als beoordeling hebben. </li>      
<?php
}
if ($onjuiste_beo){
?>
                <li class="alert">Let op: er zijn opdrachten die als beoordeling decimalen bevatten. Deze beoordelingen zijn dus niet onvoldoende, voldoende, goed of excellent maar iets er tussen in. </li>      
<?php
}
?>

            </ul>
            <p>
                <button type="button" class="btn" onclick="window.print();">Printen</button>
            </p>
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

            $('.btn_filter:not(.btn_alles)').click(function(e){ 
                $this = $(this);
                $('.btn_filter').removeClass('selected');
                $this.addClass('selected');
                $class = $this.data('unit');
                $('.opdracht_beoordeling').addClass('hide');
                $('.'+$class).removeClass('hide');
            });

            $('.btn_filter.btn_alles').click(function(e){ 
                $('.opdracht_beoordeling').removeClass('hide');
                $('.btn_filter').removeClass('selected');
            });

            //klikken op naam (de)selecteerd alleen die rij
            $('tbody td.voornaam, tbody td.achternaam').click(function(e){
                $this = $(this);
                $parent = $this.parent();
                if($parent.hasClass('show')){
                    $('tbody tr').removeClass('hide show');
                } else {
                    $('tbody tr').addClass('hide');
                    $parent.removeClass('hide').addClass('show');
                }
            });
            

            $(document).on('keydown', function(e) {
                if(e.key == "Escape") {
                    //$('.opdracht_detail_div:visible a.sluiten').trigger('click');
                    $('.opdracht_detail_div:visible a.sluiten')[0].click();
                }
            });


            //sort table
            $('th').click(function(e){
                var table = $(this).parents('table').eq(0);
                var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()));
                this.asc = !this.asc;
                if (!this.asc){rows = rows.reverse()}
                for (var i = 0; i < rows.length; i++){table.append(rows[i])}
            })
            function comparer(index) {
                return function(a, b) {
                    var valA = getCellValue(a, index), valB = getCellValue(b, index)
                    return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
                }
            }
            function getCellValue(row, index){ 
                //console.log('index: ' + index + ' - ' + $(row).children('td').eq(index).text());
                return $(row).children('td').eq(index).text();
            }
            

            $('th, td').on('mouseover', function(){
                var col = $(this).data('col');
                $('th, td').removeClass('highlight');
                $('.col_' + col).addClass('highlight');
            });


            /* #links springen omhoog, dat is storend */
            $('a.toon_info').on('click',function(e){
                e.preventDefault();
                var $id = $(this).attr('href');
                
                $('div.opdracht_detail_div').removeClass('show_info');
                $('div' + $id).addClass('show_info');
            });
            /* info sluiten*/
            $('a.sluiten').on('click',function(e){
                e.preventDefault();                
                $('div.opdracht_detail_div').removeClass('show_info');
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
        //fgetcsv($handle);// skip eerste row
        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
            array_push($rows, $data);
        }
        fclose($handle);
    }

    //return array_reverse($rows); // verander de volgorde van de array zodat de eerste opdracht bovenaan komt. Met flexobox omdraaien kan ook maar dan kan je geen gebruik maken van page-break
    return $rows; // verander de volgorde van de array zodat de eerste opdracht bovenaan komt. Met flexobox omdraaien kan ook maar dan kan je geen gebruik maken van page-break
}
function unit($str){
    //haal unit uit opdracht naam
    GLOBAL $unit_arr;
    
    $eerste_10 = substr($str, 0, 10);//in de eerste 10 characters moet wel de unit staan. BV: F2M4 VRMG
    
    foreach($unit_arr as $a) {
        if (stripos($eerste_10,$a) !== false) return $a;
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
    
    $eerste_deel = strtolower(substr($str, 0, 11));//in de eerste 11 characters moet wel de expertise staan. BV:  F2M4 DIG Motion G
    //F2M4 BO Motion Graphics = 23 characters
    foreach($expertise_arr as $a) {
        if (stripos($eerste_deel, $a) !== false) {
            
            return str_replace(' ','', $a);
        }
    }
    //return $str;
}   

function maBeoordeling($b){

    if ($b != ''){
        if($b == "0"){
            $beoordeling_tekst = "niet ingeleverd";
        } elseif($b == "1"){
            $beoordeling_tekst = "onvoldoende";
        } elseif($b == "2"){
            $beoordeling_tekst = "voldoende";
        } elseif($b == "3"){
            $beoordeling_tekst = "goed";
        } elseif($b == "4"){
            $beoordeling_tekst = "excellent";
        } else {
            $beoordeling_tekst = "ongeldig";
        }
        return $beoordeling_tekst;
    }   
}

function klas($value){
    // 'Cijfers van ' eraf knippen (12 characters)
    $str = substr($value, 12);
    // 'zoeken waar 'Module ' staat + 5 characters want 'Module 4 - '
    $pos = strpos($str, 'Module');
    $lengte_naam = $pos + 8;
    $str = substr($str, 0, $lengte_naam);
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
