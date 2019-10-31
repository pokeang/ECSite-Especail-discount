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

namespace Plugin\ProductSpecialOffer\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation as Eccube;

/**
 * @Eccube\EntityExtension("Eccube\Entity\Product")
 */
trait ProductSpecialOfferTrait
{
    /**
     * @var int
     *
     * @ORM\Column(name="target_sell", type="integer", nullable=true)
     */
    private $target_sell;

    /**
     * @var int
     *
     * @ORM\Column(name="point_offer", type="integer", nullable=true)
     */
    private $point_offer;

    /**
     * @var int
     *
     * @ORM\Column(name="special_offer_days", type="datetime", nullable=true)
     */
    private $days;

    /**
     * @var boolean
     *
     * @ORM\Column(name="flag_special_offer", type="boolean", nullable=true)
     */
    private $isFlag_special_offer;

    /**
     * @param int $target_sell
     */
    public function setTargetSell($target_sell)
    {
        $this->target_sell = $target_sell;
    }
    /**
     * @return int
     */
    public function getTargetSell()
    {
        return $this->target_sell;
    }

    /**
     * @param int $point_offer
     */
    public function setPointOffer($point_offer)
    {
        $this->point_offer = $point_offer;
    }

    /**
     * @return int
     */
    public function getPointOffer()
    {
        return $this->point_offer;
    }

    /**
     * @param int $days
     */
    public function setDays($days)
    {
        $this->days = $days;
    }

    /**
     * @return int
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * @param boolean $isFlag_special_offer
     */
    public function setIsFlagSpecialOffer($isFlag_special_offer)
    {
        $this->isFlag_special_offer = $isFlag_special_offer;
    }

    /**
     * @return boolean
     */
    public function getIsFlagSpecialOffer()
    {
        return $this->isFlag_special_offer;
    }
}
