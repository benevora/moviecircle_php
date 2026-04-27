<?php

  require_once("models/Movie.php");
  require_once("models/Message.php");
  require_once("dao/ReviewDAO.php");

  class MovieDAO implements MovieDAOInterface {

    private $conn;
    private $url;
    private $message;


    /* ======================================
      Setup database, base URL, and message system
    ====================================== */
    public function __construct(PDO $conn, $url)
    {
      $this->conn = $conn;
      $this->url = $url;
      $this->message = new Message($url);
    }


    /* ======================================
      Build Movie object from database array
    ====================================== */
    public function buildMovie($data){

      $movie = new Movie();

      // Map DB fields to Movie object
      $movie->id = $data["id"];
      $movie->title = $data["title"];
      $movie->description = $data["description"];
      $movie->image = $data["image"];
      $movie->trailer = $data["trailer"];
      $movie->category = $data["category"];
      $movie->length = $data["length"];
      $movie->users_id = $data["users_id"];

      // Attach average rating from reviews
      $reviewDao = new ReviewDAO($this->conn, $this->url);

      $rating = $reviewDao->getRatings($movie->id);

      $movie->rating = $rating;

      return $movie;
    }


    /* ======================================
      (Not implemented yet)
    ====================================== */
    public function findAll(){

    }


    /* ======================================
      Get latest movies (newest first)
    ====================================== */
    public function getLatestMovies(){
      
      $movies = [];

      $stmt = $this->conn->query("SELECT * FROM movies ORDER BY id DESC");

      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        
        $moviesArray = $stmt->fetchAll();

        foreach($moviesArray as $movie) {
          $movies[] = $this->buildMovie($movie);
        }
      }

      return $movies;
    }


    /* ======================================
      Get movies by category
    ====================================== */
    public function getMoviesByCategory($category){
      $movies = [];

      $stmt = $this->conn->prepare("SELECT * FROM movies
                                    WHERE category = :category
                                    ORDER BY id DESC");

      $stmt->bindParam(":category", $category);

      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        
        $moviesArray = $stmt->fetchAll();

        foreach($moviesArray as $movie) {
          $movies[] = $this->buildMovie($movie);
        }
      }

      return $movies;
    }


    /* ======================================
      Get movies created by a user
    ====================================== */
    public function getMoviesByUserId($id){
      $movies = [];

      $stmt = $this->conn->prepare("SELECT * FROM movies
                                    WHERE users_id = :users_id");

      $stmt->bindParam(":users_id", $id);

      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        
        $moviesArray = $stmt->fetchAll();

        foreach($moviesArray as $movie) {
          $movies[] = $this->buildMovie($movie);
        }
      }

      return $movies;
    }

    /* ======================================
      Find a movie by ID
    ====================================== */
    public function findById($id){
      $movie = [];

      $stmt = $this->conn->prepare("SELECT * FROM movies
                                    WHERE id = :id");

      $stmt->bindParam(":id", $id);

      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        
        $movieData = $stmt->fetch();

        $movie = $this->buildMovie($movieData);

        return $movie;

      } else {
        return false;
      }

    }


    /* ======================================
      Search movies by title
    ====================================== */
    public function findByTitle($title){
      $movies = [];

      $stmt = $this->conn->prepare("SELECT * FROM movies
                                    WHERE title LIKE :title");

      $stmt->bindValue(":title", '%'.$title.'%');

      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        
        $moviesArray = $stmt->fetchAll();

        foreach($moviesArray as $movie) {
          $movies[] = $this->buildMovie($movie);
        }
      }

      return $movies;
    }

    
    /* ======================================
      Create a new movie
    ====================================== */
    public function create(Movie $movie){
      $stmt = $this->conn->prepare("INSERT INTO movies(
        title, description, image, trailer, category, length, users_id
        ) VALUES (
          :title, :description, :image, :trailer, :category, :length, :users_id
        ) 
      ");

      $stmt->bindParam(":title", $movie->title);
      $stmt->bindParam(":description", $movie->description);
      $stmt->bindParam(":image", $movie->image);
      $stmt->bindParam(":trailer", $movie->trailer);
      $stmt->bindParam(":category", $movie->category);
      $stmt->bindParam(":length", $movie->length);
      $stmt->bindParam(":users_id", $movie->users_id);

      $stmt->execute();

      // Message of success by adding film
      $this->message->setMessage(
        "Movie added successfully.",
        "success",
        "index.php"
      );
    }

    /* ======================================
      Update movie
    ====================================== */
    public function update(Movie $movie) {

      $stmt = $this->conn->prepare("UPDATE movies SET
        title = :title,
        description = :description,
        image = :image,
        category = :category,
        trailer = :trailer,
        length = :length
        WHERE id = :id
      ");

      $stmt->bindParam(":title", $movie->title);
      $stmt->bindParam(":description", $movie->description);
      $stmt->bindParam(":image", $movie->image);
      $stmt->bindParam(":category", $movie->category);
      $stmt->bindParam(":trailer", $movie->trailer);
      $stmt->bindParam(":length", $movie->length);
      $stmt->bindParam(":id", $movie->id);

      $stmt->execute();

      // Message of success for edit film
      $this->message->setMessage(
        "Movie successfully updated.",
        "success",
        "dashboard.php"
      );
    }


    /* ======================================
      Delete movie
    ====================================== */
    public function destroy($id){

      $stmt = $this->conn->prepare("DELETE FROM movies WHERE id = :id");

      $stmt->bindParam(":id", $id);

      $stmt->execute();

      // Message of success for removing film
      $this->message->setMessage(
        "Movie successfully removed.",
        "success",
        "back"
      );
    }


    /* ======================================
      Get all movies with user name
    ====================================== */
    public function getAllMovies() {

      $stmt = $this->conn->prepare("
        SELECT movies.*, users.name AS user_name
        FROM movies
        JOIN users ON movies.users_id = users.id
      ");

      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    /* ======================================
      Get all movies built as objects
    ====================================== */
    public function getAllMoviesBuilt() {

      $movies = [];

      $stmt = $this->conn->query("SELECT * FROM movies ORDER BY id DESC");

      if ($stmt->rowCount() > 0) {

        $moviesArray = $stmt->fetchAll();

        foreach($moviesArray as $movie) {
          $movies[] = $this->buildMovie($movie);
        }
      }

      return $movies;
    }

  }