<?php

namespace tgui\Models;

use Illuminate\Database\Eloquent\Model;

class TACDeviceGrps extends Model
{
	protected $table = 'tac_device_groups';
	
	protected $fillable = [
		'name',
		'enable',
		'key',
		'enable_flag',
		'banner_welcome',
		'banner_failed',
		'banner_motd',
		'default_flag',
		'manual',
	];
}
