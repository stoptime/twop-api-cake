<?php

namespace App\Controller;

use App\Model\Table\ShowsTable;
use Cake\Controller\ComponentRegistry;
use Cake\Event\EventManagerInterface;
use Cake\Http\Response;
use Cake\Http\ServerRequest;

class ShowsController extends AppController
{

    public function index()
    {
        $shows = $this->Shows->find();
        $this->set(compact('shows'));
    }

    public function view(string $slug)
    {
        $url = sprintf('http://www.brilliantbutcancelled.com/show/%s/', $slug);
        try {
            $show = $this->Shows->findByUrl($url)->firstOrFail();
        } catch (\Exception $e) {
            return $this->response->withType('application/json; charset=utf-8')
                ->withStringBody('Show not found.')
                ->withStatus(404, 'Not Found');
        }

        // grab total seasons
        $show->total_seasons = $this->Shows->getTotalSeasons($show->get('sid'));
        // get total reviews (not episodes)
        $show->total_reviews = $this->Shows->getTotalReviews($show->get('sid'));
        // output
        $show = json_encode($show,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $this->set(compact('show'));
        $this->viewBuilder()->setOption('serialize', true);
        // comment below out to use normal cake view
        return $this->response->withType('application/json; charset=utf-8')->withStringBody($show);
    }
}
