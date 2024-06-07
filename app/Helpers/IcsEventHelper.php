<?php

namespace App\Helpers;

class IcsEventHelper {

    /* Function is to get all the contents from ics and explode all the datas according to the events and its sections */
    function getIcsEventsAsArray($file) {
        $icalString = file_get_contents ( $file );
        $icsDates = array ();
        
        $icsData = explode ( "BEGIN:", $icalString );
       
        foreach ( $icsData as $key => $value ) {
            $icsDatesMeta [$key] = explode ( "\n", $value );
        }
        
        unset( $icsDatesMeta [0] ,$icsDatesMeta[1]);
        $summarized_ics_data = [];

        foreach($icsDatesMeta as $icsDatesMetaArray){
            $break_point = array_search("\t\r",$icsDatesMetaArray);
            if($break_point != false){                
                $summary = trim($icsDatesMetaArray[2],"\r");
                for($i=3;$i<=5;$i++){
                    $summary_parts = substr($icsDatesMetaArray[$i],1,-1);
                    $summary.=$summary_parts;
                    unset($icsDatesMetaArray[$i]);
                }
                $icsDatesMetaArray[2] = $summary;
                array_push($summarized_ics_data,$icsDatesMetaArray);
            }else{
                array_push($summarized_ics_data,$icsDatesMetaArray);

            }
        }

        /* Itearting the Ics Meta Value */
        foreach ( $summarized_ics_data as $key => $value ) {
            foreach ( $value as $subKey => $subValue ) {
                /* to get ics events in proper order */
                $icsDates = $this->getICSDates ( $key, $subKey, $subValue, $icsDates );
            }
        }
        return $icsDates;
    }

    /* funcion is to avaid the elements wich is not having the proper start, end  and summary informations */
    function getICSDates($key, $subKey, $subValue, $icsDates) {
        if ($key != 0 && $subKey == 0) {
            $icsDates [$key] ["BEGIN"] = $subValue;
        } else {
            $subValueArr = explode ( ":", $subValue, 2 );
            if (isset ( $subValueArr [1] )) {
                $icsDates [$key] [$subValueArr [0]] = $subValueArr [1];
            }
        }
        return $icsDates;
    }
}

?>