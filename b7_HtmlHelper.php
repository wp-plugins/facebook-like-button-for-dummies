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

    public function shortElement($tag, $attr, $defaults) {
        $out = "<$tag";
        foreach (array_merge((array)$defaults, (array) $attr) as $name => $value) {
            $out .= ' '.$name.'="'.$value.'"';
        }
        return $out.' />';
    }
}
?>
