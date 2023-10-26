<?php

namespace eFund;

use Auth;
use Log as Logger;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Exception;
use DB;
class Log extends Model
{
    protected $table = 'Logs';
    public $timestamps = false;

    /* Log Types */
    private $logged_in = 'Logged_In';
    private $insert = 'Insert';
    private $update = 'Update';
    private $delete = 'Delete';
    private $default = 'Info';

    /* Driver 
		[0] database: Writes logs to database
		[1] laravel: Writes logs to default Laravel Log configs
		[2] both: Writes logs to database and Laravel Log 
    */
   	private $drivers = ['database', 'laravel', 'both'];
	private $driver;

	/* Enable Logging */
	private $enabled;

   	function __construct()
   	{
   		$this->enabled = config('preferences.log_enabled');
   		$this->driver = config('preferences.log_driver');
   	}

    public function write($type, $tbl, $model)
    {
        if(!$this->enabled)
            return;

    	$new = new $this;
    	$new->type = $type;

    	if(empty($model->getOriginal()) && $type == 'Update')
    		$new->type = 'Insert';
    	
    	$new->Message = $this->compose($new->type, $model);
    	$new->tbl = $tbl;
    	$new->user_id = $this->getUserId();
    	$new->created_at = date('Y-m-d H:i:s');

    	if($new->Message == '[]')
    		return;

    	if($this->driver == $this->drivers[0])
    		$this->writeToDB($new);
    	else if($this->driver == $this->drivers[1])
    		$this->writetoLog($new);
    	else if($this->driver == $this->drivers[2])
    		$this->writeToBoth($new);
    }

    public function writeOnly($type, $tbl, $model)
    {
        if(!$this->enabled)
            return;
        
    	$new = new $this;
    	$new->type = $type;
    	$new->Message = $this->compose($new->type, $model);
    	$new->tbl = $tbl;
    	$new->user_id = $this->getUserId();
    	$new->created_at = date('Y-m-d H:i:s');

    	if($new->Message == '[]')
    		return;

    	if($this->driver == $this->drivers[0])
    		$this->writeToDB($new);
    	else if($this->driver == $this->drivers[1])
    		$this->writetoLog($new);
    	else if($this->driver == $this->drivers[2])
    		$this->writeToBoth($new);
    }

    public function writeToDB($new)
    {
    	$newDB = new $this;
    	$newDB = $new;
    	$newDB->save();
    }

    public function writeToLog($new)
    {
    	Logger::info(json_encode($new));
    }

    public function writeToBoth($new)
    {
    	$this->writeToDB($new);
    	$this->writeToLog($new);
    }

    public function compose($type, $model)
    {
    	$msg;

    	if($type == $this->insert){

    		$msg = $model;

    	}else if($type == $this->update){

    		$msg =  $this->getDirtyValue($model);

    	}else if($type == $this->delete){

    		$msg = $model;

    	}else if($type == $this->info){

    		$msg = $model;

    	}else{

    		$msg = $model;

    	}

    	return json_encode($msg);
    }

    public function getDirtyValue($model)
    {
    	try {
    		
			    $changes = array();
                
                if($model->getKey() != NULL || $model->getKey() != ''){
                    $changes['id'] = $model->getKey();
                }
                // else{
                //     foreach($model->foreign as $foreign){
                //         $changes[$foreign] = $model[$foreign];
                //     }
                // }

			    foreach($model->getDirty() as $key => $value){
			        $original = $model->getOriginal($key);
			        $changes[$key] = [
			            'old' => $original,
			            'new' => $value,
			        ];
			    }

			   	return $changes;
			   

    	} catch (Exception $e) {
    		throw new Exception("LogException: Failed to get Dirty Fields.", $e->getMessage());
    	}

    }

    public function getType($type)
    {
    	switch ($type) {
    		case $this->logged_in:
    			return $this->logged_in;
    		case $this->insert:
    			return $this->insert;
    		case $this->update:
    			return $this->update;
    		case $this->delete:
    			return $this->delete;
    		default:
    			return $this->default;
    	}

    }

    public function getUserId()
    {
    	if(Auth::check()){
    		return Auth::user()->id;
    	}else{
    		return NULL;
    	}
    }

    public function scopeUser($query, $userId)
    {
        $ids = [];

        if(is_numeric($userId))
            $ids = array_push($ids, $userId);
        else{
            $users = DB::table('viewUsers')->select('id')->where('FullName', 'LIKE', '%' . $userId . '%')->get();
            foreach($users as $user){
                array_push($ids, $user->id);
            }
        }

        return $query->whereIn('user_id', $ids)->orderBy('id', 'desc');

    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type)->orderBy('id', 'desc');
    }

    public function scopeDate($query, $from, $to)
    {
        return $query->where('created_at', '>=', $from)->where('created_at', '<=', $to.' 23:59:59')->orderBy('created_at', 'desc');
    }

    public function scopeTable($query, $table)  
    {
        return $query->where('tbl', $table)->orderBy('created_at', 'desc');
    }

    public function scopeContent($query, $content)  
    {
        return $query->where('Message', 'LIKE', '%' . $content . '%')->orderBy('id', 'desc');
    }
}
