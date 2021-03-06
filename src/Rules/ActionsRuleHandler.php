<?php

declare(strict_types=1);

namespace Arachne\ComponentsProtection\Rules;

use Arachne\ComponentsProtection\Exception\InvalidArgumentException;
use Arachne\Verifier\Exception\VerificationException;
use Arachne\Verifier\RuleHandlerInterface;
use Arachne\Verifier\RuleInterface;
use Nette\Application\Request;
use Nette\Application\UI\Presenter;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class ActionsRuleHandler implements RuleHandlerInterface
{
    /**
     * @throws VerificationException
     */
    public function checkRule(RuleInterface $rule, Request $request, ?string $component = null): void
    {
        if (!$rule instanceof Actions) {
            throw new InvalidArgumentException(sprintf('Unknown rule "%s" given.', get_class($rule)));
        }

        if ($rule->actions === ['*']) {
            return;
        }

        $parameters = $request->getParameters();
        if (!in_array($parameters[Presenter::ACTION_KEY], $rule->actions, true)) {
            throw new VerificationException($rule, 'Component is inaccessible for the given action.');
        }
    }
}
