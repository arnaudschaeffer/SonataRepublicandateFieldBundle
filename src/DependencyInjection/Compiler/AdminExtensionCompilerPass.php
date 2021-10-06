<?php

namespace Aschaeffer\SonataRepublicandateFieldBundle\DependencyInjection\Compiler;

use Aschaeffer\SonataRepublicandateFieldBundle\Annotation\RepublicandateField;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AdminExtensionCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $annotationReader = $container->get('annotations.reader');

        foreach ($container->findTaggedServiceIds('sonata.admin') as $id => $attributes) {
            $admin = $container->getDefinition($id);
            $modelClass = $container->getParameterBag()->resolveValue($admin->getArgument(1));
            if (!$modelClass || !class_exists($modelClass)) {
                continue;
            }
            $modelClassReflection = new \ReflectionClass($modelClass);

            foreach ($modelClassReflection->getProperties() as $reflectionProperty) {
                if ($annotationReader->getPropertyAnnotation($reflectionProperty, RepublicandateField::ANNOTATION_NAME)) {
                    $adminExtensionReference = new Reference('aschaeffer.sonatarepublicandatefield.admin.extension.republicandatefield');
                    $admin->addMethodCall('addExtension', [$adminExtensionReference]);
                    break;
                }
            }
        }
    }
}
