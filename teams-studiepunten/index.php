<!doctype html>
<?php
//die("hellepie");
session_start();

$opdrachtenArray = array();

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
    <title>Teams Studiepunten per klas</title>

    <meta name="keywords" content="Opdrachten Mediacollege Mediavormgever">
    <meta name="description" content="Teams Assignments interpreteren">
    <meta name="author" content="Bethuel Heldt">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../js/jquery-3.6.0.min.js"></script>

    <link href="../css/style_all.css" rel="stylesheet" type="text/css">
    <link href="../css/style-studiepunten.css" rel="stylesheet" type="text/css">
</head>

<body>

    <div class="nav">
        <a href="../teams-opdrachten-student/" class="btn">Opdrachten per student</a>
        <a href="../teams-opdrachten-klas/" class="btn">Opdrachten per klas</a>
        <a href="../teams-studiepunten/" class="btn selected">Studiepunten per klas</a>
    </div>
    
    <div class="opdrachten_container">

        <section class="formulier">
            <h2>CSV bestand uploaden</h2>
            <p>Download de resultaten vanuit de <strong>studiepunten tegel</strong> in Teams van de hele klas. Klik op 'Exporteren naar Excel'  (rechtsboven in Teams) bij <strong>cijfers/grades</strong>. Upload het csv bestand hieronder.</p>
            <form action="../upload.php" method="post" enctype="multipart/form-data" id="uploadform">
                <label for="fileToUpload" class="custom-file-upload btn">
                    Selecteer een csv bestand
                </label>
                <input type="hidden" name="return_page" id="return_page" value="teams-studiepunten/index.php">
                <input type="file" name="fileToUpload" id="fileToUpload" accept=".csv" placeholder="Selecteer een csv bestand" class="btn">
                <button type="submit" name="submit" id="opslaan_knop" class="btn">Upload bestand</button>
            </form>
            <h3><? echo $msg_tekst ?></h3>
        </section>
