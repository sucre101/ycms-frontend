<?php
/**
 * Vonage Client Library for PHP
 *
 * @copyright Copyright (c) 2016 Vonage, Inc. (http://vonage.com)
 * @license   https://github.com/vonage/vonage-php/blob/master/LICENSE MIT License
 */

namespace Vonage\Entity;

interface CollectionAwareInterface
{
    /**
     * @param CollectionInterface $collection
     * @return mixed
     */
    public function setCollection(CollectionInterface $collection);

    /**
     * @return CollectionInterface
     */
    public function getCollection();
}
