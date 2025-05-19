<?php

/**
 * Generates an excerpt from a given text with the search term highlighted.
 *
 * @param string $text The original full text to extract from.
 * @param string $query The search term to highlight.
 * @param int $char_count The total length of the excerpt in characters (default: 200).
 *
 * @return string The excerpt with the search term highlighted.
 */
function get_highlighted_excerpt(string $text, string $query, $char_count = 200) {
    $text = strip_tags($text);
    $query = trim($query);

    // Find the position of the search term in the text
    $pos = mb_stripos($text, $query);

    if ($pos !== false) {
        $half = (int) ($char_count / 2);
 
        $start = max(0, $pos - $half);

        // Prevent words cutting at the beginning of the excerpt
        while ($start > 0 && !preg_match('/[\s\p{P}]/u', mb_substr($text, $start - 1, 1))) {
            $start--;
        }

        $end = $start + $char_count;

        // Prevent words cutting at the end of the excerpt
        while ($end < mb_strlen($text) && !preg_match('/[\s\p{P}]/u', mb_substr($text, $end, 1))) {
            $end++;
        }

        // Extract the final excerpt
        $excerpt = mb_substr($text, $start, $end - $start);

        // Highlight the search term
        $highlighted = preg_replace(
            '/' . preg_quote($query, '/') . '/iu',
            '<mark>$0</mark>',
            $excerpt
        );

        $prefix = $start > 0 ? '... ' : '';
        $suffix = $end < mb_strlen($text) ? ' ...' : '';

        return $prefix . $highlighted . $suffix;
    }

    // Fallback: return a trimmed version of the text if the query isn't found
    return wp_trim_words($text, (int) ($char_count / 5), '...');
}
