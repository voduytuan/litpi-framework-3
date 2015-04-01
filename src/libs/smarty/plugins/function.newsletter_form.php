<?php

function smarty_function_newsletter_form($params)
{
    $html = '<form class="form" action="" onsubmit="return false;">
                <input type="text" name="newsletter-email" placeholder="Địa chỉ email của bạn">
                <button type="button" class="btn-newsletter">Gửi</button>
            </form>';

    return $html;
}
