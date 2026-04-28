# 🎬 MovieCircle

MovieCircle is a full-stack PHP web application where users can discover movies, leave reviews, rate films, and follow other users to build a social movie experience.

---

## 🚀 Features

- 🎥 Browse posted movies
- ⭐ Rate and review movies
- 👤 User authentication (register / login / logout)
- 🧑 User profiles with bio and profile image
- ➕ Add, edit, and delete movies
- 👥 Follow / unfollow other users
- 📊 Admin dashboard (manage users and movies)
- 🔒 Role-based access (admin vs normal users)
- 💬 Flash message system for user feedback

---

## 🛠️ Tech Stack

- PHP (OOP + MVC-style structure)
- MySQL (database)
- PDO (secure database queries)
- HTML5 / CSS3
- Bootstrap 5
- JavaScript / jQuery
- Font Awesome (icons)

---

## 📁 Project Structure
/config → Database connection
/dao → Data Access Objects (queries)
/models → Entities (User, Movie, Review)
/templates → Header & footer layout
/img → Images (users & movies)

---

## ⚙️ Setup Instructions

1. Clone the repository:

   git clone https://github.com/benevora/moviecircle_php.git
   
2. Move into the project folder:

  cd moviecircle

3. Configure the database:
  . Copy config/db_example.php → config/db.php
  . Update MySQL credentials

4. Import the database:
  .Import db_moviecircle.sql into MySQL

5. Run the project:
  . Use XAMPP / WAMP / MAMP
  . Start Apache & MySQL
  . Open in browser: http://localhost/moviecircle  
  
🔐 Default Admin Access
You can create an admin user manually in the database by setting: is_admin = 1