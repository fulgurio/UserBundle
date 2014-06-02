<?php
/*
 * This file is part of the FulgurioUserBundle package.
 *
 * (c) Fulgurio <http://fulgurio.net/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fulgurio\UserBundle\Naming;

use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

/**
 * DirectoryNamer
 *
 * @author Vincent GUERARD <v.guerard@fulgurio.net>
 */
class DirectoryNamer implements DirectoryNamerInterface
{
    const NB_CHAR_TO_SPLIT = 3;

    /**
     * Creates a directory name for the file being uploaded.
     *
     * @param object          $object  The object the upload is attached to.
     * @param Propertymapping $mapping The mapping to use to manipulate the given object.
     *
     * @return string The directory name.
     */
    public function directoryName($object, PropertyMapping $mapping)
    {
        return DIRECTORY_SEPARATOR . $object->getId();
    }
}
