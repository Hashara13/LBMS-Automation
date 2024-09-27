<?php

class Librarian
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Function to log in a librarian
    public function login($username, $password)
    {
        // Simple query to check the librarian credentials
        $stmt = $this->db->prepare("SELECT * FROM librarians WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        // If we get a row, the login is successful
        if ($result->num_rows > 0) {
            return true;
        }
        return false;
    }

    // You can add more functions like addBook(), deleteBook(), etc.
}

