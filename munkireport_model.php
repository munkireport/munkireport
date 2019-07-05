<?php

use munkireport\models\MRModel as Eloquent;

class Munkireport_model extends Eloquent
{
    protected $table = 'munkireport';

    protected $fillable = [
      'serial_number',
      'runtype',
      'version',
      'errors',
      'warnings',
      'manifestname',
      'error_json',
      'warning_json',
      'starttime',
      'endtime',
      'timestamp',
    ];

    public $timestamps = false;

}
