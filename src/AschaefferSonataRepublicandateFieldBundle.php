<?php

namespace Aschaeffer\SonataRepublicandateFieldBundle;

use Aschaeffer\SonataRepublicandateFieldBundle\DependencyInjection\Compiler\AdminExtensionCompilerPass;
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

        $container->addCompilerPass(new AdminExtensionCompilerPass());
    }
}
