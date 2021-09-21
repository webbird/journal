<?php

return [
    [
        'name' => 'append_title',
        'label' => 'Append article title to page title',
        'default' => 'N',
        'type' => 'radio',
        'values' => ['Y','N'],
    ],[
        'name' => 'articles_per_page',
        'default' => 0,           // unlimited
        'label' => 'Articles per page',
    ],[
        'name' => 'block2',
        'default' => 'N',         // WBCE only
        'type' => 'radio',
        'values' => ['Y','N'],
        'label' => '2nd block',
    ],[
        'name' => 'crop',
        'default' => 'N',         // crop images
        'type' => 'radio',
        'values' => ['Y','N'],
        'label' => 'Crop images',
    ],[
        'name' => 'gallery',
        'default' => 'fotorama',  // gallery (frontend)
        'label' => 'Gallery',
    ],[
        'name' => 'group_order',
        'default' => '6',         // custom
        'type' => 'select',
        'values' => array('aga','gaga'),
        'label' => 'Group order',
    ],[
        'name' => 'image_subdir',
        'default' => '.articles', // located under media
        'label' => 'Articles subdir',
    ],[
        'name' => 'imgmaxsize',
        'default' => 123456,
        'label' => 'Max. image size',
    ],[
        'name' => 'imgmaxwidth',
        'default' => 4096,
        'label' => 'Max. image width',
    ],[
        'name' => 'imgmaxheight',
        'default' => 4096,
        'label' => 'Max. image height',
    ],[
        'name' => 'imgthumbwidth',
        'default' => 150,
        'label' => 'Thumbnail width',
    ],[
        'name' => 'imgthumbheight',
        'default' => 150,
        'label' => 'Thumbnail height',
    ],[
        'name' => 'mode',
        'default' => 'advanced',  // admin mode advanced || basic
        'label' => 'Mode',
    ],[
        'name' => 'subdir',
        'default' => 'articles',
        'label' => 'Subdir in pages folder',
    ],[
        'name' => 'tag_order',
        'default' => '6',
        'type' => 'radio',
        'label' => 'Tag order',
    ],[
        'name' => 'use_second_block',
        'default' => 'N',         // WBCE only
        'type' => 'radio',
        'values' => ['Y','N'],
        'label' => 'Use 2nd block',
    ],[
        'name' => 'view',
        'default' => 'default',
        'label' => 'View / Preset',
    ],[
        'name' => 'view_order',
        'default' => '6',         // custom
        'type' => 'select',
        'label' => 'Articles order',
    ],
];