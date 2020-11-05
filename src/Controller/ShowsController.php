<?php

namespace App\Controller;

use Cake\Controller\ComponentRegistry;
use Cake\Datasource\RepositoryInterface;
use Cake\Event\EventManagerInterface;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\Core\Configure;

/**
 * @property RepositoryInterface|null Shows
 */
class ShowsController extends AppController
{
    /**
     * @return Response|null
     */
    public function index(): ?Response
    {
        try {
            $shows = $this->Shows->find();
        } catch (\Exception $e) {
            return $this->returnJson404();
        }

        foreach ($shows as $show) {
            $slug = $this->getShowSlug($show->url);
            $show->api_url = BASE_URL . "/shows/$slug";
        }

        return $this->returnJson($shows);
    }

    /**
     * @param string $slug
     * @return Response|null
     */
    public function view(string $slug): ?Response
    {
        $url = sprintf('http://www.brilliantbutcancelled.com/show/%s/', $slug);
        try {
            $show = $this->Shows->findByUrl($url)->firstOrFail();
        } catch (\Exception $e) {
            return $this->returnJson404();
        }

        // grab seasons
        $show->seasons = $this->Shows->getSeasons($show->get('sid'), $slug);
        // get total reviews (not episodes)
        $show->total_reviews = $this->Shows->getTotalReviews($show->get('sid'));

        return $this->returnJson($show);
    }

    /**
     * @param string $slug
     * @return Response|null
     */
    public function getSeasonsForShow(string $slug): ?Response
    {
        $sid = $this->Shows->getSidFromSlug($slug);
        $seasons = $this->Shows->getSeasons($sid, $slug);
        return $this->returnJson($seasons);
    }

    /**
     * Note sometimes season numbers can include a non-int (22A)
     * @param string $slug
     * @param string $season_number
     * @return Response|null
     */
    public function getSeason(string $slug, string $season_number): ?Response
    {
        $sid = $this->Shows->getSidFromSlug($slug);
        $episodes_in_season = $this->Shows->getSeasonEpisodeList($sid, $season_number);

        return $this->returnJson($episodes_in_season);
    }

    /**
     * @param string $url
     * @return string
     */
    protected function getShowSlug(string $url): string
    {
        $url = rtrim($url, '/');
        $url_parts = explode('/', $url);
        return $url_parts[count($url_parts) - 1];
    }
}
