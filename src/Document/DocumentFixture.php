<?php

namespace App\Document;

use Sulu\Bundle\DocumentManagerBundle\DataFixtures\DocumentFixtureInterface;
use Sulu\Bundle\PageBundle\Document\PageDocument;
use Sulu\Component\DocumentManager\DocumentManager;
use Sulu\Component\DocumentManager\Exception\MetadataNotFoundException;


class DocumentFixture implements DocumentFixtureInterface
{

    /**
     * Simple local string with two chars.
     */
    const LOCALE = 'en';

    /**
     * All fixtures will be sorted regarding the returned integer. This
     * "weight" will let a fixture run later if the integer is higher.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 10;
    }

    /**
     * Load fixtures.
     *
     * Use the document manager to create and save fixtures.
     * Be sure to call DocumentManager#flush() when you are done.
     *
     * @param DocumentManager $documentManager
     *
     * @throws MetadataNotFoundException
     */
    public function load(DocumentManager $documentManager)
    {
        /**
         * "page" is the base content of sulu. "article" for example would be used be the Article bundle.
         *
         * @var PageDocument $document
         */
        $document = $documentManager->create('page');

        // Set the local. Keep in mind that you have to save every local version extra.
        $document->setLocale(static::LOCALE);

        // The title of the page set in the template XML. Can not be set by getStructure()->bind();
        $document->setTitle('foo bar page title');

        // Use setStructureType to set the name of the page template.
        $document->setStructureType('default');

        // URL of the content without any language prefix.
        $document->setResourceSegment('/foo-bar-page');

        // Data for all content types that this template uses.
        $document->getStructure()->bind(
            [
                'article' => '<strong>Lore Ipsum Dolor</strong>',
            ]
        );

        // Data for the "Excerpt & Taxonomies" tab when editing content.
        $document->setExtension(
            'excerpt',
            [
                'title' => 'foo title',
                'description' => 'bar description',
                'categories' => [],
                'tags' => [],
            ]
        );

        // Data for the "SEO" tab when editing content.
        $document->setExtension(
            'seo',
            [
                'title' => 'foo title',
            ]
        );

        // Optional: If you don't want your document to be published, remove this line
        $documentManager->publish($document, static::LOCALE);

        // Persist immediately to database.
        $documentManager->flush();
    }
}