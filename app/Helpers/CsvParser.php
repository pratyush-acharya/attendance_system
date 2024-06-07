<?php

namespace App\Helpers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Batch;

class CsvParser{

    /**
     * defines to which model data is to be stored
     *
     * @var mixed
     */
    public $model;
    
    /**
     * defines the model namespace assosciated to the model
     *
     * @var mixed
     */
    public $modelNamespace;

    /**
     * defines the request namespace assosciated to the model
     *
     * @var mixed
     */
    public $requestNamespace;

    public function __construct($model)
    {
        $this->model = $model;
        $this->modelNamespace = 'App\\Models\\'.$model;
        $this->requestNamespace = 'App\\Http\\Requests\\'.$model.'Request';
    }
    
    public function import($filePath, $delimiter = ',')
    {
        //check if the file exists and is readable
        if(!file_exists(storage_path().'/app/'.$filePath) || !is_readable(storage_path().'/app/'.$filePath)){

            return FALSE;
        }
        //initialize header
        $header = NULL;
        // initialize data array to store
        $data = array();
        //open the file
        if (($handle = fopen(storage_path().'/app/'.$filePath, 'r')) !== FALSE)
        {
            //get the row from the csv file
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
            {   
                //if header is not set, then take the current row as header
                if(!$header){

                    $header = $row;
                }
                else{
                    //create an assosciative array of header and row
                    $data[] = array_combine($header, $row);
                }
            }
            //close the file
            fclose($handle);
        }
        //dynamically create the model validation
        $dataRequestValidation =  new $this->requestNamespace;
        //create new request
        $request = new Request();

        //first validate all the data
        foreach($data as $entry){
            //enter the each entry in the request
            $request->replace($entry);
            //validate the data in the request
            $request->validate($dataRequestValidation->rules());
        }
        //now try and catch using db transaction
        DB::beginTransaction();
        foreach($data as $entry){

            try{
                $newData = $this->modelNamespace::create($entry);
              
            }catch( Exception $e){
                DB::rollBack();
                return redirect()->route('subject.list')->with('errors','Oops! Error Occured! Please try again');
            }
        }
        //now commit
        DB::commit();
    
    }

}
?>