<?php
if ($_SESSION["csv_file"] == "") {
    $title_tag = 'Studiepunten per klas';
?>
        <h1 class="pag_titel">Studiepunten per klas</h1>
    </div>
<?php
} else {
?>
        <div class="btns">
            <button data-unit="unit_alles" class="btn btn_alles btn_filter">alle units</button>
            <button data-unit="unit_BO" class="btn btn_bo btn_filter">BO</button>
            <button data-unit="unit_VRMG" class="btn btn_vrmg btn_filter">VRMG</button>
            <button data-unit="unit_REA" class="btn btn_rea btn_filter">REA</button>
            <button data-unit="unit_DIG" class="btn btn_dig btn_filter">DIG</button>
            <button data-unit="unit_AVO" class="btn btn_avo btn_filter">AVO</button>
            <button data-unit="unit_FLEX" class="btn btn_flex btn_filter">FLEX</button>
            <button data-unit="unit_SLB" class="btn btn_slb btn_filter">SLB</button>

            <br>
            <button data-unit="vak_ontwerpproces" class="btn btn_vak btn_filter">ontw.pr.</button>
            <button data-unit="vak_communicatie" class="btn btn_vak btn_filter">Com.</button>
            <button data-unit="vak_experiment" class="btn btn_vak btn_filter">Exp.</button>
            <button data-unit="vak_vrmg" class="btn btn_vak btn_filter">VRMG</button>
            <button data-unit="vak_werkplaats" class="btn btn_vak btn_filter">Werkpl.</button>
            <button data-unit="vak_dtp" class="btn btn_vak btn_filter">DTP</button>
            <button data-unit="vak_uxd" class="btn btn_vak btn_filter">UXD</button>
            <button data-unit="vak_motiongraphics" class="btn btn_vak btn_filter">MG</button>
            <button data-unit="vak_web" class="btn btn_vak btn_filter">WEB</button>
            <button data-unit="vak_ned" class="btn btn_vak btn_filter">NED</button>
            <button data-unit="vak_engels" class="btn btn_vak btn_filter">ENG</button>
            <button data-unit="vak_taalvaardigheid" class="btn btn_vak btn_filter">TV</button>
            <button data-unit="vak_rek" class="btn btn_vak btn_filter">REK</button>
        </div>

        <section class="opdrachten_lijst">
            <table>
<?php
    $bestandsnaam = $_SESSION['csv_file'];
    $opdracht_detail_div = '';
    $data = loadCSV('../uploads/' . $bestandsnaam);

    $class_array_student = array('nr','voornaam','achternaam','email');
    $class_array_opdracht = array('opdracht_beoordeling','punten','feedback');

    $is_totaal = FALSE;
    $totaal_M1 = 0;
    $totaal_M2 = 0;
    $totaal_M3 = 0;
    $totaal_M4 = 0;
    if (stripos($bestandsnaam,'Cijfers van ED') !== FALSE) $max_studiepunten = 24;
    else $max_studiepunten = 26;
    
    $tachtig_procent = (int)($max_studiepunten * .8);
    //echo 'tachtig_procent'. $tachtig_procent;
    
    $naam_klas = substr($bestandsnaam, 12, 4);
    $title_tag = 'Studiepunten ' . $naam_klas;

    for($row = 0; $row < count($data); $row++) {
        if($row === 0){
            echo '<thead><tr><th class="nr col_0" data-col="0"><span></span></th>';
        } else {
            echo '<tr><td class="nr col_0" data-col="0">' . $row . '</td>';
        }
        $col_nr = 0;
        $opdracht_nr = 0;

        $is_totaal = FALSE;
        $totaal_M1 = 0;
        $totaal_M2 = 0;
        $totaal_M3 = 0;
        $totaal_M4 = 0;

        foreach ($data[$row] as $col => $value) {
            $col_nr++;
            $mod = ($col_nr-1) % 3;//1ste 2de of 3de item van class_array_opdracht berekenen met modulo

            if($row === 0){ //bovenste rij, met titels
                if($col_nr < 4){//eerste 4 cellen zijn nr, naam en email
                    echo '<th class="' . $class_array_student[$col_nr] . ' col_'.$col_nr.'"><span>' . $value . '</span></th>';

                } else { //cellen met opdracht, totaal punten en feedback
                    if($mod == 0){// opdracht titel in array stoppen
                        array_push($opdrachtenArray, $value);
                        //totaal anders tonen dan studiepunten per expertise
                        if (stripos($opdrachtenArray[$opdracht_nr], 'Totaal') !== false) $is_totaal = TRUE;
                        else $is_totaal = FALSE;

                        echo '<th class="' . $class_array_opdracht[$mod] . ' unit_' . unit($opdrachtenArray[$opdracht_nr]) . ' vak_'.expertise($opdrachtenArray[$opdracht_nr]).' col_'.$col_nr.' '. ($is_totaal ? 'totaal':'') .'" data-col="'.$col_nr.'"><span>' . $value . '</span></th>';
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

                        //totaal anders tonen dan studiepunten per expertise
                        if (stripos($opdrachtenArray[$opdracht_nr], 'Totaal') !== false) $is_totaal = TRUE;
                        else $is_totaal = FALSE;

                        if ($is_totaal){//totaal van deze module
                            echo '<td class="' . $class_array_opdracht[$mod] . ' totaal col_'.$col_nr.' ' . ($value >=$tachtig_procent ? 'behaald':'') . '" data-col="'.$col_nr.'">' . ($value != '' ? '<span>' .  $value . '</span>':'');

                        } else {//geen totaal, studiepunt
                            if (empty($value)){
                                //$value = '?';
                            }

                            echo '<td class="' . $class_array_opdracht[$mod] . ' ' . maBeoordeling($value) . ' unit_' . unit($opdrachtenArray[$opdracht_nr]) . ' vak_'.expertise($opdrachtenArray[$opdracht_nr]).' col_'.$col_nr.'" data-col="'.$col_nr.'">';
                            //echo 'V='.$value;
                            echo '<a href="#opdracht_detail_div_' . $row . '_' . $opdracht_nr . '">' .  $value . '</a>';        
                            
                            //check of studiepunt klopt met te behalen studiepunten voor deze expertise
                            if (check_studiepunt(expertise($opdrachtenArray[$opdracht_nr]),$value) === FALSE){
                                echo '<div class="beoordeling_fout">!<span>Het totaal te behalen studiepunten voor deze expertise klopt niet.</span></div>';
                            } 
                   
                            $opdracht_detail_div = '<div id="opdracht_detail_div_' . $row . '_' . $opdracht_nr . '" class="opdracht_detail_div"><div class="binnen"><a href="#close" class="sluiten">sluiten</a><h1 class="naam_student">' . $data[$row][0] . ' ' . $data[$row][1] . '</h1><h2>' . $opdrachtenArray[$opdracht_nr] . '</h2><h3>Beoordeling: <span class="beoordeling '.maBeoordeling($value).'">' .  maBeoordeling($value) . '</span></h3>';

                            //totalen berekenen
                            if (!empty($value)){
                                if (stripos($opdrachtenArray[$opdracht_nr], 'M1') !== false){//module 1
                                    $totaal_M1 = $totaal_M1 + $value;
                                } elseif (stripos($opdrachtenArray[$opdracht_nr], 'M2') !== false){//module 2
                                    $totaal_M2 = $totaal_M2 + $value;
                                } elseif (stripos($opdrachtenArray[$opdracht_nr], 'M3') !== false){//module 3
                                    $totaal_M3 = $totaal_M3 + $value;
                                } elseif (stripos($opdrachtenArray[$opdracht_nr], 'M4') !== false){//module 4
                                    $totaal_M4 = $totaal_M4 + $value;
                                }
                            }
                        }
                        $opdracht_nr++;
                    } elseif($mod ===1){//points totaal
                        
                    } elseif($mod === 2){//feedback
                        $opdracht_detail_div = $opdracht_detail_div . '<h3>Feedback</h3><p>' . $value . '</p></div></div>';
                        if ($is_totaal === FALSE) {
                            echo $opdracht_detail_div;
                        }
                        echo '</td>';
                        //echo '<td class="' . $class_array_opdracht[$mod] . '">' . $value . '</td>';
                    }
                }
            }
        }   
        
        if($row == 0){//th cellen met totaal_berekend beoordelingen
            echo '<th class="totaal_berekend totaal_berekend_M1 col_1000" data-col="1000"><span>Totaal Module 1 (berekend)</span></th>';
            echo '<th class="totaal_berekend totaal_berekend_M2 col_1001" data-col="1001"><span>Totaal Module 2 (berekend)</span></th>';
            echo '<th class="totaal_berekend totaal_berekend_M3 col_1002" data-col="1002"><span>Totaal Module 3 (berekend)</span></th>';
            echo '<th class="totaal_berekend totaal_berekend_M4 col_1003" data-col="1003"><span>Totaal Module 4 (berekend)</span></th>';
            echo '<th class="totaal_berekend totaal_berekend_al col_1004" data-col="1004"><span>Totaal alle modules (berekend)</span></th>';
            echo '</tr>';
            echo '</thead><tbody>';
        } else {
            $totaal_al = $totaal_M1+$totaal_M2+$totaal_M3+$totaal_M4;
            echo '<td class="' . (($totaal_M1 >= $tachtig_procent) ? 'behaald':'') .' totaal_berekend totaal_berekend_M1 col_1000" data-col="1000"><span>'.$totaal_M1.'</span></td>';
            echo '<td class="' . (($totaal_M2 >= $tachtig_procent) ? 'behaald':'') .' totaal_berekend totaal_berekend_M2 col_1001" data-col="1001"><span>'.$totaal_M2.'</span></td>';
            echo '<td class="' . (($totaal_M3 >= $tachtig_procent) ? 'behaald':'') .' totaal_berekend totaal_berekend_M3 col_1002" data-col="1002"><span>'.$totaal_M3.'</span></td>';
            echo '<td class="' . (($totaal_M4 >= $tachtig_procent) ? 'behaald':'') .' totaal_berekend totaal_berekend_M4 col_1003" data-col="1003"><span>'.$totaal_M4.'</span></td>';
            echo '<td class="' . (($totaal_al >= $tachtig_procent*4) ? 'behaald':'') .' totaal_berekend totaal_berekend_al col_1004" data-col="1004"><span>'.$totaal_al.'</span></td>';
            echo '</tr>';
        }
    }
    ?>
                </tbody>
            </table>
        </section>

        <section class="opdrachten_info">
            <h2>Studiepunten klas <?=klas($bestandsnaam);?></h2>
            <h3>Bestand: <?=$bestandsnaam;?></h3>

            <ul>
                <li>Totaal zijn er <?=count($opdrachtenArray) - 4;?> expertises waarvoor studiepunten zijn toegekend</li>
                <li>Per module kan een student <?=$max_studiepunten;?> behalen</li>
                <li>Behaalt een student 80% of meer van de studiepunten (<?=$tachtig_procent;?> studiepunten) dan is de module behaald.</li>

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


            //zet in tilte de naam_student en naam_module
            $('title').text('<?=$title_tag;?>');

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
    //$eerste_10 = substr($str, 0, 10);//in de eerste 10 characters moet wel de unit staan. BV: F2M4 VRMG
    $unit_arr = array("AVO", "VRMG", "BO", "DIG", "REA","SLB","FLEX");
    foreach($unit_arr as $a) {
        if (stripos($str,$a) !== false) return $a;
    }
    //return $str;
}   
function expertise($str){
    //return;
    //haal expertise uit opdracht naam
    $expertise_arr = array("ontwerpproces", "communicatie", "experiment", "vrmg", "dtp", "werkplaats", "uxd", "motion graphics", "web", "ned", "rek", "engels", "taalvaardigheid","slb","flex");
    //$eerste_25 = strtolower(substr($str, 0, 25));//in de eerste 25 characters moet wel de expertise staan. BV:  F2M4 DIG Motion G
    //F2M4 BO Motion Graphics = 23 characters
    foreach($expertise_arr as $a) {
        if (stripos($str, $a) !== false) {
            return str_replace(' ','', $a);
        }
    }
    //return $str;
}   

