<?php
    include "../info.php";

    include "../../includes/settings.php";
    include "../../includes/authentication.php";

    require_once("../../libs/tcpdf/config/lang/eng.php");
    require_once("../../libs/tcpdf/tcpdf.php");

    $title = 'Στοιχεία Μηχανής';

    $file = $AppVars["SERVER_HOST"].$packages[$package]["parent"].$packages[$package]["path"]."data/list.php";
    $curl = curl_init($file);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, $User["username"].":".$User["password"]);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $_GET);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $data = curl_exec($curl);
    $data = json_decode($data);


    $pdf = new TCPDF( 'P', 'mm', 'A4', true, 'ISO-8859-7', false);

    // set document information
    $pdf->SetCreator("TCPDF");
    $pdf->SetAuthor("khitas");
    $pdf->SetTitle( $title );
    $pdf->SetSubject( $title );
    //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

    // set default header data
    $pdf->SetHeaderData( "../../images/logo.png", "15", "", "", "", "30", "", "");
    //$pdf->FooterMessage = "Οι υπηρεσίες και τα στοιχεία και που σας χορηγούνται είναι αυστηρώς προσωπικά και πρέπει να χρησιμοποιούνται αποκλειστικά και μόνο για την κάλυψη των εκπαιδευτικών/διοικητικών  αναγκών σας στο Ίδρυμα. Σε καμία περίπτωση δεν πρέπει να χρησιμοποιηθούν για προσβολή συστημάτων του ΤΕΙ ή συστημάτων άλλων δικτύων. (Στην περίπτωση αυτή, οι διαχειριστές έχουν το δικαίωμα της διακοπής χωρίς προηγούμενη ειδοποίηση).";
    $pdf->ShowPageNumbers = true;

    // set header and footer fonts
    $pdf->setHeaderFont( Array('freesans', '', 10) );
    $pdf->setFooterFont( Array('freesans', '', 10) );

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont('freesans');

    //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetMargins(15, 35, 15);


    $pdf->SetHeaderMargin(10);
    $pdf->SetFooterMargin(10);

    //set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 25);

    //set image scale factor
    $pdf->setImageScale('courier');

    //set some language-dependent strings
    $pdf->setLanguageArray($l);

    // ---------------------------------------------------------

    $pdf->SetFont('freesans', '', 14);

    $pdf->AddPage();
    $pdf->Cell( 0, 20, $title, 0, 1, "C" );

    $Column010 = 10;
    $Column020 = 20;
    $Column021 = 25;
    $Column030 = 30;
    $Column031 = 40;
    $Column060 = 60;
    $Column090 = 90;
    $Column120 = 120;
    $Column150 = 150;
    $Column180 = 180;


    $pdf->SetFont('freesans', '', 10);
    $pdf->SetFillColor(228, 223, 222, true);
    
    $row = $data->rows[0];

    $pdf->MultiCell($Column030, 6, "Κωδικός : ",        0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column150, 6, $row->engine_id,      0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column030, 6, "Σειριακός Αρ. : ",        0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column150, 6, $row->serial_number,      0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column030, 6, "Τύπος : ",          0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column150, 6, $row->engine_type,     0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column030, 6, "Είδος : ",      0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column150, 6, $row->engine_kind,    0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column030, 6, "Δύναμη : ",         0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column150, 6, $row->engine_power,           0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column030, 6, "Μάρκα : ",         0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column150, 6, $row->engine_brand,           0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column030, 6, "Κατάσταση : ",      0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column150, 6, $row->engine_status,       0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column030, 6, "Σκάφη : ",       0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column150, 6, $row->count_boats,         0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column030, 6, "Ιδιοκτήτες : ",         0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column150, 6, $row->count_owners,        0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column030, 6, "Σχόλια : ",         0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column150, 12, $row->comments,     0, 'L', 0, 1, 0, 0, true, 0);


    $pdf->Output( $packages[$package]["name"].".pdf", 'I');
?>