<?php

class LibraryManagementCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/');
    }

    public function tryToLogin(AcceptanceTester $I)
    {
        $I->see('Library Management System');
        $I->fillField('username', 'harry');
        $I->fillField('password', 'librarian');
        $I->click('Login');
        $I->see('Welcome, Librarian');
    }

    public function tryToAddBook(AcceptanceTester $I)
    {
        $this->tryToLogin($I);

        $I->amOnPage('/librarian/add-book');
        $I->fillField('title', 'Test Book');
        $I->fillField('author', 'Test Author');
        $I->fillField('isbn', '1234567890');
        $I->click('Add Book');
        $I->see('Book added successfully');
    }
    public function testSearchAndDisplayBooks(AcceptanceTester $I)
    {
        $I->amOnPage('/librarian/login.php');
        $I->fillField('l_user', 'librarian');
        $I->fillField('l_pass', 'password');
        $I->click('Login');

        $books = [
            ['8888888888', 'Acceptance Test Book 1', 'Author 1', 'Fiction', '24.99', '3'],
            ['9999999999', 'Acceptance Test Book 2', 'Author 2', 'Non-Fiction', '34.99', '2'],
        ];

        foreach ($books as $book) {
            $I->amOnPage('/librarian/insert_book.php');
            $I->fillField('b_isbn', $book[0]);
            $I->fillField('b_title', $book[1]);
            $I->fillField('b_author', $book[2]);
            $I->fillField('b_category', $book[3]);
            $I->fillField('b_price', $book[4]);
            $I->fillField('b_copies', $book[5]);
            $I->click('Insert Book');
            $I->see('Book inserted successfully');
        }

        $I->amOnPage('/librarian/search_books.php');
        $I->fillField('search_query', 'Acceptance Test');
        $I->click('Search');
        $I->see('Acceptance Test Book 1');
        $I->see('Acceptance Test Book 2');

        $I->amOnPage('/librarian/display_books.php');
        $I->see('Acceptance Test Book 1');
        $I->see('Acceptance Test Book 2');
        $I->see('Author 1');
        $I->see('Author 2');

        foreach ($books as $book) {
            $I->amOnPage('/librarian/delete_book.php');
            $I->fillField('rd_book', $book[0]);
            $I->click('Delete Book');
            $I->see('Book deleted successfully');
        }
    }

    public function testUpdateBookCopies(AcceptanceTester $I)
    {
        $I->amOnPage('/librarian/login.php');
        $I->fillField('l_user', 'librarian');
        $I->fillField('l_pass', 'password');
        $I->click('Login');

        $I->amOnPage('/librarian/insert_book.php');
        $I->fillField('b_isbn', '1010101010');
        $I->fillField('b_title', 'Update Copies Test Book');
        $I->fillField('b_author', 'Test Author');
        $I->fillField('b_category', 'Test Category');
        $I->fillField('b_price', '19.99');
        $I->fillField('b_copies', '5');
        $I->click('Insert Book');
        $I->see('Book inserted successfully');

        // Update book copies
        $I->amOnPage('/librarian/update_copies.php');
        $I->fillField('b_isbn', '1010101010');
        $I->fillField('b_copies', '8');
        $I->click('Update Copies');
        $I->see('Number of book copies has been updated');

        // Verify the update
        $I->amOnPage('/librarian/display_books.php');
        $I->see('Update Copies Test Book');
        $I->see('Copies: 8');

        $I->amOnPage('/librarian/delete_book.php');
        $I->fillField('rd_book', '1010101010');
        $I->click('Delete Book');
        $I->see('Book deleted successfully');
    }

    public function testMemberLogin(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Member Login');
        $I->click('Member Login');
        $I->fillField('m_user', 'testmember');
        $I->fillField('m_pass', 'memberpassword');
        $I->click('Login');
        $I->see('Welcome, Test Member');
    }

    public function testMemberBookSearch(AcceptanceTester $I)
    {      $I->amOnPage('/member/login.php');
        $I->fillField('m_user', 'testmember');
        $I->fillField('m_pass', 'memberpassword');
        $I->click('Login');

        $I->amOnPage('/member/search_books.php');
        $I->fillField('search_query', 'Programming');
        $I->click('Search');
        $I->see('Search Results');
        $I->see('Programming');
    }
}