<?php
/*
Plugin Name: Ethereum Price Tooltip
Description: Plugin will find mentions of Ethereum in your texts and automatically add a toltip to it with actual price in USD and EUR.
Version: 1.1
Author: plugins.club
Author URI: https://plugins.club/wordpress/ethereum-price-tooltip/
*/

function add_tooltip_to_eth($the_content)
{
    static $eth_in_text = array(
        '0' => 'ETH',
        '1' => 'Ethereum',
    );

    $url = "https://min-api.cryptocompare.com/data/price?fsym=ETH&tsyms=USD,EUR";
    $json = json_decode(file_get_contents($url));
    $ethAnswerUsd = $json->USD;
    $ethAnswerEUR = $json->EUR;

    for ($i = 0, $c = count($eth_in_text);$i < $c;$i++)
    {
        $the_content = preg_replace('#' . $eth_in_text[$i] . '#iu', '<span class="tooltip" data-tooltip="' . $ethAnswerUsd . ' USD / ' . $ethAnswerEUR . ' EUR">' . $eth_in_text[$i] . '</span>', $the_content);
    }

    return $the_content;
}

function add_eth_tooltip_css()
{
    wp_enqueue_style('tooltip-style', plugins_url('tooltip-style.css', __FILE__) , false, '1.0.0', 'all');
}

add_action('wp_enqueue_scripts', "add_eth_tooltip_css");
add_filter('the_content', 'add_tooltip_to_eth');
