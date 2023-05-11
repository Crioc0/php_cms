<?php

class PostsNew
{

    public function indexAction(){
        echo 'Индекс New-Posts';
    }
    public function testAction(){
        echo 'test New-Posts';
    }
    public function testPageAction(){
        echo 'Тестирования lowerCamelCase';
    }

    public function before(){
        echo 'PostNew::testPage';
    }
}