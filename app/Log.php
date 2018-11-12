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

			default:
				// code...
				break;
		}
	}
	//get affected row
	public function getAffectedRow()
	{
		return DB::table($this->table_name)->where('id',$this->record_id)->first();
	}
}
