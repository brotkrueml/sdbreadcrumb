# About Structured Data Breadcrumb View Helper

This is a TYPO3 Fluid view helper extension that renders structured data for the breadcrumb.

## Requirements

The extension requires TYPO3 9 LTS.

## Why should you use this extension?

Structured data enriches your content for search engines.
The breadcrumb shows independently of the page tree the website hierarchy and categorization.
With this view helper you can render a [breadcrumb list](https://schema.org/BreadcrumbList) according to [schema.org](https://schema.org/).

You can find more information about the benefits of structured breadcrumb markup and examples in the [Google feature guide](https://developers.google.com/search/docs/data-types/breadcrumb).

## Installation

### Installation via Composer

The recommended way to install this extension is by using Composer. In your Composer based TYPO3 project root, just do

    composer require brotkrueml/sdbreadcrumb

### Installation as extension from TYPO3 Extension Repository (TER)

Download and install the extension with the extension manager module.

## Usage

The view helper can be used in your Fluid template with the following syntax:

    <sdb:breadcrumbMarkup breadcrumb="{breadcrumb}"/>

Just throw the result of the menu processor for the special property `rootline` from your TypoScript page object into the view helper, e.g.:

    page.10 = FLUIDTEMPLATE
    page.10 {
        // ... Your other configuration

        dataProcessing {
            10 = TYPO3\CMS\Frontend\DataProcessing\MenuProcessor
            10 {
                special = rootline
                as = breadcrumb
            }
        }
    }

By default the first entry - which is the start page - is stripped off, so it won't be shown in the structured data (which is not needed).
You can change this behaviour with the additional parameter `stripFirstItem':

    <sdb:breadcrumbMarkup breadcrumb="{breadcrumb}" stripFirstItem="0" />

As the result of the menu processor for the rootline is an array of an array, you can use your own generated structure, e.g. for categories:

    $myBreadcrumb = [
        [
            'link' => '/',
            'title' => 'Start page',
        ],
        [
            'link' => '/category-1',
            'title' => 'Category 1',
        ],
        [
            'link' => '/subcategory-1-1',
            'title' => 'Subcategory 1-1',
        ],
    ];

The result will be in the compact JSON-LD format.

You can test the generated structured data in the [Structured Data Testing Tool](https://search.google.com/structured-data/testing-tool).