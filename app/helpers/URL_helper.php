<?php
/**
 * @param $page<p>
 * page to visit it
 * </p>
 */
function redirect($page)
{
    header('location: '.URLROOT."/".$page);

}