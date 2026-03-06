<?php

namespace App\Models\Concerns;

use League\CommonMark\CommonMarkConverter;

trait HasMarkdown
{
    protected function renderMarkdown(?string $markdown): string
    {
        try {
            $converter = new CommonMarkConverter([
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]);

            return $converter->convert($markdown ?? '')->getContent();
        } catch (\Throwable $e) {
            report($e);
            return '';
        }
    }
}
