# slacktail
Simple PHP script to have your logs tailed (tail -f) to Slack. Initial release. PRs and suggestions welcomed.

# install

Rename the file config.example.php to config.php . Fill in the url (incoming webhook), channel, and files options in config.php, for each files you want to monitor. The programs accepts multiple accounts/webhooks.

Add it to your crontab in order to be run.

*/5 * * * * /usr/bin/php path_to_file/slacktail.php > /dev/null 2>&1

If you activate $output_errors, you may have them sent to you via email, with the little utility "ifne" (package moreutils)

*/5 * * * * /usr/bin/php path_to_file/slacktail.php | ifne mail -s "[SlackTail] Error" me@example.com