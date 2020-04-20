<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Categorizable extends Pivot
{
   /**
     * The table associated with the model.
     * 
     * Note: Interestingly, Laravel adds a plural "s" to table names in the case of when 
     * morphing. When using a word that's plurized (Eg. categorizable(s)) without a "s" 
     * causes Laravel to query the incorrect table name. This conflicts with Models 
     * using the singular naming convention (Eg. categorizable).
     *
     * @var string
     */
    protected $table = 'categorizables';
    
    /**
     * The categories that belong to the video.
     */
    public function videos()
    {
        return $this->belongsToMany('App\Video')->using('App\Category');
    }

}
