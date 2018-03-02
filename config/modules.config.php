<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

/**
 * List of enabled modules for this application.
 *
 * This should be an array of module namespaces used in the application.
 */
return [
    /* Core Framework modules */
    'Zend\\Router',
    'Zend\\Form',
    'Zend\\Validator',
    'Zend\\InputFilter',
    'Zend\\I18n',
    'Zend\\Mvc\\I18n',
    'Zend\\Mvc\\Plugin\\FlashMessenger',
    'Zend\\Paginator',

    /* API Framework */
    'ZF\\Apigility',
    'ZF\\Apigility\\Documentation',
    'ZF\\ApiProblem',
    'ZF\\Configuration',
    'ZF\\OAuth2',
    'ZF\\MvcAuth',
    'ZF\\Hal',
    'ZF\\ContentNegotiation',
    'ZF\\ContentValidation',
    'ZF\\Rest',
    'ZF\\Rpc',
    'ZF\\Versioning',

    /* Thirdparty modules */
    'DoctrineModule',
    'DoctrineORMModule',
    'ZendTwig',
    'ZfcRbac',
    
    'Application',
    
    'Api\\Program',
    'Api\\Project',
    
    'Axmit\\Dao',
    'Axmit\\Util',
    'Axmit\\UserCore',
    
    'Zsa\\Pip',
    'Zsa\\Program',
    'Zsa\\Project',
    'Zsa\\Util'
];
