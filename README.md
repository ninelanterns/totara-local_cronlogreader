Totara moodle cron log reader

This plugin allows a user to read moodle cron logs from a directory on a Totara site's webserver on the Totara frontend.

The cron log directory is hard-coded in the plugin, e.g., /var/log/nginx/. This could be improved upon in future to be dynamic/editable by a Totara user. 

The plugin will search for all files with the accepted file extensions from the specified directory (currently hard-coded).

The plugin requires to the output of Totara moodle cron logs to be structured in a specific way for the get_time_flagged_tasks() method to work. This structure isn't anything different from normal, I'm only writing this because the output structure may change in the future for whatever reason.

The plugin also currently only reads .log and .gz files.