<?php

namespace yiiunit\extensions\bootstrap4;

use yii\bootstrap4\Tabs;
use yii\helpers\Html;

/**
 * Tests for Tabs widget
 *
 * @group bootstrap4
 */
class TabsTest extends TestCase
{
    /**
     * Each tab should have a corresponding unique ID
     *
     * @see https://github.com/yiisoft/yii2/issues/6150
     */
    public function testIds()
    {
        Tabs::$counter = 0;
        $out = Tabs::widget([
            'items' => [
                [
                    'label' => 'Page1',
                    'content' => 'Page1',
                ],
                [
                    'label' => 'Dropdown1',
                    'items' => [
                        ['label' => 'Page2', 'content' => 'Page2'],
                        ['label' => 'Page3', 'content' => 'Page3'],
                    ]
                ],
                [
                    'label' => 'Dropdown2',
                    'items' => [
                        ['label' => 'Page4', 'content' => 'Page4'],
                        ['label' => 'Page5', 'content' => 'Page5'],
                    ]
                ],
                [
                    'label' => $extAnchor1 = 'External link',
                    'url' => $extUrl1 = ['//other/route'],
                ],
                [
                    'label' => 'Dropdown3',
                    'items' => [
                        [
                            'label' => $extAnchor2 = 'External Dropdown Link',
                            'url' => $extUrl2 = ['//other/dropdown/route']
                        ],
                    ]
                ],
            ]
        ]);

        $page1 = 'w0-tab0';
        $page2 = 'w0-dd1-tab0';
        $page3 = 'w0-dd1-tab1';
        $page4 = 'w0-dd2-tab0';
        $page5 = 'w0-dd2-tab1';

        $shouldContain = [
            'w0', // nav widget container
            "#$page1", // Page1

            'w1', // Dropdown1
            "$page2", // Page2
            "$page3", // Page3


            'w2', // Dropdown2
            "#$page4", // Page4
            "#$page5", // Page5

            'w3', // Dropdown3

            // containers
            "id=\"$page1\"",
            "id=\"$page2\"",
            "id=\"$page3\"",
            "id=\"$page4\"",
            "id=\"$page5\"",
            Html::a($extAnchor1, $extUrl1, ['class' => 'nav-link']),
            Html::a($extAnchor2, $extUrl2, [/*'tabindex' => -1, */
                'class' => 'dropdown-item'
            ]),
        ];

        foreach ($shouldContain as $string) {
            $this->assertContains($string, $out);
        }
    }

    public function testVisible()
    {
        Tabs::$counter = 0;
        $html = Tabs::widget([
            'items' => [
                [
                    'label' => 'Page1',
                    'content' => 'Page1',
                ],
                [
                    'label' => 'InvisiblePage',
                    'content' => 'Invisible Page Content',
                    'visible' => false
                ],
                [
                    'label' => 'Dropdown1',
                    'items' => [
                        ['label' => 'Page2', 'content' => 'Page2'],
                        ['label' => 'InvisibleItem', 'content' => 'Invisible Item Content', 'visible' => false],
                        ['label' => 'Page3', 'content' => 'Page3'],
                        ['label' => 'External Link', 'url' => ['//other/dropdown/route']],
                        ['label' => 'Invisible External Link', 'url' => ['//other/dropdown/route'], 'visible' => false],
                    ]
                ],
            ]
        ]);

        $this->assertNotContains('InvisiblePage', $html);
        $this->assertNotContains('Invisible Page Content', $html);
        $this->assertNotContains('InvisibleItem', $html);
        $this->assertNotContains('Invisible Item Content', $html);
        $this->assertNotContains('Invisible External Link', $html);
    }

    public function testItem()
    {
        $checkTag = 'article';

        $out = Tabs::widget([
            'items' => [
                [
                    'label' => 'Page1',
                    'content' => 'Page1',
                ],
                [
                    'label' => 'Page2',
                    'content' => 'Page2',
                ],
            ],
            'itemOptions' => ['tag' => $checkTag],
            'renderTabContent' => true,
        ]);

        $this->assertContains('<' . $checkTag, $out);
    }

