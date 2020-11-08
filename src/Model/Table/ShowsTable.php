<?php

namespace App\Model\Table;

use Cake\Datasource\ConnectionInterface;
use Cake\ORM\Query;
use Cake\Datasource\ConnectionManager;

/**
 * Class ShowsTable
 * @package App\Model\Table
 */
class ShowsTable extends \Cake\ORM\Table
{
    private ConnectionInterface $connection;
    private string $urlStart;

    public function __construct(array $config = ['connection'])
    {
        parent::__construct($config);
        $this->connection = ConnectionManager::get('default');
        $this->urlStart = 'http://www.brilliantbutcancelled.com/show';
    }

    public function initialize(array $config): void
    {
        $this->setPrimaryKey('sid');
        $this->hasMany('Episodes');
    }

    /**
     * @param int $sid
     * @param string $slug
     * @return array
     */
    public function getSeasons(int $sid, string $slug): array
    {
        $return = [
            'count' => 0,
            'seasons' => []
        ];
        $sql = "SELECT season FROM episodes WHERE sid = ? AND season IS NOT NULL GROUP BY season";
        $result = $this->connection->execute($sql, [$sid]);

        $return['count'] = $result->count();
        $seasons_result = $result->fetchAll('assoc');

        $seasons = [];

        foreach ($seasons_result as $key => $season_number) {
            $seasons[$season_number['season']] = BASE_URL . "/shows/$slug/seasons/" . $season_number['season'];
        }
        ksort($seasons);
        $return['seasons'] = $seasons;
        return $return;
    }

    /**
     * @param int $sid
     * @return int
     */
    public function getTotalReviews(int $sid): int
    {
        $sql = "SELECT sid FROM episodes WHERE sid = ?";
        $result = $this->connection->execute($sql, [$sid]);
        return $result->count();
    }

    /**
     * Returns the show id (sid) from a url slug (30-rock)
     * @param string $slug
     * @return int
     */
    public function getSidFromSlug(string $slug): ?int
    {
        $url = $this->urlStart . "/$slug/";
        $sql = "SELECT sid FROM shows WHERE url = ?";
        $result = $this->connection->execute($sql, [$url])->fetch();
        if ($result) {
            return intval($result[0]);
        }
        else {
            return false;
        }
    }

    /**
     * Given a show id (sid) and season number,
     * Returns a list of episodes w/ some meta info
     * Note: season may contain a letter
     * @param int $sid
     * @param string $season
     * @return array
     */
    public function getSeasonEpisodeList(int $sid, string $season): array
    {
        $sql = "SELECT title, season, episode, air_date, grade, author, episode_meta, url, url_slug
            FROM episodes WHERE sid = ? AND season = ? ORDER BY eid DESC";
        $results = $this->connection->execute($sql, [$sid, $season])->fetchAll('assoc');
        if ($results) {
            $return = [];
            $return['count'] = count($results);
            foreach ($results as &$season) {
                $episodes_slug = '/episodes/';
                $req_url = rtrim($_SERVER['REQUEST_URI'], "/");
                if (strpos($req_url, 'episodes')) {
                    $episodes_slug = '/';
                }
                $season['api_url'] = BASE_URL . $req_url . $episodes_slug . $season['episode'];
            }
            $return['episodes'] = $results;
            return $return;
        }
        return [];
    }

    public function getEpisode(string $show_slug, string $season, string $episode): array
    {
        $show_id = $this->getSidFromSlug($show_slug);
        $sql = "SELECT * FROM episodes WHERE sid = ? and season = ? and episode = ? LIMIT 1";
        $result = $this->connection->execute($sql, [$show_id, $season, $episode])->fetchAll('assoc');
        if ($result) {
            return $result;
        }
        return [];
    }

    public function getEpisodeFromSlug(string $slug): array
    {
        $sql = "SELECT * FROM episodes WHERE url_slug = ? LIMIT 1";
        $result = $this->connection->execute($sql, [$slug])->fetchAll('assoc');
        if ($result) {
            return $result;
        }
        return [];
    }
}


















