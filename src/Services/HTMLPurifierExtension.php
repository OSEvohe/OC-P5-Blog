<?php


namespace Services;


use HTMLPurifier;
use HTMLPurifier_Config;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class HTMLPurifierExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('html_purifier', array($this, 'purify'), array('is_safe' => array('html'))),
        );
    }

    public function purify($text)
    {
        $elements = array(
            'p',
            'span',
            'br',
            'small',
            'strong', 'b',
            'em', 'i',
            'strike',
            'sub', 'sup',
            'ins', 'del',
            'ol', 'ul', 'li',
            'h2', 'h3', 'h4',
            'dl', 'dd', 'dt',
            'pre', 'code', 'samp', 'kbd',
            'q', 'blockquote', 'abbr', 'cite',
            'table', 'thead', 'tbody', 'th', 'tr', 'td',
            'a[href|target|rel|id]',
            'img[src|title|alt|width|height|style]'
        );

        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', implode(',', $elements));

        $purifier = new HTMLPurifier($config);
        return $purifier->purify($text);
    }

    public function getName()
    {
        return 'html_purifier';
    }
}