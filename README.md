Munki reporting module
==============

Provides info about munki

* timestamp (string) (string) datetime 
* runtype'] = (string) one of auto, manualcheck, installwithnologout, checkandinstallatstartup and logoutinstall
* starttime (string) DST datetime 
* endtime (string) DST datetime 
* version (string) Munki version
* errors (int) Amount of errors
* error_json (string) Error messages in `JSON` format
* warnings (int) Amount of warnings
* warning_json (string) Warning messages in `JSON` format
* manifestname (string) name of the primary manifest
