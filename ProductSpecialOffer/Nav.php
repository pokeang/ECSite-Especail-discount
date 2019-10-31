<?php

namespace Plugin\ProductSpecialOffer;

use Eccube\Common\EccubeNav;

class Nav implements EccubeNav
{
    /**
     * @return array
     */
    public static function getNav()
    {
      return [
          'product' => [
              'children' => [
                  'product_special_offer' => [
                      'name' => 'product_special_offer.admin.product_special_offer.title',
                      'url' => 'product_special_offer_admin_product_special_offer',
                  ],
              ],
          ],
      ];
    }
}