    public function testTabContentOptions()
    {
        $checkAttribute = "test_attribute";
        $checkValue = "check_attribute";

        $out = Tabs::widget([
            'items' => [
                [
                    'label' => 'Page1',
                    'content' => 'Page1'
                ]
            ],
            'tabContentOptions' => [
                $checkAttribute => $checkValue
            ]
        ]);

        $this->assertContains($checkAttribute . '=', $out);
        $this->assertContains($checkValue, $out);
    }

    public function testActivateFirstVisibleTab()
    {
        $html = Tabs::widget([
            'id' => 'mytab',
            'items' => [
                [
                    'label' => 'Tab 1',
                    'content' => 'some content',
                    'visible' => false
                ],
                [
                    'label' => 'Tab 2',
                    'content' => 'some content'
                ],
                [
                    'label' => 'Tab 3',
                    'content' => 'some content'
                ],
                [
                    'label' => 'Tab 4',
                    'content' => 'some content'
                ]
            ]
        ]);

        $this->assertNotContains('<li class="nav-item active"><a class="nav-link active" href="#mytab-tab0" data-toggle="tab" role="tab" aria-controls="mytab-tab0" aria-selected="true">Tab 1</a></li>',
            $html);
        $this->assertContains('<li class="nav-item active"><a class="nav-link active" href="#mytab-tab1" data-toggle="tab" role="tab" aria-controls="mytab-tab1" aria-selected="true">Tab 2</a></li>',
            $html);
    }

    public function testActivateTab()
    {
        $html = Tabs::widget([
            'id' => 'mytab',
            'items' => [
                [
                    'label' => 'Tab 1',
                    'content' => 'some content',
                    'visible' => false
                ],
                [
                    'label' => 'Tab 2',
                    'content' => 'some content'
                ],
                [
                    'label' => 'Tab 3',
                    'content' => 'some content',
                    'active' => true
                ],
                [
                    'label' => 'Tab 4',
                    'content' => 'some content'
                ]
            ]
        ]);
        $this->assertContains('<li class="nav-item active"><a class="nav-link active" href="#mytab-tab2" data-toggle="tab" role="tab" aria-controls="mytab-tab2" aria-selected="true">Tab 3</a></li>',
            $html);
    }

    public function testTabLabelEncoding()
    {
        $html = Tabs::widget([
            'encodeLabels' => false,
            'id' => 'mytab',
            'items' => [
                [
                    'label' => 'Tab 1<span>encoded</span>',
                    'content' => 'some content',
                    'encode' => true,
                ],
                [
                    'label' => 'Tab 2<span>not encoded</span>',
                    'content' => 'some content',
                ],
                [
                    'label' => 'Tab 3<span>not encoded too</span>',
                    'content' => 'some content',
                ],
            ]
        ]);
        $this->assertContains('&lt;span&gt;encoded&lt;/span&gt;', $html);
        $this->assertContains('<span>not encoded</span>', $html);
        $this->assertContains('<span>not encoded too</span>', $html);
    }
  
    /**
     * @see https://github.com/yiisoft/yii2-bootstrap4/issues/108#issuecomment-465219339
     */
    public function testIdRendering()
    {
        Tabs::$counter = 0;
        $html = Tabs::widget([
            'items' => [
                [
                    'options' => ['id' => 'pane1'],
                    'label' => 'Tab 1',
                    'content' => '<div>Content 1</div>',
                ],
                [
                    'label' => 'Tab 2',
                    'content' => '<div>Content 2</div>',
                ],
            ],
        ]);

        $expected = <<<HTML
<ul id="w0" class="nav nav-tabs"><li class="nav-item active"><a class="nav-link active" href="#pane1" data-toggle="tab" role="tab" aria-controls="pane1" aria-selected="true">Tab 1</a></li>
<li class="nav-item"><a class="nav-link" href="#w0-tab1" data-toggle="tab" role="tab" aria-controls="w0-tab1" aria-selected="false">Tab 2</a></li></ul>
<div class="tab-content"><div id="pane1" class="tab-pane active"><div>Content 1</div></div>
<div id="w0-tab1" class="tab-pane"><div>Content 2</div></div></div>
HTML;

        $this->assertEquals($expected, $html);
    }
}
