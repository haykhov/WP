<?php

namespace netzlodern\pwr\models;

class Duration extends BaseModel
{
    protected static function get(): array
    {
        return [
            '' => 'beliebig',
            'exact' => 'exakt wie angegeben',
            '7' => '1 Woche',
            '14' => '2 Wochen',
            '21' => '3 Wochen',
            '1,4' => '1-4 Tage',
            '5,8' => '5-8 Tage',
            '9,12' => '9-12 Tage',
            '13,15' => '13-15 Tage',
            '16,22' => '16-22 Tage',
            '1' => '1 Tag',
            '2' => '2 Tage',
            '3' => '3 Tage',
            '4' => '4 Tage',
            '5' => '5 Tage',
            '6' => '6 Tage',
            '7' => '7 Tage',
            '8' => '8 Tage',
            '9' => '9 Tage',
            '10' => '10 Tage',
            '11' => '11 Tage',
            '12' => '12 Tage',
            '13' => '13 Tage',
            '14' => '14 Tage',
            '15' => '15 Tage',
            '16' => '16 Tage',
            '17' => '17 Tage',
            '18' => '18 Tage',
            '19' => '19 Tage',
            '20' => '20 Tage',
            '21' => '21 Tage',
            '22' => '22 Tage',
            '23,100' => '&gt;22 Tage',
        ];
    }

    public static function getDurationOptions(): string
    {
        $durationOptions = '';
        foreach (self::get() as $key => $value) {
            $durationOptions .= sprintf(
                '<option value="%s">%s</option>',
                esc_attr($key),
                esc_html($value)
            );
        }

        return $durationOptions;
    }

    public static function batchReplace(array $data): void
    {

    }
}

