<?php
namespace Kernel\Extensions;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class  TimeExtension extends AbstractExtension
{
    public function getFilters() : array
    {
        return [
            new TwigFilter('ago',[$this, 'ago'], ['is_safe'=>['html']])
        ];
    }

    /**
     * @param DateTime $time
     * @param string $format
     * @return string
     */
    public function ago(DateTime $time, string $format = 'd-m-Y H:i') : string
    {
        return '<span class="timeago" datetime="'. $time->format(DateTime::ISO8601).'">'. $time->format($format).'</span>';
    }
}