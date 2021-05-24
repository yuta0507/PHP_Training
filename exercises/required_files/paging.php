<?php
/** 
 * ページングのファンクションを管理
 * 
 * PHP Version >= 7.2.24
 * 
 * @category Exercise
 * @package  Paging
 * @author   Yuta Kikkawa <kikkawa@ye-p.co.jp>
 * @license  MIT License
 * @link     http://192.168.2.62/exercises/required_files/paging.php
 * */

/**
 * Function outputHref.
 * 
 * @param string $index  文字列
 * @param string $page   文字列
 * @param string $search 文字列
 * @param string $order  文字列
 * 
 * @return string
 * */
function getHref($index, $page, $search, $order) 
{
    $pattern = '/company_id/';
    $href = null;

    //Company
    if (preg_match($pattern, $index) === 0) {
        $href = $index .'?page=' .$page;
        if (!empty($search)) {
            $href .= "&search=$search";
        } 
        if ($order === 'desc') {
            $href .= "&order=desc";
        }
    }

    //Employee
    if (preg_match($pattern, $index) === 1) {
        $href = $index .'&page=' .$page; 
        if ($order === 'desc') {
            $href .= "&order=desc";
        } 
    }

    return $href;
}

/**
 * Function outputHref.
 * 
 * @param string $url      文字列
 * @param string $page     文字列
 * @param string $max_page 文字列
 * @param string $search   文字列
 * @param string $order    文字列
 * 
 * @return null
 * */
function outputPaging($url, $page, $max_page, $search, $order) {
    echo '<ul class="paging">';
    if ($page > 1) {
        print(
            '<li>'.
            '<a href="' .getHref($url, $page-1, $search, $order) .'">'.
            '≪'.
            '</a>'.
            '</li>'
        );
        if ($page > 2) {
            print(
                '<li>'.
                '<a href="' .getHref($url, 1, $search, $order) .'">'.
                1 .
                '</a>'.
                '</li>'
            );
        }
        if ($page > 3 && $max_page != 4) {
            print(
                '<li>'.
                '<span>...</span>'.
                '</li>'
            );
        }
        if ($page == $max_page && $max_page != 3 && $max_page != 2) {
            print(
                '<li>'.
                '<a href="'.getHref($url, $max_page-2, $search, $order).'">'.
                ($max_page-2).
                '</a>'.
                '</li>'
            );
        }
        print(
            '<li>'.
            '<a href="'.getHref($url, $page-1, $search, $order).'">'.
            ($page-1).
            '</a>'.
            '</li>'
        );
    }
    print(
        '<li>'.
        '<a href="'.getHref($url, $page, $search, $order).'" class="current-page">'.
        $page.
        '</a>'.
        '</li>'
    );
    if ($page < $max_page) {
        print(
            '<li>'.
            '<a href="'.getHref($url, $page+1, $search, $order).'">'.
            ($page+1).
            '</a>'.
            '</li>'
        );
        if ($page == 1 && $max_page != 3 && $max_page != 2) {
            print(
                '<li>'.
                '<a href="'.getHref($url, 3, $search, $order).'">'.
                3 .
                '</a>'.
                '</li>'
            );
        }
        if ($page < $max_page-2 && $max_page != 4) {
            print(
                '<li>'.
                '<span>...</span>'.
                '</li>'
            );
        }
        if ($page < $max_page-1) {
            print(
                '<li>'.
                '<a href="'.getHref($url, $max_page, $search, $order).'">'.
                $max_page.
                '</a>'.
                '</li>'
            );
        }
        print(
            '<li>'.
            '<a href="'.getHref($url, $page+1, $search, $order).'">'.
            '≫'.
            '</a>'.
            '</li>'
        );
    }

    echo '</ul>';
}

?>