<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Batch;

class StudentCsvBatch implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $batch_stream = explode("_",$value);
        $batch  = $batch_stream[0];
        $stream = $batch_stream[1];
        $getBatch = Batch::join('streams','batches.stream_id','=','streams.id')
                            ->where([['batches.name',$batch],['streams.name',$stream]])
                            ->get();
        return count($getBatch) === 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Batch and/or stream does not exist';
    }
}
