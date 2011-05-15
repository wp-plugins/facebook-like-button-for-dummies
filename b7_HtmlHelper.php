<?php
class b7_HtmlHelper {
    const CHECKBOX_VALUE = 'Y';

    public static function textBox($name, $value, $attr = NULL) {
        $defaults = array(
            'type' => 'text',
            'name' => $name,
            'value'=> $value
        );
        return self::shortElement('input', $attr, $defaults);
    }

    public static function checkbox($name, $is_checked, $attr = NULL) {
        $defaults = array(
            'type' => 'checkbox',
            'name' => $name,
            'value'=> self::CHECKBOX_VALUE
        );
        if ($is_checked) {
            $defaults['checked'] = 'checked';
        }
        return self::shortElement('input', $attr, $defaults);
    }

    public static function listbox($name, $options, $value, $attr = NULL) {
        $defaults = array(
            'name' => $name,
        );
        $out = self::openTag('select', $attr, $defaults);
        foreach ($options as $optValue) {
            $optAttr = array('value' => $optValue);
            if ($optValue == $value) {
                $optAttr['selected'] = '1';
            }
            $out .= self::openTag('option', $optAttr, NULL).$optValue.self::closeTag('option');
        }
        $out .= self::closeTag('select');
        return $out;
    }

    public function shortElement($tag, $attr, $defaults) {
        $out = "<$tag";
        foreach (array_merge((array)$defaults, (array) $attr) as $name => $value) {
            $out .= ' '.$name.'="'.$value.'"';
        }
        return $out.' />';
    }

    public function openTag($tag, $attr, $defaults) {
        $out = "<$tag";
        foreach (array_merge((array)$defaults, (array) $attr) as $name => $value) {
            $out .= ' '.$name.'="'.$value.'"';
        }
        return $out.'>';
    }

    public function closeTag($tag) {
        return '</'.$tag.'>';
    }

}
?>
