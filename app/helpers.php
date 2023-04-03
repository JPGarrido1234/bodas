<?php

use Nette\Utils\ArrayList;
use Barryvdh\DomPDF\Facade\PDF;

function user() {
    return auth()->user();
}

function boda() {
    return user()->boda;
}

function tooltip($string = '') {
    return 'data-bs-toggle="tooltip" data-bs-placement="top" title="'.$string.'"';
}

function viewPDF($url = null) {
    $prefix = 'https://docs.google.com/viewer?url=';
    $http = '';
    
    if(strpos($url, 'http') === 0) { $http = ''; } else { $http = 'https://bodas.bodegascampos.com'; }
    return $prefix.$http.$url;
}

function validateDNI($value) {
        $pattern = "/^[XYZ]?\d{5,8}[A-Z]$/";
        $dni = strtoupper($value);
        if(preg_match($pattern, $dni))
        {
            $number = substr($dni, 0, -1);
            $number = str_replace('X', 0, $number);
            $number = str_replace('Y', 1, $number);
            $number = str_replace('Z', 2, $number);
            $dni = substr($dni, -1, 1);
            $start = $number % 23;
            $letter = 'TRWAGMYFPDXBNJZSQVHLCKET';
            $letter = substr('TRWAGMYFPDXBNJZSQVHLCKET', $start, 1);
            if($letter != $dni)
            {
              //echo 'Wrong ID, the letter of the NIF does not correspond';
              return false;
            } else {
              //echo 'Correct ID';
              return true;
            }
        }else{
            //echo 'Wrong ID, invalid format';
            return false;
        }
}

function generateUsername() {
    $a = ['bodas', 'bodegas', 'campos', 'evento'];
    $b = rand(100, 999);
    $result = $a[array_rand($a)].$b;

    if(App\Models\User::where('username', $result)->exists()) {
        generateUsername();
    }
    
    return $result;
}

function generatePassword($length = 10) {
    $data = '1234567890abcefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($data), 0, $length);
}

function addActivity($description = null, $boda_id = null, $user_id = null) {
    \App\Models\Activity::create(['boda_id' => $boda_id, 'user_id' => $user_id, 'description' => $description]);
}

function percentageBetweenDates($FirstDate, $SecondDate) {
    
    $start = strtotime($FirstDate);
    $finish = strtotime($SecondDate);

    $diff = $finish - $start;

    $progress = time() - $start;
    $procent = ($progress / $diff) * 100;

    $width = round($procent);

    if ($width >= 100)
    {
        $result = '100';
    }
    else
    {
        $result = $width;
    }

    return $result;
}

function getMail($type) {
    $mail = \App\Models\Email::where('type', $type)->first();
    return $mail;
}

function splitVariables($data) {
    return array_values(array_filter($data, function($v) {
        return $v[0] !== '_';
    }));
}

function readDocx($filePath) {
    // Create new ZIP archive
    $zip = new ZipArchive;
    $dataFile = 'word/document.xml';
    // Open received archive file
    if (true === $zip->open($filePath)) {
        // If done, search for the data file in the archive
        if (($index = $zip->locateName($dataFile)) !== false) {
            // If found, read it to the string
            $data = $zip->getFromIndex($index);
            // Close archive file
            $zip->close();
            // Load XML from a string
            // Skip errors and warnings
            $xml = DOMDocument::loadXML($data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
            // Return data without XML formatting tags

            $contents = explode('\n',strip_tags($xml->saveXML()));
            $text = '';
            foreach($contents as $i=>$content) {
                $text .= $contents[$i];
            }
            return $text;
        }
        $zip->close();
    }
    // In case of failure return empty string
    return "";
}

function DocToPDF($doc_path, $pdf_path, $filename = null) {
        if($filename == null) { $filename = basename($doc_path, '.docx'); }
        $domPdfPath = base_path('vendor/dompdf/dompdf');
        \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
        \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
        $content = \PhpOffice\PhpWord\IOFactory::load($doc_path); 
        $PDFWriter = \PhpOffice\PhpWord\IOFactory::createWriter($content,'PDF');
        $PDFWriter->save($pdf_path.$filename.'.pdf');
        //PDF::loadHTML($content)->setPaper('a4')->setOrientation('landscape')->setOption('margin-bottom', 0)->save($pdf_path.$filename.'.pdf');
}

function ReadDoc($doc_path){
    $body = '';
    $phpWord = \PhpOffice\PhpWord\IOFactory::createReader('Word2007')->load($doc_path);
    foreach ($phpWord->getSections() as $section) {
        foreach ($section->getElements() as $element) {
            if(method_exists($element, 'getElements')){
                if(count($element->getElements()) > 0){
                    foreach ($element->getElements() as $item) {
                        if(method_exists($item, 'getText')){
                            $body .= $item->getText();
                        }
                    }
                }
                $body .= '\n';   
            }
        }
    }
    return $body;
}

function tiposFactura() {
    return [
        'contrato_celebracion' => 'Reserva',
        'prueba_menu' => 'Prueba de menú',
        'cobro_60' => 'Cobro 60% contrato',
        'pago_final' => 'Pago final'
    ];
}

function filterTypeFacture($cobro_id){
    $array = array();
    $cobros_factura = App\Models\Cobro::where('boda_id', '=', $cobro_id)->get();
    foreach(tiposFactura() as $tipo => $value){
        $existe = false;
        foreach($cobros_factura as $cobro) { 
            if($tipo == $cobro->type){
                $existe = true;
            }
        }

        if($existe == false){
            $tipo = array(
                $tipo => $value
            );
            array_push($array, $tipo);
        }
    }

    //dd($array);
    //dd(tiposFactura());
    return $array;
}

function estadosFactura() {
    $estados = ['pending' => 'Pendiente', 'completed' => 'Completado'];
    return $estados;
}