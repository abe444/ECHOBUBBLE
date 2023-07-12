<?php 
///// ALL CREDIT GOES TO TEXTBOARD.LOL /////
function markdown_to_html(string $text): string {
    
    // newline
    $text = nl2br($text);
    
    // **bold**
    $text = preg_replace('/(\*\*)(?!\*)([^*]*?)(?<!\*)\1/', '<b>$2</b>', $text);

    // *italic*
    $text = preg_replace('/(\*)(?!\*)([^*]*?)(?<!\*)\1/', '<i>$2</i>', $text);

    // ~~strikethrough~~
    $text = preg_replace('/(~~)(.*?)\1/', '<del>$2</del>', $text);

    // __underscore__
    $text = preg_replace('/(__)(.*?)\1/', '<u>$2</u>', $text);

    // %%spoiler%%
    $text = preg_replace('/(%%)(.*?)\1/', '<span id="spoiler">$2</span>', $text);

    // $$shaketext$$ 
    $text = preg_replace('/(\$\$)(.*?)\1/', '<span class="shaketext">$2</span>', $text);

    // $rainbowtext$ 
    $text = preg_replace('/(\$)(.*?)\1/', '<span class="rainbowtext">$2</span>', $text);

    // ^^3D text^^ 
    $text = preg_replace('/(\^\^)(.*?)\1/', '<span class="threedtext">$2</span>', $text);

    // !!GLOW text!! 
    $text = preg_replace('/(\!\!)(.*?)\1/', '<span class="glow">$2</span>', $text);

    // ==Red text== 
    $text = preg_replace('/(\=\=)(.*?)\1/', '<span class="redtext">$2</span>', $text);

    // <Blue text 
    $text = preg_replace('/(^|\r\n|\n|\r)&lt;(.*?)($|\r\n|\n|\r(?!\n))(?!(\r\n)?&lt;)(\r\n|\n|\r)*/s',
                         '<span class="bluetext">&lt;$2</span>', $text);
    
    // >GREEN text 
    $text = preg_replace('/(^|\r\n|\n|\r)&gt;(.*?)($|\r\n|\n|\r(?!\n))(?!(\r\n)?&gt;)(\r\n|\n|\r)*/s',
                         '<span class="greentext">&gt;$2</span>', $text);

    return $text;
}
function sanitize(string $input): string {
    // remove format chars
    $input = preg_replace('/\p{Cf}/u', '', $input);
    // Trim, sanitize HTML and return formatted string
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
};
function contains_long_ass_word(string $input, int $max_length): bool {
    $words = preg_split('/[\s[:punct:]]+/', $input);
    foreach ($words as $word) {
        if (strlen($word) > $max_length) {
            return true;
        }
    }
    return false;
}

///// ALL CREDIT GOES TO TEXTBOARD.LOL /////