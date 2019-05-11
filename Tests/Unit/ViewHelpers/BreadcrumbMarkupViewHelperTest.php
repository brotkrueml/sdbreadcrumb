<?php

namespace Brotkrueml\Sdbreadcrumb\Tests\Unit\ViewHelpers;

use Brotkrueml\Sdbreadcrumb\ViewHelpers\BreadcrumbMarkupViewHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContext;


/**
 * Testcase for BreadcrumbMarkupViewHelper
 */
class BreadcrumbMarkupViewHelperTest extends Testcase
{
    /**
     * @test
     */
    public function argumentsAreRegisteredCorrectly()
    {
        /** @var MockObject|BreadcrumbMarkupViewHelper $viewHelper */
        $viewHelper = $this->getMockBuilder(BreadcrumbMarkupViewHelper::class)
            ->setMethods(['registerArgument'])
            ->getMock();

        $viewHelper
            ->expects($this->exactly(2))
            ->method('registerArgument')
            ->withConsecutive(
                [
                    'breadcrumb',
                    'array',
                    $this->anything(),
                    true
                ],
                [
                    'stripFirstItem',
                    'bool',
                    $this->anything(),
                    false,
                    true
                ]
            );

        $viewHelper->initializeArguments();
    }


    /**
     * @test
     * @dataProvider provider
     * @param $breadcrumb
     * @param $stripFirstItem
     * @param $expected
     */
    public function renderReturnsCorrectStructuredData(array $breadcrumb, bool $stripFirstItem, string $expected)
    {
        $viewHelper = new BreadcrumbMarkupViewHelper();

        /** @var RenderingContext $renderingContextMock */
        $renderingContextMock = $this->getMockBuilder(RenderingContext::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->fakeTypo3SiteUrl();

        $arguments = [
            'breadcrumb' => $breadcrumb,
        ];

        if (is_bool($stripFirstItem)) {
            $arguments['stripFirstItem'] = $stripFirstItem;
        }

        $actual = $viewHelper::renderStatic(
            $arguments,
            function () {
            },
            $renderingContextMock
        );

        $this->assertEquals($expected, $actual);
    }

    public function provider(): array
    {
        $defaultBreadcrumb = [
            [
                'link' => '/',
                'title' => 'fake start page',
            ],
            [
                'link' => '/level-1/',
                'title' => 'fake subpage for level 1',
            ],
            [
                'link' => '/level-1/level-2/',
                'title' => 'fake subpage for level 2',
            ],
        ];


        return [
            'output empty string if breadcrumb list is empty and stripFirstTime is set to true' => [
                [],
                true,
                '',
            ],
            'output empty string if breadcrumb list is empty and stripFirstTime is set to false' => [
                [],
                false,
                '',
            ],
            'render correct output with stripFirstTime is set to true' => [
                $defaultBreadcrumb,
                true,
                '<script type="application/ld+json">{"@context":"http://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"item":{"@type":"WebPage","@id":"https://example.org/level-1/","name":"fake subpage for level 1"}},{"@type":"ListItem","position":2,"item":{"@type":"WebPage","@id":"https://example.org/level-1/level-2/","name":"fake subpage for level 2"}}]}</script>',
            ],
            'render correct output with stripFirstTime is set to false' => [
                $defaultBreadcrumb,
                false,
                '<script type="application/ld+json">{"@context":"http://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"item":{"@type":"WebPage","@id":"https://example.org/","name":"fake start page"}},{"@type":"ListItem","position":2,"item":{"@type":"WebPage","@id":"https://example.org/level-1/","name":"fake subpage for level 1"}},{"@type":"ListItem","position":3,"item":{"@type":"WebPage","@id":"https://example.org/level-1/level-2/","name":"fake subpage for level 2"}}]}</script>',
            ],
            'output empty string with breadcrumb has only one entry and stripFirstTime is set to true' => [
                array_slice($defaultBreadcrumb, 0, 1),
                true,
                '',
            ],
            'render correct output with breadcrumb has only one entry and stripFirstTime is set to false' => [
                array_slice($defaultBreadcrumb, 0, 1),
                false,
                '<script type="application/ld+json">{"@context":"http://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"item":{"@type":"WebPage","@id":"https://example.org/","name":"fake start page"}}]}</script>',
            ],
            'render correct output with links in format index.php?id=42' => [
                [
                    [
                        'link' => 'index.php?id=1',
                        'title' => 'fake start page',
                    ],
                    [
                        'link' => 'index.php=id=2',
                        'title' => 'fake subpage',
                    ],
                ],
                false,
                '<script type="application/ld+json">{"@context":"http://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"item":{"@type":"WebPage","@id":"https://example.org/index.php?id=1","name":"fake start page"}},{"@type":"ListItem","position":2,"item":{"@type":"WebPage","@id":"https://example.org/index.php=id=2","name":"fake subpage"}}]}</script>'
            ]
        ];
    }

    protected function fakeTypo3SiteUrl()
    {
        if (method_exists(GeneralUtility::class, 'setIndpEnv')) {
            /** @noinspection PhpInternalEntityUsedInspection */
            GeneralUtility::setIndpEnv('TYPO3_SITE_URL', 'https://example.org/');
            return;
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $class = new \ReflectionClass(GeneralUtility::class);
        /** @noinspection PhpUnhandledExceptionInspection */
        $property = $class->getProperty('indpEnvCache');
        $property->setAccessible(true);
        $property->setValue(GeneralUtility::class, ['TYPO3_SITE_URL' => 'https://example.org/']);
    }
}