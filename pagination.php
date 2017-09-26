<?php
/**
 * @param $currentPage int current page
 * @param $totalSum int your total sum
 * @param $limit int your limitt
 * @param string $actions how would you like to do action
 * @return array return result with information: status, errMsg, pagination
 */
function pagination($currentPage, $totalSum, $limit, $actions = 'default', $appearance = 'pagination')
{
    $totalPage = (int)ceil($totalSum / $limit);
    $container = '<div class="'.$appearance.'">';
    $prosessStatus = array();
    $showSumInfo = false;
    $showSearchBtn = false;
    $urlString = function ($currentPage, $next) {
        $replaceString = "page={$currentPage}";
        $address = $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
        $address = str_replace($replaceString, "page={$next}", $address);
        return $address;
    };


    if (empty((int)$currentPage)) {
        $prosessStatus['status'] = false;
        $prosessStatus['errMsg'] = "You did not specific current page, we could not help you build this pagination!";
    }
    if (empty((int)$totalSum)) {
        $prosessStatus['status'] = false;
        $prosessStatus['errMsg'] = "You did not specific total sum, we could not understand how many items do you have!";
    }
    if (empty((int)$limit)) {
        $prosessStatus['status'] = false;
        $prosessStatus['errMsg'] = "You did not specific limit num, we could not help you calculate how many pages do you need!";
    }

    if ($totalPage < 0) {
        $prosessStatus['status'] = false;
        $prosessStatus['errMsg'] = "You did not specific limit num, we could not help you calculate how many pages do you need!";
    }

    //preview button
    if ($currentPage !== 1) {
        $string = $urlString($currentPage, $currentPage - 1);
        $first = $urlString($currentPage, 1);
        $container .= $actions == 'url' ? '<a href="' . $string . '"><span>←</span></a><a href="' . $first . '"><span>First</span><a>' : '<span id="preview" data-page="' . --$currentPage . '">←</span><span id="first" data-page="1">First</span>';
    }


    try {
        $container .= '<ul style="display: inline-block;list-style: none;margin: 0;padding: 0;">';

        if ($currentPage <= 4) {
            for ($i = 1; $i < 10; $i++) {
                if ($i == $currentPage) {
                    $container .= '<li class="pageNum p-on">' . $i . '</li>';
                } else {
                    $string = $urlString($currentPage, $currentPage - 1);
                    $container .= $actions == 'url' ? '<a href="' . $string . '"><li class="pageNum">' . $i . '</li></a>' : '<li class="pageNum jump-page" data-page="' . $i . '">' . $i . '</li>';
                }
            }
        }
        if ($currentPage > $totalPage - 4 && $currentPage <= $totalPage) {
            $first = $totalPage - 9;
            for ($i = 0; $i < 9; $i++) {
//                $container .= '<li class="pageNum">'.++$first.'</li>';
                $container .= $actions == 'url' ? '<a href="' . ++$first . '"><li class="pageNum">' . ++$first . '</li></a>' : '<li class="pageNum jump-page" data-page="' . ++$first . '">' . ++$first . '</li>';
            }
        }
        if ($currentPage >= 5 && $currentPage <= $totalPage - 4) {
            $preview = $currentPage - 5;
            $next = $currentPage;
            for ($i = 0; $i < 4; $i++) {
//                $container .= '<li class="pageNum">'.++$preview.'</li>';
                $container .= $actions == 'url' ? '<a href="' . ++$preview . '"><li class="pageNum">' . ++$preview . '</li></a>' : '<li class="pageNum jump-page" data-page="' . ++$preview . '">' . ++$preview . '</li>';
            }
            $container .= '<li class="pageNum p-on">' . $currentPage . '</li>';
            for ($i = 0; $i < 4; $i++) {
//                $container .= '<li class="pageNum">'.++$next.'</li>';
                $container .= $actions == 'url' ? '<a href="' . ++$next . '"><li class="pageNum">' . ++$next . '</li></a>' : '<li class="pageNum jump-page" data-page="' . ++$next . '">' . ++$next . '</li>';
            }
        }


        $container .= '</ul>';

        if ($currentPage !== $totalPage) {
            $container .= '<span>End</span><span>→</span>';
        }

        if ($showSumInfo) {
            $container .= '<label class="p-info">Total Records: ' . $totalSum . '</label>';
        }

        if ($showSearchBtn) {
            $container .= '<a class="p-go">Go</a><input class="p-input" type="text"/>';
        }
        $prosessStatus['status'] = true;
        $prosessStatus['pagination'] = $container;
    } catch (Exception $e) {
        $prosessStatus['status'] = false;
        $prosessStatus['errMsg'] = "Oops, something went wrong with me, please try again";
    }

    return $prosessStatus;

}
