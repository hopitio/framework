<?php

namespace Apps\Cores\Controllers;

use Apps\Cores\Views\Setting\StringControl;
use Apps\Cores\Views\Setting\BooleanControl;
use Apps\Cores\Views\Setting\SelectControl;
use Apps\Cores\Views\Setting\TextControl;

class SettingCtrl extends CoresCtrl
{

    function index()
    {
        $this->requireAdmin();
        $this->twoColsLayout->render('Setting/setting.phtml');
    }

    function update()
    {
        $settingDir = BASE_DIR . '/Config/Settings';
        $files = scandir($settingDir);

        //loop file
        foreach ($files as $file)
        {
            $path = $settingDir . '/' . $file;
            if (!strpos($path, '.xml'))
            {
                continue;
            }
            $dom = simplexml_load_file($path, 'SimpleXMLElement', LIBXML_NOCDATA);
            if (!$dom || $dom->attributes()->active != 'true')
            {
                continue;
            }

            //update new value to xml
            foreach ($dom as $field)
            {
                switch ((string) $field->type)
                {
                    case 'string':
                        $control = new StringControl($dom);
                        $field->value = $control->handleValue($this->req->post((string) $field->id));
                        break;
                    case 'boolean':
                        $control = new BooleanControl($dom);
                        $field->value = $control->handleValue($this->req->post((string) $field->id));
                        break;
                    case 'select':
                        $control = new SelectControl($dom);
                        $field->value = $control->handleValue($this->req->post((string) $field->id));
                        break;
                    case 'text':
                        $control = new TextControl($dom);
                        $field->value = $control->handleValue($this->req->post((string) $field->id));
                        break;
                }
            }

            //save to file
            file_put_contents($path, $this->formatXml($dom));
        }

        $this->resp->redirect(url('/admin/setting'));
    }

    protected function formatXml(\SimpleXMLElement $xml)
    {
        $domxml = new \DOMDocument('1.0');
        $domxml->preserveWhiteSpace = false;
        $domxml->formatOutput = true;
        /* @var $xml SimpleXMLElement */
        $domxml->loadXML($xml->asXML());
        return $domxml->saveXML();
    }

}
