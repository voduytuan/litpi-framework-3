<?php

function smarty_function_cms_mobile_collection($params)
{
    $templateidentifier = $params['templateidentifier'];
    $setionclass = $params['selectionclass'] != ''
                    ? $params['selectionclass'] : 'latestproduct';
    $idscroll = $params['idscroll'] != '' ? $params['idscroll'] : 'owl-latest';
    $collectionList =  \Model\Cms\Collection::getProductFromCollection($templateidentifier);

    $collection = $collectionList['collection'];
    $products = $collectionList['products'];
    $productCategory = $collectionList['productCategory'];

    $html = '';
    if ($collection->id > 0) {
        $html .= '<section class="' . $setionclass . '">';
        $html .= '<h3>' . $collection->lang->title . '</h3>';
        if ($collection->morelink != '') {
            $html .= '<a href="' . $collection->morelink . '" class="viewmorestyle">›</a>';
        }

        if (count($products) > 0) {
            foreach ($products as $myProduct) {
                //Get promotion
                $myPromotion = array();
                $promotionList = $myProduct->getPromotionForProduct();
                if (count($promotionList) > 0) {
                    $myPromotion = $promotionList[0];
                }
                
                $html .= '<a href="' . $myProduct->getProductUrl($productCategory[$myProduct->id]->lang->slug)
                            . '" class="" title="'. $myProduct->lang->name .'">';
                $html .= '<img class="" width="270" height="270" alt="'
                        . $myProduct->lang->name . '" src="' . $myProduct->getSmallImage()
                        . '" data-at2x="'.$myProduct->getSmallImage(true).'">';
                $html .= '<span></span>';
                $html .= '<strong>' . $myProduct->lang->name . '</strong>';
                $html .= '<label>';
                if ($myProduct->sellprice <= 0) {
                    $html .= 'Giá liên hệ';
                } else {
                    if (!empty($myPromotion) && $myPromotion['discount'] > 0) {
                        $discountValue = '';
                        if ($myPromotion['valuetype'] == Model\Sale\Promotion\Promotion::VALUETYPE_PERCENT) {
                            $discountValue = '-' . $myPromotion['value'] . '%';
                        }
                        $html .= '<b class="price-linethrough">Giá bán: <i>'
                        . $collectionList['currency']['currencyprefix']
                        . number_format($myProduct->sellprice, 0, ',', '.')
                        . $collectionList['currency']['currency']
                        . '</i>'.$discountValue.'</b>';
                        $html .= 'Giá KM: ' . $collectionList['currency']['currencyprefix']
                        . number_format($myPromotion['discountprice'], 0, ',', '.')
                        . $collectionList['currency']['currency'];
                    } else {
                        $html .= 'Giá bán: ' . $collectionList['currency']['currencyprefix']
                        . number_format($myProduct->sellprice, 0, ',', '.')
                        . $collectionList['currency']['currency'];
                    }
                }
                $html .= '</label>';
                $html .= '</a>';
            }
            $html .= '<div class="clr"></div>';
        }
        $html .= '</section>';
    }
    echo $html;
}
