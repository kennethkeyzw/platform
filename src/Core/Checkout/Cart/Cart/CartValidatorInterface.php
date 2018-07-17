<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Cart\Cart;

use Shopware\Core\Checkout\Cart\Cart\Struct\CalculatedCart;
use Shopware\Core\Checkout\CheckoutContext;

interface CartValidatorInterface
{
    public function isValid(CalculatedCart $cart, CheckoutContext $context): bool;
}