function maBeoordeling($b){

    if ($b != ''){
        if($b == "0" OR $b == ""){
            $beoordeling_tekst = "niet behaald";
        } else {
            $beoordeling_tekst = "behaald";
        }
        return $beoordeling_tekst;
    }   
}

function check_studiepunt($opdr, $val){
    $expertise_1 = array("ned","rek","engels","taalvaardigheid","slb","tava");
    $expertise_2 = array("ontwerpproces","communicatie","experiment","werkplaats","uxd", "motiongraphics","web","flex");
    $expertise_3 = array("dtp");
    $expertise_4 = array("vrmg");
    //print "val: " . $val;
    $klopt = FALSE;
    if (empty($val)) {//ja klopt, 0 studiepunten halen kan altijd
        $klopt = TRUE;
    } else {
        
        if (
            ($val == 1 AND in_array($opdr, $expertise_1)) OR 
            ($val == 2 AND in_array($opdr, $expertise_2)) OR 
            ($val == 3 AND in_array($opdr, $expertise_3)) OR 
            ($val == 4 AND in_array($opdr, $expertise_4))
            ){
            $klopt = TRUE;
        } else {
            //print "hier?";
            $klopt = FALSE;//niet de juiste studiepunten bij de expertise
        }
    }
    return $klopt;
}

function klas($value){
    // 'Cijfers van ' eraf knippen (12 characters)
    $str = substr($value, 12);
    $str = substr($str, 0, 4);
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
