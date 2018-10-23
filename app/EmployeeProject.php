<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeProject extends Model
{
  /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employee_project';

    //make 1 to many with project
    public function project()
    {
      return $this->belongsTo("App\Project");
    }
    //make 1 to many with employee
    public function employee()
    {
      return $this->belongsTo("App\Employee");
    }
}
