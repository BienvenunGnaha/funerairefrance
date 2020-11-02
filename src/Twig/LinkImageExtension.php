<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class LinkImageExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('link_image', [$this, 'link_image']),
        ];
    }

    public function link_image($link): ?string
    {
       if(is_string($link) && $link !== '') 
       {
           $link_arr = explode('/public/', $link);

           return isset($link_arr[1]) ? $link_arr[1] : $link;
       }
       
        return null;
    }
}
