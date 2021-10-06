<?php

declare(strict_types=1);

namespace Aschaeffer\SonataRepublicandateFieldBundle\Admin\Extension;

use App\Entity\Recette;
use App\Entity\SonataMediaGallery;
use Aschaeffer\SonataRepublicandateFieldBundle\Annotation\RepublicandateField;
use Aschaeffer\SonataRepublicandateFieldBundle\Service\DateService;
use Doctrine\Common\Annotations\AnnotationReader;
use Gedmo\Translatable\TranslatableListener;
use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Validator\ErrorElement;

class RepublicanFieldAdminExtension extends AbstractAdminExtension
{
    /**
     * @var AnnotationReader
     */
    protected $annotationReader;

    /**
     * @var DateService
     */
    protected $dateService;

    public function __construct(AnnotationReader $annotationReader, DateService $dateService)
    {
        $this->annotationReader = $annotationReader;
        $this->dateService = $dateService;
    }

    public function validate(AdminInterface $admin, ErrorElement $errorElement, $object)
    {
        $modelClassReflection = new \ReflectionClass(get_class($object));
        foreach ($modelClassReflection->getProperties() as $reflectionProperty) {
            $republicanField = $this->annotationReader->getPropertyAnnotation($reflectionProperty, RepublicandateField::ANNOTATION_NAME);
            if ($republicanField) {
                $this->validateDate($errorElement, $object, $reflectionProperty->getName(), $republicanField->getGregorianField());
            }
        }
    }

    protected function validateDate(ErrorElement $errorElement, $object, $republicanPropertyName, $gregorianPropertyName)
    {
        $getterGregorianProperty = 'get' . ucfirst($gregorianPropertyName);
        $gregorianDate = $object->$getterGregorianProperty();

        if ($gregorianDate == null) {
            try {
                $getterRepublicanProperty = 'get' . ucfirst($republicanPropertyName);
                $setterGregorianProperty = 'set' . ucfirst($gregorianPropertyName);

                $dt = $this->dateService->republicatinTodateTime($object->$getterRepublicanProperty());
                $object->$setterGregorianProperty($dt);
            }  catch (\Exception $e) {
                $this->getRequest()->getSession()->getFlashBag()->add('sonata_flash_error', "La date n'a pas pu être calculée : " . $e->getMessage());
            }
        } else {
            $setterRepublicanProperty = 'set' . ucfirst($republicanPropertyName);
            $object->$setterRepublicanProperty($this->dateService->dateTimeToRepublicain($gregorianDate));
        }

        $errorElement
            ->with('date')
            ->assertNotNull()
            ->end()
        ;
    }
}
