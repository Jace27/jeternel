<?php


namespace App;


class Settings
{
    public static $posts_per_page = 10;
    public static $title_site_name = 'База знаний Jeternel';
    public static $tinymce_settings = 'plugins: \'lists advlist link autolink charmap fullscreen image paste insertdatetime media spellchecker table\', toolbar: \' undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | removeformat | fontselect | fontsizeselect\', language: \'ru\', charmap_append: [ [0x0301, \'Знак ударения\'] ]';
}
