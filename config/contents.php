<?php
return [
    'about-us' => [
        'field_name' => [
            'short_description' => 'text',
        ],
        'validation' => [
            'description.*' => 'required|max:1000',
        ],
    ],

    'how-it-work' => [
        'field_name' => [
            'title' => 'text',
            'short_description' => 'textarea',
            'image' => 'file',
        ],
        'validation' => [
            'title.*' => 'required|max:100',
            'sub_title.*' => 'required|max:100',
            'short_description.*' => 'required|max:5000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
        ],
        'size' => [
            'image' => '80x80'
        ]
    ],

    'feature' => [
        'field_name' => [
            'title' => 'text',
            'information' => 'textarea',
            'image' => 'file',
        ],
        'validation' => [
            'title.*' => 'required|max:100',
            'information.*' => 'required|max:30000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
        ],
        'size' => [
            'image' => '80x80'
        ]
    ],

    'faq' => [
        'field_name' => [
            'title' => 'text',
            'description' => 'textarea'
        ],
        'validation' => [
            'title.*' => 'required|max:190',
            'description.*' => 'required|max:30000'
        ]
    ],

    'testimonial' => [
        'field_name' => [
            'name' => 'text',
            'rating' => 'text',
            'designation' => 'text',
            'description' => 'textarea',
            'image' => 'file'
        ],
        'validation' => [
            'name.*' => 'required|max:100',
            'rating.*' => 'required|integer|min:1|max:5',
            'designation.*' => 'required|max:2000',
            'description.*' => 'required|max:2000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
        ],
        'size' => [
            'image' => '100x100'
        ]
    ],

    'blog' => [
        'field_name' => [
            'title' => 'text',
            'description' => 'textarea',
            'image' => 'file'
        ],
        'validation' => [
            'title.*' => 'required|max:100',
            'description.*' => 'required|max:20000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
        ],
        'size' => [
            'image' => '670x424',
            'thumb' => '856x571'
        ]
    ],

    'social' => [
        'field_name' => [
            'name' => 'text',
            'icon' => 'icon',
            'link' => 'url',
        ],
        'validation' => [
            'name.*' => 'required|max:100',
            'icon.*' => 'required|max:100',
            'link.*' => 'required|max:100'
        ],
    ],
    'support' => [
        'field_name' => [
            'title' => 'text',
            'description' => 'textarea'
        ],
        'validation' => [
            'title.*' => 'required|max:100',
            'description.*' => 'required|max:3000'
        ]
    ],


    'message' => [
        'required' => 'This field is required.',
        'min' => 'This field must be at least :min characters.',
        'max' => 'This field may not be greater than :max characters.',
        'image' => 'This field must be image.',
        'mimes' => 'This image must be a file of type: jpg, jpeg, png.',
        'integer' => 'This field must be an integer value',
    ],

    'content_media' => [
        'image' => 'file',
        'thumbnail' => 'file',
        'youtube_link' => 'url',
        'button_link' => 'url',
        'link' => 'url',
        'icon' => 'icon'
    ]
];
