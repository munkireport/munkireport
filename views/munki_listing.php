<?php 

$this->view('listings/default',
[
  "i18n_title" => 'munkireport.report',
  "table" => [
    [
      "column" => "machine.computer_name",
      "i18n_header" => "listing.computername",
      "formatter" => "clientDetail",
      "tab_link" => "displays-tab",
    ],
    [
      "column" => "reportdata.serial_number",
      "i18n_header" => "serial",
    ],
	["column" => "reportdata.long_username", "i18n_header" => "username",],
	["column" => "reportdata.remote_ip", "i18n_header" => "network.ip_address",],
	[
		"column" => "machine.os_version",
		"i18n_header" => "os.version",
		"filter" => "osFilter",
		"formatter" => "osVersion",
	],
	["column" => "munkireport.version", "i18n_header" => "munkireport.version",],
	[
		"column" => "reportdata.timestamp",
		"i18n_header" => "last_seen",
		"sort" => "desc",
		"formatter" => "timestampToMoment",
	],
	["column" => "munkireport.runtype", "i18n_header" => "munkireport.run_type",],
	["column" => "munkireport.errors", "i18n_header" => "error_plural",],
	["column" => "munkireport.warnings", "i18n_header" => "warning_plural",],
	["column" => "munkireport.manifestname", "i18n_header" => "munkireport.manifest.name",],
  ]
]);
