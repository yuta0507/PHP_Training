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
ini_set('display_errors', "On");

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
 * @return string
 * */
function outputPaging($url, $page, $max_page, $search, $order) {
    echo '<ul class="paging">';
    if ($page > 1) {
        echo '<li>';
        echo '<a href="' .getHref($url, $page-1, $search, $order) .'">';
        echo '≪';
        echo '</a>';
        echo '</li>';
        if ($page > 2) {
            echo '<li>';
            echo '<a href="' .getHref($url, 1, $search, $order) .'">';
            echo 1;
            echo '</a>';
            echo '</li>';
        }
        if ($page > 3 && $max_page != 4) {
            echo '<li>';
            echo '<span>...</span>';
            echo '</li>';
        }
        if ($page == $max_page && $max_page != 3 && $max_page != 2) {
            echo '<li>';
            echo '<a href="' .getHref($url, $max_page-2, $search, $order) .'">';
            echo $max_page-2;
            echo '</a>';
            echo '</li>';
        }
        echo '<li>';
        echo '<a href="' .getHref($url, $page-1, $search, $order) .'">';
        echo $page-1;
        echo '</a>';
        echo '</li>';
    }
    echo '<li>';
    echo '<a href="' .getHref($url, $page, $search, $order) .'" class="current-page">';
    echo $page;
    echo '</a>';
    echo '</li>';
    if ($page < $max_page) {
        echo '<li>';
        echo '<a href="' .getHref($url, $page+1, $search, $order) .'">';
        echo $page+1;
        echo '</a>';
        echo '</li>';
        if ($page == 1 && $max_page != 3 && $max_page != 2) {
            echo '<li>';
            echo '<a href="' .getHref($url, 3, $search, $order) .'">';
            echo 3;
            echo '</a>';
            echo '</li>';
        }
        if ($page < $max_page-2 && $max_page != 4) {
            echo '<li>';
            echo '<span>...</span>';
            echo '</li>';
        }
        if ($page < $max_page-1) {
            echo '<li>';
            echo '<a href="' .getHref($url, $max_page, $search, $order) .'">';
            echo $max_page;
            echo '</a>';
            echo '</li>';
        }
        echo '<li>';
        echo '<a href="' .getHref($url, $page+1, $search, $order) .'">';
        echo '≫';
        echo '</a>';
        echo '</li>';
    }

    echo '</ul>';
}

?>