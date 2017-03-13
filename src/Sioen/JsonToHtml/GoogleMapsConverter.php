<?php

namespace Sioen\JsonToHtml;

final class GoogleMapsConverter implements Converter
{
    public function toHtml(array $data)
    {
        return sprintf(
            '<iframe src="https://www.google.com/maps?q=%s,%s&output=embed" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>',
            $data['lat'],
            $data['lng']
        );
    }

    public function matches($type)
    {
        return $type === 'google_maps';
    }
}
