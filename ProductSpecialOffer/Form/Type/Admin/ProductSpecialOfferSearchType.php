<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\ProductSpecialOffer\Form\Type\Admin;

use Eccube\Common\EccubeConfig;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ProductSpecialOfferSearchType.
 * [製品特別オファー]-[レビュー検索]用Form.
 */
class ProductSpecialOfferSearchType extends AbstractType
{

    /**
     * {@inheritdoc}
     * build form method.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$config = $this->eccubeConfig;
        $builder
            ->add('product_name', TextType::class, [
                'label' => 'product_special_offer.admin.product_special_offer.search_product_name',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 150]),
                ],
            ])
            ->add('target_sell', TextType::class, [
                'label' => 'product_special_offer.admin.product_special_offer.search_product_target_sell',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 1000]),
                ],
            ]);
    }
}
