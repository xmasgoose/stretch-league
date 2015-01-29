<?php

class StretchLeague {

    public function __construct() {
        $this->db = new Database();
    }

    public function getFixtureData($where = '%') {
        $dates = $this->db->select("SELECT DISTINCT fixture_date from fixtures WHERE home_team_name LIKE '$where' OR away_team_name LIKE '$where';");
        $fixtures = [];
        for ($i = 0; $i < count($dates); $i++) {
            array_push($fixtures, $this->db->select(
                            "SELECT home_team_name, away_team_name, fixture_time, referee_name, DATE_FORMAT(fixture_date, '%e %M %Y') as fixture_date"
                            . " FROM fixtures WHERE fixture_date = " . $dates[$i]['fixture_date'] . " AND (home_team_name LIKE '$where' OR away_team_name LIKE '$where');"
            ));
        }
        return $fixtures;
    }

    public function getResultData($where = '%') {
        $dates = $this->db->select("SELECT DISTINCT fixture_date from results WHERE fixture_date < curdate() + 0 AND (home_team_name LIKE '$where' OR away_team_name LIKE '$where') ORDER BY fixture_date DESC;");
        $results = [];
        for ($i = 0; $i < count($dates); $i++) {
            array_push($results, $this->db->select(
                            "SELECT home_team_name, away_team_name, fixture_time, DATE_FORMAT(fixture_date, '%e %M %Y') as fixture_date, home_goals_for, away_goals_for"
                            . " FROM results WHERE fixture_date = " . $dates[$i]['fixture_date'] . " AND (home_team_name LIKE '$where' OR away_team_name LIKE '$where');"
            ));
        }
        return $results;
    }

    public function getPlayerData($where = '%') {
        return $this->db->select("SELECT * FROM goalscorers WHERE team_name LIKE '$where';");
    }

    public function getTableData() {
        return $this->db->select("SELECT * FROM fulltable;");
    }

}