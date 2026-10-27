<?php

function fieldIsEmpty($content){
    if($content === null) return true;
    $text = strip_tags( $content, '<img><svg><video><audio><iframe><picture><embed><object><script><style>' );
    $text = html_entity_decode( $text, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
    $text = preg_replace( '/[\s\x{00A0}\x{200B}\x{FEFF}]+/u', '', $text );
    return $text === '';
}