<?php
include"inc/conf.php";

include"inc/phpqrcode/qrlib.php";
require"inc/fpdf/Fpdf.php";

/**
* fpdf
*/
class PDF extends fpdf
{

	// Page footer
	function Footer()
	{
	    // Position at 1.5 cm from bottom
	    $this->SetY(-1.4);
	    // Arial italic 8
	    $this->SetFont('courier','',8);
	    // Page number
        $this->SetFillColor(194, 194, 194);
        $this->SetTextColor(0);
	    $this->Cell(0,0.4,'Cetaklah kartu ujian ini mengguanakan printer berwarna.',0,1, 'L', true);
	}

	
}
    $pdf = new PDF('P', 'cm');
    $pdf->SetMargins(0.1, 0.1, 0.1);
    $pdf->AliasNbPages();
    $pdf->AddPage();

    $rsql = mysql_query("SELECT * FROM qrcode WHERE id_qrcode = '$_GET[id]'") or die(mysql_error());

    if (mysql_num_rows($rsql) > 0) {
        $a = mysql_fetch_assoc($rsql);


        $path = "./qrcode_s/";
        $file = $path.$a['code'].".png";
        // outputs image directly into browser, as PNG stream 
        QRcode::png($a['code'], $file, QR_ECLEVEL_H, 5);

        //-------------------------------
        //$pdf->setFont('courier', 'B', 14);
        $pdf->setFont('courier', 'B', 14);
        $pdf->Ln(0.3);
        $pdf->SetX(2);
        $pdf->cell(8,0.5,"Print QR Code",0,0);

        
        $pdf->cell(5.3,0.6,"No.".$a['code'],1,1, 'C');
        $pdf->Image('./qrcode_s/'.$a['code'].'.png', 14.5, 1.3, 2, 2);


        $pdf->setFont('courier', 'B', 11);
        $pdf->cell(0,0.9,"KARTU QRCode",0,1, 'C');

        $pdf->Ln(0.5);
        $pdf->setFont('courier', '', 9);
        
        $pdf->cell(4,0.5,"Code",0,0);
        $pdf->cell(0.5,0.5,":",0,0);
        $pdf->MultiCell(5.3,0.5,$a['code'],0,1);

        
        $pdf->cell(4,0.5,"Nama",0,0);
        $pdf->cell(0.5,0.5,":",0,0);
        $pdf->MultiCell(5.3,0.5,$a['nama'],0,1);

        
        $pdf->cell(4,0.5,"Keterangan",0,0);
        $pdf->cell(0.5,0.5,":",0,0);
        $pdf->MultiCell(5.3,0.5,$a['keterangan'],0,1);
    }
        

	


    $pdf->Output();
	//$pdf->Output('D', $data_formulir->no_tes.'.pdf');
?>