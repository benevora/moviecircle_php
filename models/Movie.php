<?php

  /* ======================================
     Movie model
  ====================================== */
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

    /* ======================================
       // Generate image name
    ====================================== */
    public function imageGenerateName($extension = "jpg") {
      return bin2hex(random_bytes(60)) . "." . $extension;
    }


    /* ======================================
       Get trailer embed URL
    ====================================== */
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
    

    /* ======================================
       Get title
    ====================================== */
    public function getTitle() {
      return $this->title;
    }


    /* ======================================
       Get ID
    ====================================== */
    public function getId() {
      return $this->id;
    }

  }


  /* ======================================
     Movie DAO interface
  ====================================== */
  interface MovieDAOInterface {

    // Build movie
    public function buildMovie($data);

    // Find all movies
    public function findAll();

    // Get latest movies
    public function getLatestMovies();

    // Get movies by category
    public function getMoviesByCategory($category);

    // Get movies by user
    public function getMoviesByUserId($id);

    // Find movie by ID
    public function findById($id);

    // Find movie by title
    public function findByTitle($title);

    // Create movie
    public function create(Movie $movie);

    // Update movie
    public function update(Movie $movie);

    // Delete movie
    public function destroy($id);
  }