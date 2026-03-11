#!/usr/local/munkireport/munkireport-python3
"""
Filter the results of munki's MANAGED_INSTALL_REPORT.plist
to these items: 'EndTime', 'StartTime', 'ManifestName', 'ManagedInstallVersion'
'Errors', 'Warnings', 'RunType'
"""

import plistlib
import sys
import os
import CoreFoundation

DEBUG = False

# Path to the default munki install dir
default_install_dir = '/Library/Managed Installs'

# Checks munki preferences to see where the install directory is set to.
managed_install_dir = CoreFoundation.CFPreferencesCopyAppValue(
    "ManagedInstallDir", "ManagedInstalls")

# Set the paths based on munki's configuration.
if managed_install_dir:
    MANAGED_INSTALL_REPORT = os.path.join(
        managed_install_dir, 'ManagedInstallReport.plist')
else:
    MANAGED_INSTALL_REPORT = os.path.join(
        default_install_dir, 'ManagedInstallReport.plist')

def dict_from_plist(path):
    """Returns a dict based on plist found in path"""
    try:
        with open(path, 'rb') as fp:
            return plistlib.load(fp)
    except Exception as message:
        raise Exception("Error creating plist from output: %s" % message)

def unique_list(seq):
    seen = set()
    seen_add = seen.add
    return [x for x in seq if not (x in seen or seen_add(x))]

def main():
    """Main"""

    # Check if MANAGED_INSTALL_REPORT exists
    if not os.path.exists(MANAGED_INSTALL_REPORT):
        print('%s is missing.' % MANAGED_INSTALL_REPORT)
        install_report = {}
    else:
        install_report = dict_from_plist(MANAGED_INSTALL_REPORT)

    # Collect Errors, Warnings (as JSON?)
    # EndTime, StartTime, ManifestName, (Conditions->catalogs?)
    # ManagedInstallVersion
    # Some statistics

    # pylint: disable=E1103
    report_list = {}
    items = ['EndTime', 'StartTime', 'ManifestName', 'ManagedInstallVersion',
             'Errors', 'Warnings', 'RunType']

    for item in items:
        # Only list unique errors. Munki lists missing catalogs for each section: Check for installs, Check for removals, Check for managed updates
        #  causing duplicate entries for the same catalog.
        if item == 'Errors' or item == 'Warnings':
            if install_report.get(item):
                report_list[item] = unique_list(install_report[item])
        else:
            if install_report.get(item):
                report_list[item] = install_report[item]
    
    # pylint: enable=E1103

    if DEBUG:
        PP.pprint(report_list)

    # Write report to cache
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    # plistlib.writePlist(report_list, "%s/munkireport.plist" % cachedir)
    try:
        plistlib.writePlist(report_list, "%s/munkireport.plist" % cachedir)
    except:
        with open("%s/munkireport.plist" % cachedir, 'wb') as fp:
            plistlib.dump(report_list, fp, fmt=plistlib.FMT_XML)

if __name__ == "__main__":
    main()
