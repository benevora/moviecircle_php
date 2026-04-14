<?php

  class Movie {
    public $id;
    public $title;
    public $description;
    public $image;
    public $trailer;
    public $category;
    public $length;
    public $users_id;

    public $rating;

    public function imageGenerateName($extension = "jpg") {
      return bin2hex(random_bytes(60)) . "." . $extension;
    }

    public function getTrailerEmbed() {

      // Normal YouTube link
      if(strpos($this->trailer, "youtube.com") !== false) {
        parse_str(parse_url($this->trailer, PHP_URL_QUERY), $params);
        if(isset($params['v'])) {
          return "https://www.youtube.com/embed/" . $params['v'];
        }
      }

      // Short youtu.be link
      if(strpos($this->trailer, "youtu.be") !== false) {
        $videoId = basename(parse_url($this->trailer, PHP_URL_PATH));
        return "https://www.youtube.com/embed/" . $videoId;
      }

      return $this->trailer;
    }

    public function getTitle() {
      return $this->title;
    }

    public function getId() {
      return $this->id;
    }

  }


  interface MovieDAOInterface {
    public function buildMovie($data);

    public function findAll();

    public function getLatestMovies();

    public function getMoviesByCategory($category);

    public function getMoviesByUserId($id);

    public function findById($id);

    public function findByTitle($title);

    public function create(Movie $movie);

    public function update(Movie $movie);

    public function destroy($id);
  }