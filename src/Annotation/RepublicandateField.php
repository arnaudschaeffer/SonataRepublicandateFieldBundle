<?php
namespace Aschaeffer\SonataRepublicandateFieldBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class RepublicandateField
{
    const ANNOTATION_NAME = 'Aschaeffer\\SonataRepublicandateFieldBundle\\Annotation\\RepublicandateField';

    /**
     * @Required
     *
     * @var string
     */
    public $gregorianField;

    /**
     * @return string
     */
    public function getGregorianField()
    {
        return $this->gregorianField;
    }
}