<?php

namespace App\Console\Commands;

class ApiDocGenerator extends \Despark\Apidoc\Commands\ApiDocGenerator
{
    protected function setPaths($method)
    {
        $docArray = $this->methodCommentToArray($method);

        if (!count($docArray)) {
            return;
        }

        $methodType = strtolower(str_replace(['|HEAD', '|PATCH'], '', $method['method']));

        $path = [
            'tags'        => [
                str_replace('CE\Http\Controllers', '', array_get($method, 'controllerNameSpace', '')),
            ],
            'summary'     => array_get($docArray, 'desc'),
            'description' => array_get($method, 'controllerClassName', ''),
            'operationId' => '',
            'consumes'    => [
                'application/json',
                'application/xml',
            ],
            'produces'    => [
                'application/xml',
                'application/json',
            ],
            'parameters'  => $this->setParams($docArray, $method),
            'responses'   => $this->setResponses($docArray),
        ];


        return $this->swagger['paths'][str_replace('api/v1', '', array_get($method, 'uri', ''))][$methodType] = $path;
    }
}
