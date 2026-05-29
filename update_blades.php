<?php
$dir = new RecursiveDirectoryIterator('c:\\xampp\\htdocs\\graduation project\\resources\\views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/^.+\\.blade\\.php$/i', RecursiveRegexIterator::GET_MATCH);

foreach($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $original = $content;
    
    // replacements
    $content = str_replace('->member->full_name', '->member->user->name', $content);
    $content = str_replace('->member?->full_name', '->member?->user?->name', $content);
    $content = preg_replace('/\$member->full_name/', '\$member->user->name', $content);
    
    $content = str_replace('->member->national_id', '->member->user->national_id', $content);
    $content = str_replace('->member?->national_id', '->member?->user?->national_id', $content);
    $content = preg_replace('/\$member->national_id/', '\$member->user->national_id', $content);
    
    // Fix auth fields (like $user->member->user->name -> $user->member->user->name is fine but $user->name is better, actually $user->member->full_name became $user->member->user->name, let's leave it as it will work or fix if broken)
    
    if ($content !== $original) {
        file_put_contents($path, $content);
        echo 'Updated: ' . $path . PHP_EOL;
    }
}
