<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;
use Session;
class PagesController extends Controller
{
    public function uploadFile(Request $request){

        if ($request->input('submit') != null ){

            $file = $request->file('file');

            // File Details
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();

            // Valid File Extensions
            $valid_extension = array("csv");

            // 2MB in Bytes
            $maxFileSize = 2097152;

            // Check file extension
            if(in_array(strtolower($extension),$valid_extension)){

                // Check file size
                if($fileSize <= $maxFileSize){

                    // File upload location
                    $location = 'uploads';

                    // Upload file
                    $file->move($location,$filename);

                    // Import CSV to Database
                    $filepath = public_path($location."/".$filename);

                    // Reading file
                    $file = fopen($filepath,"r");

                    $importData_arr = array();
                    $i = 0;

                    while (($filedata = fgetcsv($file, 1000, ";")) !== FALSE) {
                        $num = count($filedata );

                        // Skip first row (Remove below comment if you want to skip the first row)
                        /*if($i == 0){
                           $i++;
                           continue;
                        }*/
                        for ($c=0; $c < $num; $c++) {
                            $importData_arr[$i][] = $filedata [$c];
                        }
                        $i++;
                    }
                    fclose($file);

                    // Insert to MySQL database
                    foreach($importData_arr as $importData){

                        $insertData = array(
                            "voornaam"=>$importData[0],
                            "achternaam"=>$importData[1],
                            "mail"=>$importData[2],

                            "afstudeerjaar"=>$importData[3],
                            "created_at"=>now());

                        Page::insertData($insertData);

                    }

                    Session::flash('message','Importeren gelukt.');
                }else{
                    Session::flash('message','Bestand is te groot. Het bestand moet kleiner zijn dan 2MB.');
                }

            }else{
                Session::flash('message','Verkeerde bestand extensie.');
            }

        }

        // Redirect to index
        return redirect()->action('AlumniController@index');
    }
}
