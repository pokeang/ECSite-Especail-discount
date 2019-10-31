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

namespace Plugin\ProductSpecialOffer\Controller\Admin;

use Eccube\Controller\AbstractController;
use Eccube\Repository\Master\PageMaxRepository;
use Eccube\Util\FormUtil;
use Eccube\Service\MailService;
use Eccube\Repository\CustomerRepository;
use Knp\Component\Pager\PaginatorInterface;
use Plugin\ProductSpecialOffer\Form\Type\Admin\ConfigType;
use Plugin\ProductSpecialOffer\Repository\ConfigRepository;
use Plugin\ProductSpecialOffer\Form\Type\Admin\ProductSpecialOfferSearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;


/**
 * Class ProductSpecialOfferController.
 */
class ProductSpecialOfferController extends AbstractController
{
  /**
   * @var PageMaxRepository
   */
  protected $pageMaxRepository;

  /**
   * @var ConfigRepository
   */
  protected $configRepository;

  /**
   * @var MailService
   */
  protected $mailService;

  /**
   * @var CustomerRepository
   */
  protected $customerRepository;

  /**
   * ConfigController constructor.
   *
   * @param PageMaxRepository $pageMaxRepository
   * @param ConfigRepository $configRepository
   * @param CustomerRepository $customerRepository
   */
  public function __construct(ConfigRepository $configRepository, PageMaxRepository $pageMaxRepository, CustomerRepository $customerRepository, MailService $mailService)
  {
      $this->pageMaxRepository = $pageMaxRepository;
      $this->configRepository = $configRepository;
      $this->customerRepository = $customerRepository;
      $this->mailService = $mailService;

  }
    /**
     *
     * @Route("/%eccube_admin_route%/product_special_offer", name="product_special_offer_admin_product_special_offer")
     * @Route("/%eccube_admin_route%/product_special_offer/page/{page_no}", requirements={"page_no" = "\d+"}, name="product_special_offer_admin_product_special_offer_page")
     * @Template("@ProductSpecialOffer/admin/index.twig")
     * @param Request $request
     * @param null $page_no
     *
     * @return array
     */
    public function index(Request $request, $page_no = null, PaginatorInterface $paginator)
    {
       $builder = $this->formFactory->createBuilder(ProductSpecialOfferSearchType::class);
       $searchForm = $builder->getForm();
       $pageMaxis = $this->pageMaxRepository->findAll();
       $pageCount = $this->session->get(
          'product_special_offer.admin.product_special_offer.search.page_count',
          $this->eccubeConfig['eccube_default_page_count']
      );
      $pageCountParam = $request->get('page_count');
      if ($pageCountParam && is_numeric($pageCountParam)) {
          foreach ($pageMaxis as $pageMax) {
              if ($pageCountParam == $pageMax->getName()) {
                  $pageCount = $pageMax->getName();
                  $this->session->set('product_special_offer.admin.product_special_offer.search.page_count', $pageCount);
                  break;
              }
          }
      }

      if ('POST' === $request->getMethod()) {
          $searchForm->handleRequest($request);

          if ($searchForm->isValid()) {

              $searchData = $searchForm->getData();
              $page_no = 1;

              $this->session->set('product_special_offer.admin.product_special_offer.search', FormUtil::getViewData($searchForm));
              $this->session->set('product_special_offer.admin.product_special_offer.search.page_no', $page_no);

          } else {
            // action from click push complete btn
            if ($request->get('is_update_customer_piont') == true) {
              log_info('get data update customers points', [
                'is_update_customer_piont' => $request->get('is_update_customer_piont'),
                'product_id' => $request->get('product_id')
              ]);
              $result = $this->configRepository->getUpdateCustomerPoint($request->get('product_id'));
              $customersId = $result['customersId'];
              if(count($customersId) > 0) {
                for($i = 0; $i < count($customersId); $i++) {
                  $Customer = $this->customerRepository->find($customersId[$i]);
                  if (is_null($Customer)) {
                      throw new NotFoundHttpException();
                  }
                  // メール送信
                  $this->mailService->sendSpecialProductCompleteMail($Customer);
                }
                log_info('sent email to customers', ['count customer' => count($customersId)]);
              }

              return [
                  'searchForm' => $searchForm->createView(),
                  'pagination' => [],
                  'pageMaxis' => $pageMaxis,
                  'page_no' => $page_no,
                  'page_count' => $pageCount,
                  'has_errors' => false,
                  'result' => count($result)
              ];
            }
              return [
                  'searchForm' => $searchForm->createView(),
                  'pagination' => [],
                  'pageMaxis' => $pageMaxis,
                  'page_no' => $page_no,
                  'page_count' => $pageCount,
                  'has_errors' => true,
              ];
          }
      } else {
          if (null !== $page_no || $request->get('resume')) {
              if ($page_no) {
                  $this->session->set('product_special_offer.admin.product_special_offer.search.page_no', (int) $page_no);
              } else {
                  $page_no = $this->session->get('product_special_offer.admin.product_special_offer.search.page_no', 1);
              }
              $viewData = $this->session->get('product_special_offer.admin.product_special_offer.search', []);
          } else {
              $page_no = 1;
              $viewData = FormUtil::getViewData($searchForm);
              $this->session->set('product_special_offer.admin.product_special_offer.search', $viewData);
              $this->session->set('product_special_offer.admin.product_special_offer.search.page_no', $page_no);
          }
          $searchData = FormUtil::submitAndGetData($searchForm, $viewData);
      }
      $qb = $this->configRepository->getProductSpecialOfferWasBoughtSearchList($searchData);
      //print_r($qb);die();
      $pagination = $paginator->paginate(
          $qb,
          $page_no,
          $pageCount
      );

      return [
           'searchForm' => $searchForm->createView(),
           'pagination' => $pagination,
           'pageMaxis' => $pageMaxis,
           'page_no' => $page_no,
           'page_count' => $pageCount,
           'has_errors' => false,
      ];
    }
}
