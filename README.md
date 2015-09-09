# Toggl Helper
Toggl Helper -plugin for Wordpress. Requires Advanced Custom Fields and 
PHP library for Toggl API v8 (you can get it from https://github.com/arendjantetteroo/guzzle-toggl)

## What plugin do?
1. Creates custom post type 'Day'.
2. Gets time entries for specific day from Toggl using Toggl API (https://github.com/toggl/toggl_api_docs) and
PHP library for Toggl API v8 by Arend Jan Tetteroo (https://github.com/arendjantetteroo/guzzle-toggl).
3. Calculates how many hours you have worked in a day from start time and end time that user gives. 
4. Uses this duration to create new time entry (starting from 17:00:00+00:00) to Toggl with the description that user has given into a project which ID user has given before (in options).
5. Calculates day hourbalance: do you have too much, enough, or not enough workhours in a day and in a week. Default is 7,5h hours in a day



## Todo:
1. Dynamic statistic to frontpage (so that it echoes current week hours)
2. Shortcode for printing the week/month/year -statistics. Few problems though:
    1. Fronpage week statistic works only if there are 5 workday in a week, so for example finnish arkipyhät are bad
    2. Month workhours statistic to frontpage (same problem as in 1.)
    3. Year workhours statistic to frontpage (same problem as in 1.)





##Changelog
9.9.2015
v1.1 – Add settings page for easier customize

7.9.2015
v1.0 is published with basic functionalities
