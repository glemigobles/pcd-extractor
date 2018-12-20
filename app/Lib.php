<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\IOFactory;
use DB;
use Storage;

class Lib extends Model
{
    public function createrap($context)
    {
        $filename=request('filename');
        $fileurl=request('fileUrl');
        //odczytaj adresy z pliku wejsciowego i dodaj je do tablicy
        $sites=$this->readxls($fileurl);
        //przeszukaj każdą stronę z tablicy i dodaj do obiektu   
        $clients=array();
        foreach(array_chunk($sites,25) as $data){
        $clientschunk=$this->multirequest($data,null);
        $clients=array_merge($clients,$clientschunk);
        }
        //dd($clients);
        
        //utwórz raport
        $xlsx=$this->createxls($clients);
        
        if(Storage::disk('public')->exists($xlsx)){
            return Storage::disk('public')->url($xlsx);
        }
        else{
            return 'blad/brak pliku';
        }
    }
    public function readxls($fileurl){
        $inputxls= storage_path('app/'.$fileurl); 
        $spreadsheet = IOFactory::load($inputxls);

        $sites=[];
        $worksheet = $spreadsheet->getActiveSheet();
        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE); 

            foreach ($cellIterator as $cell) {
                    array_push($sites, $cell->getValue());
            }

        }
        return $sites;
    }

    public function createxls($clients)
    {
        $inputxls= storage_path('app/public/raport.xlsx');  
         //load document
        $spreadsheet = IOFactory::load($inputxls);
        $rand=str_random(10);
        $outputxls=storage_path('app/public/raport'.$rand.'.xlsx');
        $row=2;
        foreach($clients as $client){
                //nazwa
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $client['name']);
                //url
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $client['url']);
                //tel
                //$tel=json_encode($client['phone']);
                $tel="";
                if(count($client['phone'])>1){
                    foreach($client['phone'] as $p){
                        $tel.=$p.', ';
                    }
                }else{
                    $tel=json_encode($client['phone']);
                }
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(3, $row, trim($tel,'"["]"'));
                //email
                $email=json_encode($client['email']);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(4, $row, trim($email,'"["]"'));
                //adres
                $adress=$client['adress'];
                $adress=str_replace('streetAddress','',$adress);
                $adress=str_replace('addressLocality','',$adress);
                $adress=str_replace('addressRegion','',$adress);
                $adress=str_replace('postalCode','',$adress);
                $adress=str_replace('"','',$adress);
                $adress=str_replace(':','',$adress);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $adress);
                $row++;
        }
     
        //save document
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save( $outputxls );

        return 'raport'.$rand.'.xlsx';

    }
    public function multirequest($data, $options = array()) 
    {
    // array of curl handles
    $curly = array();
    // data to be returned
    $result = array();
    
    // multi handle
    $mh = curl_multi_init();
    
    // loop through $data and create curl handles
    // then add them to the multi-handle
    foreach ($data as $id => $d) {
    
        $curly[$id] = curl_init();
    
        $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
        curl_setopt($curly[$id], CURLOPT_URL,            $url);
        curl_setopt($curly[$id], CURLOPT_HEADER,         0);
        curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curly[$id], CURLOPT_CONNECTTIMEOUT, 4);
    
        // // post?
        // if (is_array($d)) {
        // if (!empty($d['post'])) {
        //     curl_setopt($curly[$id], CURLOPT_POST,       1);
        //     curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
        // }
        // }
    
        // // extra options?
        // if (!empty($options)) {
        // curl_setopt_array($curly[$id], $options);
        // }
    
        curl_multi_add_handle($mh, $curly[$id]);
    }
    
    // execute the handles
    $running = null;
    do {
        curl_multi_exec($mh, $running);
    } while($running > 0);
    
    
    // get content and remove handles
    foreach($curly as $id => $c) {
        $result[$id] = curl_multi_getcontent($c);
        curl_multi_remove_handle($mh, $c);
    }
    $clients=[];
    $i=0;
    foreach($result as $id =>$a){    
        $client=array('name'=>'','url'=>'','email'=>array(),'phone'=>array(),'adress'=>'');
                     //nazwa
                    $res = preg_match(
                    "/<title>(.*)<\/title>/siU",
                    $a,
                    $matches
                    );
                    if ($res) {
                        $title = preg_replace('/\s+/', ' ', $matches[0]);
                        $title = trim($title);
                        $title=strip_tags($title);
                        $client['name']=$title;
                    }
                    //url
                    $client['url']=$data[$i];
                    //email
                    $res = preg_match(
                    "/[a-z0-9]+[_a-z0-9.-]*[a-z0-9]+@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})/i",
                    $a,
                    $matches
                    );
                    if ($res) {
                        array_push($client['email'], $matches[0]);
                    }
                    //tel
                    $res = preg_match_all(
                    //"/(?:\d{1}\s)?\(?(\d{3})\)?-?\s?(\d{3})-?\s?(\d{4})/",
                    "/href=\"tel:(.*)\"/siU",
                    $a,
                    $matches
                    );
                    if ($res) {
                        foreach(array_unique($matches[0]) as $phone){
                            $p=substr_replace($phone, '',0,10 );
                            array_push($client['phone'], substr_replace($p,'',-1,1));
                        }
                    }
                    //adress
                    $res = preg_match(
                    "/\"streetAddress\":(.*)[A-Z][0-9][A-Z] ?[0-9][A-Z][0-9]/",
                    $a,
                    $matches
                    );
                    if ($res) {
                        $client['adress']=$matches[0];
                    }
                    
                    //dodaj do tablicy klientow
                    array_push($clients, $client);
                    $i++;
    }
    
    // all done
    curl_multi_close($mh);

    return $clients;
    }
}