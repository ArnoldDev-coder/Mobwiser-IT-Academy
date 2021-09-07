<?php

namespace Kernel\Extensions;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FormExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('field', [$this, 'field'],
                ['is_safe' => ['html'],
                    'needs_context' => true
                ])
        ];
    }

    public function field(array $context, string $key, mixed $value, ?string $label = null, array $options = []): string
    {
        $type = $options['type'] ?? 'text';
        $error = $this->getErrorHtml($context, $key);
        $class = 'form-group';
        $value = $this->convertValue($value);
        $attributes = [
            'class' => trim('form-control ' . ($options['class'] ?? '')),
            'name' => $key,
            'id' => $key
        ];

        if ($error) {
            $class .= ' has-danger';
            $attributes['class'] .= ' form-control-danger';
        }

        if ($type === 'textarea') {

            $input = $this->textarea($value, $attributes);
        } elseif ($type === 'number') {
            $input = $this->number($value, $attributes);
        } elseif (array_key_exists('options', $options)) {
            $input = $this->select($value, $options['options'], $attributes);
        } elseif ($type === 'file') {
            $input = $this->file($attributes);
        } elseif ($type === 'checkbox') {
            $input = $this->checkbox($value, $attributes);
        } elseif ($type === 'password') {
            $input = $this->password($value, $attributes);
        } else {
            $input = $this->input($value, $attributes);
        }
        return "<div class=$class>
                     <label for='$key'>$label</label>
                     $input
                     $error
                 </div>";
    }

    private function textarea(?string $value, array $attributes): string
    {
        return "<textarea " . $this->getHtmlFromArray($attributes) . ">{$value}</textarea>";
    }

    private function getErrorHTML(array $context, string $key): string
    {
        $error = $context['errors'][$key] ?? false;
        if ($error) {
            return "<small class='form-text text-muted'>$error</small>";
        }
        return "";
    }

    private function input(mixed $value, array $attributes): string
    {
        return "<input type='text'" . $this->getHtmlFromArray($attributes) . "value=$value>";
    }

    private function password(mixed $value, array $attributes): string
    {
        return "<input type='password'" . $this->getHtmlFromArray($attributes) . "value=$value>";
    }

    private function file(array $attributes): string
    {
        return "<input type='file'" . $this->getHtmlFromArray($attributes) . ">";
    }

    private function number(mixed $value, array $attributes): string
    {
        return "<input type='number'" . $this->getHtmlFromArray($attributes) . "value=$value>";
    }


    /**
     * Transforme un tableau $clef => $valeur en attribut HTML
     * @param array $attributes
     * @return string
     */
    private function getHtmlFromArray(array $attributes): string
    {
        $htmlParts = [];
        foreach ($attributes as $key => $value) {
            if ($value === true) {
                $htmlParts[] = (string)$key;
            } elseif ($value !== false) {
                $htmlParts[] = "$key=\"$value\"";
            }
        }
        return implode(' ', $htmlParts);
    }

    private function convertValue(mixed $value): string
    {
        if ($value instanceof DateTime) {
            return $value->format('Y-m-d H:i:s');
        }
        return (string)$value;
    }

    /**
     * Génère un <select>
     * @param null|string $value
     * @param array $options
     * @param array $attributes
     * @return string
     */
    private function select(?string $value, array $options, array $attributes): string
    {
        $htmlOptions = array_reduce(array_keys($options), function (string $html, string $key) use ($options, $value) {
            $params = ['value' => $key, 'selected' => $key === $value];
            return $html . '<option ' . $this->getHtmlFromArray($params) . '>' . $options[$key] . '</option>';
        }, "");
        return "<select rows=\"5\" cols=\"20\"" . $this->getHtmlFromArray($attributes) . ">$htmlOptions</select>";
    }

    /**
     * Génère un <input type="checkbox">
     * @param null|string $value
     * @param array $attributes
     * @return string
     */
    private function checkbox(?string $value, array $attributes): string
    {
        $html = '<input type="hidden" name="' . $attributes['name'] . '" value="0"/>';
        if ($value) {
            $attributes['checked'] = true;
        }
        return $html . "<input type=\"checkbox\" " . $this->getHtmlFromArray($attributes) . " value=\"1\">";
    }

}