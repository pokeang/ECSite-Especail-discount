<?php

namespace Plugin\ProductSpecialOffer;

use Eccube\Entity\Product;
use Eccube\Event\TemplateEvent;
use Plugin\ProductSpecialOffer\Repository\ConfigRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class Event implements EventSubscriberInterface
{
  /**
   * @var ConfigRepository
   */
   protected $configRepository;

   /**
    * Event constructor.
    *
    * @param ConfigRepository $configRepository
    */
     public function __construct(ConfigRepository $configRepository) {
         $this->configRepository = $configRepository;
     }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return ['Product/detail.twig' => 'onTemplateProductDetail'];
    }

    /**
     * Append JS to display product special offer
     *
     * @param TemplateEvent $templateEvent
     */
    public function onTemplateProductDetail(TemplateEvent $event)
    {
        /** @var Product $Product */
        $product = $event->getParameter('Product');


        if(!empty($product['target_sell']) && !empty($product['point_offer']) && !empty($product['days']) && $product['isFlag_special_offer'] == 0) {
          $numBuyers = $this->configRepository->getRemainBuyers($product);
          $dateCreate  = $numBuyers['updateDate'];
          $getNumBuyers = $numBuyers['remainBuyer'];
          $specailOfferDay = $product['days'];
          $from_time = date_create(date('Y-m-d H:i:s'));
          $to_time = date_create($specailOfferDay->format('Y-m-d H:i:s'));
          $dateRemain = date_diff($from_time, $to_time)->format('%R%a days %H:%I:%S');
          $parameters = $event->getParameters();
          $parameters['ProductSpecialOfferRemainDays'] = $specailOfferDay->format('Y-m-d H:i:s');//date_diff($from_time, $to_time)->format('%a days %Hh %Imin %Sss');
          $parameters['ProductSpecialOfferRemainBuyer'] = $getNumBuyers;
          $event->setParameters($parameters);

          if($product['flagSpecialOffer'] != true) {
            if(strpos($dateRemain, '+') !== false) {
              if($product['target_sell'] > $getNumBuyers ) {
                if($product['target_sell'] == $getNumBuyers) {
                  $this->configRepository->getUpdateCustomerPoint($numBuyers);
                }
                $event->addSnippet('@ProductSpecialOffer/default/ProductSpecialOffer.twig');
              }
            }
          }
        }
    }
}
