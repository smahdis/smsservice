<?php

return [
    'bots' => [
        'user_2' => [
            'token' => '6954372947:AAFfartlJtnTHZAMTc3sNtks6bpsOFqwuUU',
            'webhook_url' => 'https://sms.tikoagency.ir/callback/user_2',
            'commands' => [
                'start' => 'App\\Http\\Controllers\\Commands\\StartCommand',
                'test' => 'App\\Http\\Controllers\\Commands\\TestCommand'
            ]
        ],
        'test_bot' => [
            'token' => '6928052247:AAFc5Iwmk5pNvQ2nQP5mhsy88GIr7F9OJHE',
            'webhook_url' => 'https://sms.tikoagency.ir/callback/test_bot',
            'commands' => [
                'start' => 'App\\Http\\Controllers\\Commands\\StartCommand',
                'help' => 'App\\Http\\Controllers\\Commands\\TestCommand'
            ]
        ],
        'user_1' => [
            'token' => '6954372947:AAFfartlJtnTHZAMTc3sNtks6bpsOFqwuUU',
            'webhook_url' => 'https://sms.tikoagency.ir/callback/user_1',
            'commands' => [
                'start' => 'App\\Http\\Controllers\\Commands\\StartCommand',
                'test' => 'App\\Http\\Controllers\\Commands\\TestCommand'
            ]
        ],
        'user_3' => [
            'token' => '181473118:AAEZG9OPPbdDC2MrHPYn8xXKPshEkHQssy0',
            'webhook_url' => 'https://sms.tikoagency.ir/callback/user_3'
        ]
    ],
    'default' => 'user_1',
    'async_requests' => false,
    'http_client_handler' => null,
    'base_bot_url' => null,
    'resolve_command_dependencies' => true,
    'commands' => ['Telegram\\Bot\\Commands\\HelpCommand'],
    'command_groups' => [],
    'shared_commands' => []
];
