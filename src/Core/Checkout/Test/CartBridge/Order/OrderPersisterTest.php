<?php declare(strict_types=1);
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Shopware\Core\Checkout\Test\CartBridge\Order;

use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Cart\Cart\Struct\CalculatedCart;
use Shopware\Core\Checkout\Cart\Cart\Struct\Cart;
use Shopware\Core\Checkout\Cart\Delivery\Struct\DeliveryCollection;
use Shopware\Core\Checkout\Cart\Error\ErrorCollection;
use Shopware\Core\Checkout\Cart\LineItem\CalculatedLineItem;
use Shopware\Core\Checkout\Cart\LineItem\CalculatedLineItemCollection;
use Shopware\Core\Checkout\Cart\LineItem\LineItemCollection;
use Shopware\Core\Checkout\Cart\Order\OrderConverter;
use Shopware\Core\Checkout\Cart\Order\OrderPersister;
use Shopware\Core\Checkout\Cart\Price\Struct\CalculatedPrice;
use Shopware\Core\Checkout\Cart\Price\Struct\CartPrice;
use Shopware\Core\Checkout\Cart\Tax\Struct\CalculatedTaxCollection;
use Shopware\Core\Checkout\Cart\Tax\Struct\TaxRuleCollection;
use Shopware\Core\Checkout\Cart\Tax\TaxDetector;
use Shopware\Core\Checkout\CheckoutContext;
use Shopware\Core\Checkout\Customer\Aggregate\CustomerAddress\CustomerAddressStruct;
use Shopware\Core\Checkout\Customer\CustomerStruct;
use Shopware\Core\Framework\ORM\EntityRepository;

class OrderPersisterTest extends TestCase
{
    public function testSave(): void
    {
        $faker = Factory::create();
        $repository = $this->createMock(EntityRepository::class);
        $repository->expects($this->once())->method('create');

        $taxDetector = new TaxDetector();

        $billingAddress = new CustomerAddressStruct();
        $billingAddress->setId('SWAG-ADDRESS-ID-1');
        $billingAddress->setSalutation('mr');
        $billingAddress->setFirstName($faker->firstName);
        $billingAddress->setLastName($faker->lastName);
        $billingAddress->setZipcode($faker->postcode);
        $billingAddress->setCity($faker->city);
        $billingAddress->setCountryId('SWAG-AREA-COUNTRY-ID-1');

        $customer = new CustomerStruct();
        $customer->setId('SWAG-CUSTOMER-ID-1');
        $customer->setDefaultBillingAddress($billingAddress);

        $converter = new OrderConverter($taxDetector);
        $persister = new OrderPersister($repository, $converter);

        $checkoutContext = $this->createMock(CheckoutContext::class);
        $checkoutContext->expects($this->any())->method('getCustomer')->willReturn($customer);

        $cart = new CalculatedCart(
            new Cart('A', 'a-b-c', new LineItemCollection(), new ErrorCollection()),
            new CalculatedLineItemCollection([
                new CalculatedLineItem(
                    'test',
                    new CalculatedPrice(1, 1, new CalculatedTaxCollection(), new TaxRuleCollection()),
                    1,
                    'test',
                    'test'
                ),
            ]),
            new CartPrice(1, 1, 1, new CalculatedTaxCollection(), new TaxRuleCollection(), CartPrice::TAX_STATE_FREE),
            new DeliveryCollection()
        );

        $persister->persist($cart, $checkoutContext);
    }
}