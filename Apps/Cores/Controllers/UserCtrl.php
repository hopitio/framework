<?php

namespace Apps\Cores\Controllers;

use Apps\Cores\Models\UserMapper;
use Apps\Cores\Models\DepartmentMapper;

class UserCtrl extends CoresCtrl
{

    protected $userMapper;
    protected $depMapper;

    function init()
    {
        parent::init();
        $this->userMapper = UserMapper::makeInstance();
        $this->depMapper = DepartmentMapper::makeInstance();
    }

    function index()
    {
        $this->requireAdmin();
        $this->twoColsLayout->render('User/user.phtml');
    }

    function group()
    {
        $this->requireAdmin();
        $this->twoColsLayout->render('User/group.phtml');
    }

    function importUser()
    {
        $this->requireAdmin();
        $viewData = array();

        if (!empty($_FILES))
        {
            $result = $this->importUserMoveFile($_FILES['upload']);
            if ($result['status'])
            {
                //start execute
                $this->resp->redirect(url('/admin/user/import?file=' . $result['path'] . '&progress=0'));
                return;
            }
            else
            {
                $viewData['error'] = $result['error'];
            }
        }
        elseif ($this->req->get('file'))
        {
            $path = $this->req->get('file');
            //đã upload, xử lý file
            $progress = $this->req->get('progress');
            if ($progress)
            {
                $result = $this->importUserProgress($path, $progress);
            }
            else
            {
                $result = $this->importUserProgress($this->req->get('file'));
            }

            if ($result['status'])
            {
//                if ($result['progress'] >= $result['total'])
//                {
//                    $this->resp->redirect(url('/admin/user'));
//                    return;
//                }
//                else
//                {
//                    $this->resp->redirect(url('/admin/user/import?file=' . $path . '&progress=' . $result['progress']));
//                    return;
//                }
            }
            else
            {
                $viewData['error'] = $result['error'];
            }
        }

        $this->twoColsLayout->render('User/importUser.phtml', $viewData);
    }

    protected function importUserMoveFile($file)
    {
        //check file valid
        if ($file['type'] != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
        {
            return array(
                'status' => false,
                'error'  => 'Phải là file excel'
            );
        }

        //upload dir
        $destDir = date_create()->format('Y/m/d') . '/';
        $destFile = uniqid() . '.xlsx';
        $fullPath = BASE_DIR . '/Docroot/upload/' . $destDir . $destFile;
        if (!file_exists(dirname($fullPath)) && !mkdir(dirname($fullPath), 0777, true))
        {
            return array(
                'status' => false,
                'error'  => 'Không tạo được thư mục ' . $destDir
            );
        }

        //move file
        if (!move_uploaded_file($file['tmp_name'], $fullPath))
        {
            return array(
                'status' => false,
                'error'  => 'Không chuyển được file upload'
            );
        }

        $this->importUserProgress($destDir . $destFile);
    }

    protected function importUserProgress($path)
    {
        $fullPath = BASE_DIR . '/Docroot/upload/' . $path;

        if (!file_exists($fullPath))
        {
            return array(
                'status' => false,
                'error'  => 'File bị mất trong quá trình xử lý ' . $path
            );
        }

        //php excel
        require BASE_DIR . '/Libs/PHPExcel/PHPExcel.php';
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($fullPath);
        $objWorksheet = $objPHPExcel->getActiveSheet();

        $rows = $objWorksheet->toArray();
        $insertData = array();
        for ($i = 2; $i < count($rows); $i++)
        {
            $insertDataRow = array();
            for ($j = 1; $j < count($rows[1]); $j++)
            {
                $field = $rows[1][$j];
                $insertDataRow[$field] = $rows[$i][$j];
            }
            $insertData[] = $insertDataRow;
        }

        //insert
        for ($i = $progress + 1; $i < $progress + 1 + $inserLimit; $i++)
        {
            if (!isset($insertData[$i]))
            {
                continue;
            }
            $row = $insertData[$i];
            $depFk = $row['depFk'];
            if (!is_numeric($depFk))
            {
                preg_match("/\[([0-9]+)\]/", $depFk, $matches);
                $depFk = $matches[1];
            }
            $depFk = (int) $depFk;
            $groups = explode(',', str_replace("\n", ",", $row['groups']));
            $permissions = explode(',', str_replace("\n", ",", $row['permissions']));
            foreach ($groups as &$group)
            {
                $group = trim($group, ' ');
            }
            foreach ($permissions as &$pem)
            {
                $pem = trim($pem, ' ');
            }

            $this->userMapper->updateUser(0, array(
                'fullName'    => $row['fullName*'],
                'depFk'       => $depFk,
                'account'     => $row['account*'],
                'jobTitle'    => $row['jobTitle'],
                'groups'      => $groups,
                'permissions' => $permissions,
                'newPass'     => '123456',
                'stt'         => 1
            ));
        }

        $newProgress = min(array(count($insertData), $i));
        return array(
            'status'   => true,
            'progress' => $newProgress,
            'total'    => count($insertData)
        );
    }

}
