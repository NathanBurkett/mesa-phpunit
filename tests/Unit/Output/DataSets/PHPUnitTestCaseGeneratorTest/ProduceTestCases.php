<?php

return [
    'Name encoding: false | Modification during setup: false' => [
        'iterable' => [
            'one' => [
                'name' => 'one',
                'context' => 'foo',
            ],
            'two' => [
                'name' => 'two',
                'context' => 'bar',
            ],
            'three' => [
                'name' => 'three',
                'context' => 'baz',
                'additional' => [
                    'something' => 'else',
                ],
            ],
        ],
        'expected' => [
            'one' => [
                'name' => 'one',
                'context' => 'foo',
            ],
            'two' => [
                'name' => 'two',
                'context' => 'bar',
            ],
            'three' => [
                'name' => 'three',
                'context' => 'baz',
                'additional' => [
                    'something' => 'else',
                ],
            ],
        ],
    ],
    'Name encoding: true | Modification during setup: false' => [
        'iterable' => [
            [
                'name' => 'one',
                'context' => 'foo',
            ],
            [
                'name' => 'two',
                'context' => 'bar',
            ],
            [
                'name' => 'three',
                'context' => 'baz',
                'additional' => [
                    'something' => 'else',
                ],
            ],
        ],
        'expected' => [
            '{"name":"one","context":"foo"}' => [
                'name' => 'one',
                'context' => 'foo',
            ],
            '{"name":"two","context":"bar"}' => [
                'name' => 'two',
                'context' => 'bar',
            ],
            '{"name":"three","context":"baz","additional":{"something":"else"}}' => [
                'name' => 'three',
                'context' => 'baz',
                'additional' => [
                    'something' => 'else',
                ],
            ],
        ],
    ],
    'Name encoding: true | Modification during setup: false | Exception thrown: true' => [
        'iterable' => [
            [
                'name' => "\xB1\x31",
            ],
        ],
        'expected' => [],
        'expectedException' => \RuntimeException::class,
    ],
    'Name encoding: false | Modification during setup: true' => [
        'iterable' => [
            'one' => [
                'name' => 'one',
                'context' => 'foo',
            ],
            'two' => [
                'name' => 'two',
                'context' => 'bar',
            ],
            'three' => [
                'name' => 'three',
                'context' => 'baz',
                'additional' => [
                    'something' => 'else',
                ],
            ],
        ],
        'expected' => [
            'one' => [
                'name' => 'one',
                'context' => 'foo',
            ],
            'two' => [
                'name' => 'two',
                'context' => 'bar',
            ],
            'three' => [
                'name' => 'three - FOO',
                'context' => 'baz',
                'additional' => [
                    'something' => 'else',
                ],
            ],
        ],
        'setup' => function (array $row, $index): array {
            if ($index !== 'three') {
                return $row;
            }

            $row['name'] = 'three - FOO';

            return $row;
        },
    ],
    'Name encoding: true | Modification during setup: true' => [
        'iterable' => [
            [
                'name' => 'one',
                'context' => 'foo',
            ],
            [
                'name' => 'two',
                'context' => 'bar',
            ],
            [
                'name' => 'three',
                'context' => 'baz',
                'additional' => [
                    'something' => 'else',
                ],
            ],
        ],
        'setup' => function(array $row, $index) {
            if ($row['name'] !== 'three') {
                return $row;
            }

            $row['name'] = 'three - FOO';

            return $row;
        },
        'expected' => [
            '{"name":"one","context":"foo"}' => [
                'name' => 'one',
                'context' => 'foo',
            ],
            '{"name":"two","context":"bar"}' => [
                'name' => 'two',
                'context' => 'bar',
            ],
            '{"name":"three","context":"baz","additional":{"something":"else"}}' => [
                'name' => 'three - FOO',
                'context' => 'baz',
                'additional' => [
                    'something' => 'else',
                ],
            ],
        ],
    ],
];
