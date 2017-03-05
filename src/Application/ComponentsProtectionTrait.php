<?php

namespace Arachne\ComponentsProtection\Application;

use Arachne\ComponentsProtection\Exception\MissingAnnotationException;
use Arachne\Verifier\Application\VerifierPresenterTrait;
use Doctrine\Common\Annotations\Reader;
use Nette\ComponentModel\IComponent;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
trait ComponentsProtectionTrait
{
    use VerifierPresenterTrait;

    /** @var Reader */
    private $reader;

    /**
     * @param Reader $reader
     */
    final public function injectReader(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Component factory. Delegates the creation of components to a createComponent<Name> method.
     *
     * @param string $name
     *
     * @return IComponent|null
     */
    protected function createComponent($name)
    {
        $method = 'createComponent'.ucfirst($name);
        if (method_exists($this, $method)) {
            $reflection = $this->getReflection()->getMethod($method);
            if (!$this->reader->getMethodAnnotation($reflection, 'Arachne\ComponentsProtection\Rules\Actions')) {
                throw new MissingAnnotationException("Missing annotation @Arachne\ComponentsProtection\Rules\Actions for component '$name'.");
            }
            $this->checkRequirements($reflection);
        }

        return parent::createComponent($name);
    }
}
