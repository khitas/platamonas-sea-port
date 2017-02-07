<?php
    include "../info.php";

    include "../../includes/settings.php";
    include "../../includes/authentication.php";

    require_once("../../libs/tcpdf/config/lang/eng.php");
    require_once("../../libs/tcpdf/tcpdf.php");

    $title = 'Στοιχεία Σκάφους';

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
    $Column040 = 40;
    $Column060 = 60;
    $Column090 = 90;
    $Column120 = 120;
    $Column140 = 140;
    $Column150 = 150;
    $Column180 = 180;


    $pdf->SetFont('freesans', '', 10);
    $pdf->SetFillColor(228, 223, 222, true);

    $row = $data->rows[0];

    $row->is_fast = ($row->is_fast == 1 ? "ΝΑΙ" : "OXI");


    $pdf->MultiCell($Column040, 6, "Κωδικός : ",                    0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->boat_id,                   0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Όνομα : ",                      0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->name,                      0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Είδος : ",                      0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->boat_kind,                 0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Λιμένας : ",                    0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->boat_port,                 0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Αρ. Εγγραφής : ",               0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->registry_type_number,      0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Ημ. Εγγραφής : ",               0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->registry_date_gr,          0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Αριθμός Α.Μ.Υ.Ε.Ν. : ",         0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->amyen_type_number,         0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Δ.Δ.Σ. : ",                     0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->dds,                       0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Δ.Σ.Π. : ",                     0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->dsp,                       0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Υλικό Κατασκευής : ",           0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->boat_material,             0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Χρώμα : ",                      0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->boat_color,                0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Τύπος : ",                      0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->boat_type,                 0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Κίνηση : ",                     0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->movement_type,             0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Μήκος : ",                      0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->length,                    0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Πλάτος : ",                     0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->width,                     0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Ύψος : ",                       0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->height,                    0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Κατασκευαστής : ",              0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->builder,                   0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Κατάσταση : ",                  0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->boat_status,               0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Α.Ε.Π. : ",                     0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->license_expired_date_gr,   0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Μηχανές : ",                    0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->count_engines,             0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Ιδιοκτήτες : ",                 0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->count_owners,              0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Ταχύπλοο : ",                   0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 6, $row->is_fast,                   0, 'L', 0, 1, 0, 0, true, 0);

    $pdf->MultiCell($Column040, 6, "Σχόλια : ",                     0, 'R', 0, 0, 0, 0, true, 0);
    $pdf->MultiCell($Column140, 12, $row->comments,                 0, 'L', 0, 1, 0, 0, true, 0);


    $pdf->Output( $packages[$package]["name"].".pdf", 'I');
?>