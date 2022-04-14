<?php
class HelperBackend
{
    public static function createSideBarItem($array)
    {
        $xhtml = '';
        foreach ($array as $key => $value) {
            $xhtml .= sprintf(
        '<li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas %s"></i>
                <p>
                    %s
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="%s" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>%s</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="%s" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>%s</p>
                    </a>
                </li>
            </ul>
        </li>',$value['icon'],$key,$value['list']['link'],$value['list']['name'],$value['form']['link'],$value['form']['name'],
            );
        }
        return $xhtml;
    }
    public static function createNavItem($link, $icon, $name)
    {
        $xhtml = sprintf('
        <li class="nav-item">
            <a href="%s" class="nav-link active">
                <i class="nav-icon fas fa-%s"></i>
                <p>%s</p>
            </a>
        </li>', $link, $icon, $name);
        return $xhtml;
    }

    public static function createButton($type, $class, $name)
    {
        $xhtml = '<button type="' . $type . '" class="' . $class . '">' . $name . '</button>';
        return $xhtml;
    }

    public static function showListItem($items)
    {
        $xhtml = '';

        return $xhtml;
    }

    // Create Selectbox
    public static function cmsSelectboxNumeric($name, $arrValue, $keySelect = 'default', $style = null)
    {
        $xhtml = '<select style="' . $style . '" name="' . $name . '" class="form-control custom-select" >';
        foreach ($arrValue as $key => $value) {
            if ($key == $keySelect && is_numeric($keySelect)) {
                $xhtml .= '<option selected="selected" value = "' . $key . '">' . $value . '</option>';
            } else {
                $xhtml .= '<option value = "' . $key . '">' . $value . '</option>';
            }
        }
        $xhtml .= '</select>';
        return $xhtml;
    }

    public static function cmsSelectbox($name, $arrValue, $keySelect = 'default', $dataURL = '', $style = null)
    {
        $xhtml = '<select style="' . $style . '" name="' . $name . '" class="ajax-attr-slb form-control custom-select" data-url=' . $dataURL . ' >';
        foreach ($arrValue as $key => $value) {
            if ($key == $keySelect) {

                $xhtml .= '<option selected="selected" value = "' . $key . '">' . $value . '</option>';
            } else {
                $xhtml .= '<option value = "' . $key . '">' . $value . '</option>';
            }
        }
        $xhtml .= '</select>';
        return $xhtml;
    }

    public static function createSelectBox($class, $value, $name)
    {
        $xhtml = '<select class="form-control custom-select">
                    <option>Bulk Action</option>
                    <option>Delete</option>
                    <option>Active</option>
                    <option>Inactive</option>
                </select>';
        return $xhtml;
    }

    public static function createStatusFilter($module, $controller, $itemStatusCount, $currentStatus, $searchValue)
    {
        $xhtml = '';
        foreach ($itemStatusCount as $key => $value) {
            $link = URL::createLink($module, $controller, 'index', [
                'currentStatus' => $key,
                'search_value'  => $searchValue,
            ]);
            if (empty($currentStatus)) {
                $currentStatus = 'all';
            }
            $classActive = ($key == $currentStatus)  ? 'btn btn-info' : 'btn btn-secondary';
            $xhtml .= '<a href="' . $link . '" class="' . $classActive . '">' . ucfirst($key) . ' <span class="badge badge-pill badge-light"> ' . $value . '</span></a> ';
        }

        return $xhtml;
    }

    public static function createCRUDBtn($link, $class, $name, $onClick = null)
    {
        $xhtml = '<a href="' . $link . '" onclick="' . $onClick . '" class="' . $class . '">' . $name . '</a> ';
        return $xhtml;
    }


    public static function createLabel($class, $labelName, $object = null)
    {
        $xhtml = '<div class="form-group"><label for="' . strtolower($labelName) . '" class="' . $class . '">' . $labelName . '</label>';
        if (isset($object)) {
            $xhtml .= $object;
        }
        $xhtml .= '</div>';
        return $xhtml;
    }

    public static function createInput($type, $name, $value = null, $disabled = false, $dataURL = null, $required = null)
    {
        if ($disabled == true) {
            $xhtml = '<input class="form-control ajax-attr-input" data-url="' . $dataURL . '" type="' . $type . '" name="' . $name . '" value="' . $value . '" placeholder="' . $value . '" disabled>';
        } else {
            $xhtml = '<input class="form-control ajax-attr-input"  data-url="' . $dataURL . '" type="' . $type . '" name="' . $name . '" value="' . $value . '" placeholder="' . $value . '">';
        }
        return $xhtml;
    }

    public static function createBtn($class, $type, $name, $value = null)
    {
        $btnName = ucfirst($name);
        $xhtml = '<button class="' . $class . '" type="' . $type . '" name="' . $name . '" value="' . $value . '">' . $btnName . '</button> ';
        return $xhtml;
    }

    public static function createBtnAjaxAttr($link,$class, $icon)
    {
        $xhtml = sprintf('<button type="button" data-url="%s" class="%s ajax-attr-btn rounded-circle btn-sm "><i class="fas %s"></i></button>',$link,$class,$icon);
        return $xhtml;
    }

    public static function highlight($search, $value)
    {
        if ($search != '') {
            return preg_replace('/' . preg_quote($search, '/') . '/ui', '<mark style="background-color:yellow">$0</mark>', $value);
        }

        return $value;
    }

    public static function createTextArea($value, $name, $cols, $rows)
    {

        $xhtml = sprintf('<textarea class="form-control" cols="%s" rows="%s" id="%s" name="%s" value="%s">%s</textarea>', $cols, $rows, $name, $name, $value, $value);
        return $xhtml;
    }

    public static function notification($notify, $type = true)
    {
        $xhtml = '';
        $message = @Session::get('message');
        $class = ($type) ? 'success' : 'danger';
        if ($message) {
            $xhtml = '
                <div class="alert alert-' . $class . ' alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    ' . $notify . '      
                </div>';
            Session::delete('message');
        }
        return $xhtml;
    }

    public static function random_string($name_length = 8)
    {
        $alpha_numeric = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        return substr(str_shuffle(str_repeat($alpha_numeric, $name_length)), 0, $name_length);
    }

    public static function column_in_list($element)
    {
        $xhtml = '<td> ' . $element . ' </td>';
        return $xhtml;
    }

    public static function itemHistory($icon, $name)
    {
        $xhtml = '<p class="mb-0"><i class="far fa-' . $icon . '"></i> ' . $name . '</p> ';
        return $xhtml;
    }

    public static function actionButton($link, $class, $icon, $deleteBtn = false)
    {
        $delete = ($deleteBtn) ? 'btn-delete' : '';
        $xhtml  = sprintf('<a href="%s" class="btn %s btn-sm rounded-circle %s" >
                    <i class="fas %s"></i>
                </a> ', $link, $class, $delete, $icon);
        return $xhtml;
    }
}
