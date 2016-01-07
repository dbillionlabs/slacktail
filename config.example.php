<?php

$config = [
    'group1' => [
        'config' => [
            'url' => 'https://hooks.slack.com/services/T0341CD6U/B0HTV99C5/...',
            'channel' => '#logs',
            // 'channel'    =>    '', post to this channel too
            'username' => 'slacklog',
            //'icon_url'    =>    '',
            //'icon_emoji'    =>    ':smile:'
        ],
        'files' => [
            'path_to_log/error.log',
            'path_to_log/syslog.log',
        ],
    ],
];
