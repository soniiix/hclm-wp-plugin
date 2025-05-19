<?php

// Function to build a highlighted excerpt around a search term
function get_highlighted_excerpt(string $text, string $query, $word_count = 20, $context_words = 10) {
    $text = strip_tags($text);
    $pos = stripos($text, $query);

    if ($pos !== false) {
        // Convert text to an array of words
        $words = preg_split('/\s+/', $text);

        // Find the index of the searched term
        $matchIndex = -1;
        foreach ($words as $i => $word) {
            if (stripos($word, $query) !== false) {
                $matchIndex = $i;
                break;
            }
        }

        // Build excerpt
        if ($matchIndex !== -1) {
            $start = max(0, $matchIndex - $context_words);
            $excerptWords = array_slice($words, $start, $word_count);
            $snippet = implode(' ', $excerptWords);
            $safe_snippet = html_entity_decode($snippet, ENT_QUOTES, 'UTF-8');

            // Highlight the matched term and return the formatted excerpt      
            $highlighted = preg_replace(
                '/' . preg_quote($query, '/') . '/i',
                '<mark>$0</mark>',
                $safe_snippet
            );
            if (count($words) >= 20){
                $excerpt = '... ' . $highlighted . ' ...';
            } else {
                $excerpt = $highlighted;
            }
            return $excerpt;
        }
    }

    // Fallback
    return wp_trim_words($text, $word_count, '...');
}
