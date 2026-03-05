<?php

    require_once("models/User.php");

    class UserDAO implements UserDAOInterface {

  public function buildUser($data): void {

  }

  public function create(User $user, $authUser = false): void {

  }

  public function update(User $user): void {

  }

  public function verifyToken($protected = false): void {

  }

  public function setTokenToSession($token, $redirect = true): void {

  }

  public function authenticateUser($email, $password): void {

  }

  public function findByEmail($email): void {

  }

  public function findById($id): void {

  }

  public function findByToken($token): void {

  }

  public function changePassword(User $user): void {

  }

  public function isAdmin(User $user): void {

  }

  public function setAdmin(User $user, $isAdmin = true): void {

  }

}