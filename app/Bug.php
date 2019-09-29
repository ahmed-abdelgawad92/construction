<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bug extends Model
{
    /**
     * state description
     * 0 => not started
     * 1 => started
     * 2 => test
     * 3 => finished
     */
    public function getStateAttribute($value)
    {
        $state = [ 'not started', 'started', 'test', 'issues found', 'started re-developing', 'finished' ];
        return $state[$value];
    }
    /* 
    * type description
    * 0 => bug
    * 1 => new feature
    * 
    */
    public function getTypeAttribute($value)
    {
        $type = [ 'bug', 'new feature' ];
        return $type[$value];
    }

    // scope a query that get only the not finished records
    public function scopeNotFinished($query)
    {
        return $query->where('bugs.state','!=', 5)->orderBy('state', 'DESC')->orderBy('created_at', 'DESC');
    }

    //relationship between users and bugs
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
