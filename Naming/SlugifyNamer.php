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

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

/**
 * SlugifyNamer
 *
 * @author Vincent GUERARD <v.guerard@fulgurio.net>
 */
class SlugifyNamer implements NamerInterface
{
    /**
     * {@inheritDoc}
     */
    public function name($object, PropertyMapping $mapping)
    {
        $file = $mapping->getFile($object);
        $extension = $this->getExtension($file);
        $filename = $extension == '' ? $file->getClientOriginalName() : substr($file->getClientOriginalName(), 0, -mb_strlen($extension) - 1);
        $name = strtr(
                utf8_decode(mb_strtolower($filename, 'UTF-8')),
                utf8_decode('àáâãäåòóôõöøèéêëçìíîïùúûüÿñ'),
                'aaaaaaooooooeeeeciiiiuuuuyn');
        $name = preg_replace(array('`[^a-z0-9\._]`i', '`[-]+`'), '-', $name);
        if ($extension)
        {
            $name = sprintf('%s.%s', $name, $extension);
        }
        return $name;
    }

    /**
     * Get file extension
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return null
     */
    protected function getExtension(UploadedFile $file)
    {
        $originalName = $file->getClientOriginalName();
        if ($extension = pathinfo($originalName, PATHINFO_EXTENSION))
        {
            return $extension;
        }
        if ($extension = $file->guessExtension())
        {
            return $extension;
        }
        return null;
    }
}
