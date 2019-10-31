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

namespace Plugin\ProductSpecialOffer\Form\Extension;

use Eccube\Common\EccubeConfig;
use Eccube\Form\Type\Admin\ProductType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ProductTypeSpecialOfferExtension extends AbstractTypeExtension
{
    /**
     * @var EccubeConfig
     */
    private $eccubeConfig;

    /**
     * ProductTypeSpecialOfferExtension constructor.
     *
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(EccubeConfig $eccubeConfig)
    {
        $this->eccubeConfig = $eccubeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('target_sell', IntegerType::class, [
                'label' => 'product_special_offer.admin.target_sell',
                'required' => false,
                'eccube_form_options' => [
                    'auto_render' => true,
                ],
                'attr' => [
                    'placeholder' => 'product_special_offer.admin.placeholder.target_sell',
                ],
            ])
            ->add('point_offer', IntegerType::class, [
                'label' => 'product_special_offer.admin.point',
                'required' => false,
                'eccube_form_options' => [
                    'auto_render' => true,
                ],
                'attr' => [
                    'placeholder' => 'product_special_offer.admin.placeholder.point',
                ],
            ])
            ->add('days', DateType::class, [
                'label' => 'product_special_offer.admin.target_days',
                'widget' => 'single_text',
                'required' => false,
                'eccube_form_options' => [
                    'auto_render' => true,
                ],
                'attr' => [
                    'placeholder' => 'product_special_offer.admin.placeholder.target_days',
                ],
            ])
            ->add('isFlag_special_offer', CheckboxType::class, [
              'label'    => 'product_special_offer.admin.flag_special_offer',
              'required' => false,
                'eccube_form_options' => [
                    'auto_render' => true,
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return ProductType::class;
    }
}
