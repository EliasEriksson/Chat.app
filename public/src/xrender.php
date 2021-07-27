<?php

function render(string $templateFile, array $context): string
{
    $template = file_get_contents($templateFile);

    foreach ($context as $variable => $value) {
        $template = preg_replace("/{{\s*$variable\s*}}/", $value, $template);
    }
    if (preg_match_all("/{{[^}]*}}/", $template)) {
        return "";
    } else {
        return $template;
    }
}
