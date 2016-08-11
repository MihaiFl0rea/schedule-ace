<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 01.06.2016
 * Time: 15:11
 */

    require_once BASE_URL . "/models/Model_Groups.php";

    $model = new Model_Groups();

    if($_SESSION['user_id'] != 'admin'){

        header('Location: ' . ADMIN_AUTH_PATH);

    } else {

        $template = $twig->loadTemplate('groups.html');

        $action = isset($_GET['case']) ? $_GET['case'] : '';

        $statistics = $model->create_counters_array();

        switch($action) {

            case 'groups':

                $groups = $model->get_groups();

                echo $template->render(array(
                    'url' => ASSETS_URL,
                    'slash' => DIRECTORY_SEPARATOR,
                    'year' => date('Y'),
                    'name' => 'Specializari | ACE',
                    'statistics' => $statistics,
                    'case' => 'groups',
                    'groups' => $groups
                ));

                break;

            case 'year':

                $years = $model->get_groups_by_year($_GET['id']);
                $group_name = $model->get_group_name($_GET['id']);

                echo $template->render(array(
                    'url' => ASSETS_URL,
                    'slash' => DIRECTORY_SEPARATOR,
                    'year' => date('Y'),
                    'name' => 'Specializari Ani | ACE',
                    'statistics' => $statistics,
                    'case' => 'year',
                    'group_name' => $group_name,
                    'groups_by_year' => $years
                ));

                break;

            case 'year-subgroup':

                $subgroups = $model->get_subgroups_by_year($_GET['id']);
                $group_details = $model->get_group_details($_GET['id']);

                echo $template->render(array(
                    'url' => ASSETS_URL,
                    'slash' => DIRECTORY_SEPARATOR,
                    'year' => date('Y'),
                    'name' => 'Specializari Subgrupe | ACE',
                    'statistics' => $statistics,
                    'case' => 'year-subgroup',
                    'group_details' => $group_details,
                    'subgroups' => $subgroups
                ));

                break;

            default:

                echo $template->render(array(
                    'url' => ASSETS_URL,
                    'slash' => DIRECTORY_SEPARATOR,
                    'year' => date('Y'),
                    'name' => 'Specializari | ACE',
                    'statistics' => $statistics,
                ));

        }

    }

?>