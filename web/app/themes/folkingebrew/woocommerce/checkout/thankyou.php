<?php
/**
 * Theme: Order Received (Thank You) page
 */

defined('ABSPATH') || exit;

/** Render Blade view */
echo \Roots\view('webshop.thankyou', ['order' => $order])->render();

