<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Log extends Model {

	//relationship with users 1 to many
	public function user()
	{
		return $this->belongsTo('App\User');
	}
	//get table name
	public function getTableNameAttribute()
	{
		return $this->attributes['table'];
	}
	//get Action
	public function getAction()
	{
		switch ($this->action) {
			case 'create':
				return 'أنشاء';
				break;
			case 'delete':
				return 'حذف';
				break;
			case 'update':
				return 'تعديل';
				break;
			case 'restore':
				return 'استرجاع';
				break;

			default:
				// code...
				break;
		}
	}
	//get affected row
	public function getAffectedRow()
	{
		$class = ($this->table_name == 'taxes') ? 'App\Tax' : "App\\".ucfirst(substr($this->table_name,0,-1)) ;
		return $class::where('id',$this->record_id)->first();
	}
}
