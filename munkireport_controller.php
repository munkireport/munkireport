<?php

/**
 * Munkireport module class
 *
 * @package munkireport
 * @author
 **/
class Munkireport_controller extends Module_controller
{
    
    /*** Protect methods with auth! ****/
    public function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__) .'/';
        $this->view_path = $this->module_path . 'views/';
    }

    /**
     * Default method
     *
     * @author AvB
     **/
    public function index()
    {
        echo "You've loaded the munkireport module!";
    }
    
    /**
     * Retrieve data in json format
     *
     **/
    public function get_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
        }
        $columns = [
          'runtype',
          'version',
          'errors',
          'warnings',
          'manifestname',
          'error_json',
          'warning_json',
          'starttime',
          'endtime'
        ];

        $out = Munkireport_model::select($columns)
            ->whereSerialNumber($serial_number)
            ->filter()
            ->first();

        $obj->view('json', array('msg' => $out));
    }
    
    /**
     * Get manifests statistics
     *
     *
     **/
    public function get_manifest_stats()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authorized')));
        } else {
          $out = Munkireport_model::selectRaw('count(1) AS count, manifestname')
            ->filter()
            ->groupBy('manifestname')
            ->orderBy('count', 'desc')
            ->get()
            ->toArray();

            $obj->view('json', array('msg' => $out));
        }
    }
    
    /**
    * Get munki versions
     *
     *
     **/
    public function get_versions()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authorized')));
        } else {
            // $mrm = new Munkireport_model();
            $out = Munkireport_model::selectRaw('version, count(*) AS count')
              ->filter()
              ->groupBy('version')
              ->orderBy('count', 'desc')
              ->get()
              ->toArray();

            $obj->view('json', ['msg' => $out]);
        }
    }
    
    
    /**
     * Get statistics
     *   *
     * @param integer $hours Number of hours to get stats from
     **/
    public function get_stats($hours = 24)
    {
        $timestamp = date('Y-m-d H:i:s', time() - 60 * 60 * (int) $hours);
        $out = Munkireport_model::selectRaw('sum(errors > 0) as error, sum(warnings > 0) as warning')
            ->filter()
            ->where('munkireport.timestamp', '>', $timestamp)
            ->first();
        
        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }
    
    public function get_errors($max = 5)
    {
        $out = array();
        if (! $this->authorized()) {
            $out['error'] = 'Not authorized';
        } else {
            $out = Munkireport_model::selectRaw('error_json, COUNT(*) as count')
              ->where('errors', '>', 0)
              ->filter()
              ->groupBy('error_json')
              ->orderBy('count', 'desc')
              ->limit($max)
              ->get()
              ->toArray();
        }
        $obj = new View();
        $obj->view('json', ['msg' => $out]);
    }
    
    public function get_warnings($max = 5)
    {
        $out = array();
        if (! $this->authorized()) {
            $out['error'] = 'Not authorized';
          } else {
              $out = Munkireport_model::selectRaw('warning_json, COUNT(*) as count')
                ->where('warnings', '>', 0)
                ->filter()
                ->groupBy('warning_json')
                ->orderBy('count', 'desc')
                ->limit($max)
                ->get()
                ->toArray();
          }
          
          $obj = new View();
          $obj->view('json', ['msg' => $out]);
    }


} // END class default_module
