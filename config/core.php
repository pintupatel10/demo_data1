<?php

return [
    'wirecard' => [
        'dev' => [
            'merchant-id' => '92ae4548-354f-4d73-b46a-59458dd1ef6c',
            'url' => 'https://test.wirecard.com.sg/engine/hpp/',
            'secret-key' => 'a6620f11-1a15-4091-a657-67632fbc2e84',
        ]
    ],


    'union-pay' => [
        'dev' => [
            'environment' => 'development',
            'merchant-id' => '508034447224355',
            'private-cert' => base_path() . '/certificates/upop/acp_test_sign.pfx',
            'private-cert-password' => '000000',
            'public-cert' => base_path() . '/certificates/upop/acp_test_verify_sign_new.cer',
            'encryption-cert' => base_path() . '/certificates/upop/acp_test_enc.cer',
            'url' => 'https://101.231.204.80:5000/gateway/api/frontTransReq.do',
        ],

        'prod' => [
            'environment' => 'production',
            'merchant-id' => '508034447224355',
            'private-cert' => base_path() . '/certificates/upop/acp_test_sign.pfx',
            'private-cert-password' => '000000',
            'public-cert' => base_path() . '/certificates/upop/acp_test_verify_sign_new.cer',
            'encryption-cert' => base_path() . '/certificates/upop/acp_test_enc.cer',
            'url' => 'https://gateway.95516.com/gateway/api/frontTransReq.do',
        ]
    ],

    'disneyland' => [
        'dev' => [
            'url' => 'https://hongkongdisneylandticketstest.disney.go.com',
            'agent-id' => "GL API TESTING",
            'secret-key' => '7rAWwJLjgu',
        ],
        'prod' => [
            'url' => 'https://hongkongdisneylandtickets.disney.go.com',
            'agent-id' => "GL API",
            'secret-key' => 'a5MD48TtEF',
        ]
    ],

    'oceanpark' => [
        'dev' => [
            'url' => 'https://otasuat.oceanpark.com.hk',
            'agent-id' => "AT000005835",
            'secret-key' => "eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJBVDAwMDAwNTgzNSIsImNyZWF0ZWQiOjE0OTAxNjM1ODA4MjAsImV4cCI6MTQ5MDc2ODM4MH0.bK8NzJ931XsGUJ9GJDsOTKzznB20N94Apwht7__-TQ9fuzlFjM8U_VIHnQAl58BlCsvs5F6S016q4aQ2yvh-uQ",
        ],
    ],

    'turbojet' => [
        'dev' => [
            'url' => 'https://uat.turbojetbooking.com/agentbooking/service.asmx?wsdl',
            'login-id' => 'ZTESTAC030',
            'password' => 'granw279',
            'member-id' => 'A2GRAYLINE',
        ]
    ],
];