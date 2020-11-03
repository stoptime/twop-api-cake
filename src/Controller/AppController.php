<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use \Cake\Http\Response;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * @param mixed $var
     * @param bool $compact
     * @return Response|null
     */
    protected function returnJson($var, bool $compact = true): ?Response
    {
        if ($compact) {
            compact($var);
        }
        $var = json_encode($var,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $this->viewBuilder()->setOption('serialize', ['var']);
        return $this->response->withType('application/json; charset=utf-8')->withStringBody($var);
    }

    /**
     * @return Response|null
     */
    protected function returnJson404(): ?Response
    {
        return $this->response->withType('application/json; charset=utf-8')
            ->withStringBody('Show not found.')
            ->withStatus(404, 'Not Found');
    }
}
