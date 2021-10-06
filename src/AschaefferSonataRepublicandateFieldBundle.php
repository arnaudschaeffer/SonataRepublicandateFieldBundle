<?php

namespace Aschaeffer\SonataRepublicandateFieldBundle;

use Sonata\CoreBundle\Form\FormHelper;
use Aschaeffer\SonataRepublicandateFieldBundle\DependencyInjection\Compiler\AdminExtensionCompilerPass;
use Aschaeffer\SonataRepublicandateFieldBundle\Model\RepublicandateType;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AschaefferSonataRepublicandateFieldBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        //$this->registerFormMapping();
        $container->addCompilerPass(new AdminExtensionCompilerPass());
    }
//
//    /**
//     * Register form mapping information.
//     *
//     * NEXT_MAJOR: remove this method
//     */
//    public function registerFormMapping()
//    {
//        if (class_exists(FormHelper::class)) {
//            FormHelper::registerFormTypeMapping([
//                'sonata_republicandate_type' => RepublicandateType::class,
//            ]);
//        }
//    }
}
