<?php
namespace LMS;

class Librarian {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM librarians WHERE username = ? AND password = ?");
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function insertBook($isbn, $title, $author, $genre, $price, $copies) {
        $stmt = $this->db->prepare("INSERT INTO books (isbn, title, author, genre, price, copies) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssdii', $isbn, $title, $author, $genre, $price, $copies);
        return $stmt->execute();
    }

    public function updateBookCopies($isbn, $copies) {
        $stmt = $this->db->prepare("UPDATE books SET copies = ? WHERE isbn = ?");
        $stmt->bind_param('is', $copies, $isbn);
        return $stmt->execute();
    }

    public function deleteBook($isbn) {
        $stmt = $this->db->prepare("DELETE FROM books WHERE isbn = ?");
        $stmt->bind_param('s', $isbn);
        return $stmt->execute();
    }

    public function displayBooks() {
        $stmt = $this->db->prepare("SELECT * FROM books");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

