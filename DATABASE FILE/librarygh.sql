

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";




DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_due_list`()
    NO SQL
SELECT I.issue_id, M.email, B.isbn, B.title
FROM book_issue_log I INNER JOIN member M on I.member = M.username INNER JOIN book B ON I.book_isbn = B.isbn
WHERE DATEDIFF(CURRENT_DATE, I.due_date) >= 0 AND DATEDIFF(CURRENT_DATE, I.due_date) % 5 = 0 AND (I.last_reminded IS NULL OR DATEDIFF(I.last_reminded, CURRENT_DATE) <> 0)$$

DELIMITER ;

CREATE TABLE IF NOT EXISTS `book` (
  `isbn` char(13) NOT NULL,
  `title` varchar(80) NOT NULL,
  `author` varchar(80) NOT NULL,
  `category` varchar(80) NOT NULL,
  `price` int(4) unsigned NOT NULL,
  `copies` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



INSERT INTO `book` (`isbn`, `title`, `author`, `category`, `price`, `copies`) VALUES
('6900152484440', 'V for Vendetta', 'Alan Moore', 'Comics', 299, 13),
('9782616052277', 'X-Men: God Loves, Man Kills', 'Chris', 'Comics', 399, 33),
('9783161484100', 'Mike Tyson : Undisputed Truth', 'Larry Sloman, Mike Tyson', 'Sports', 299, 19),
('9789996245442', 'When Breath Becomes Air', 'Paul Kalanithi', 'Medical', 515, 9),
('9885691200700', 'The Great Gatsby', 'F. Scott Fitzgerald', 'Fiction', 420, 20);



CREATE TABLE IF NOT EXISTS `book_issue_log` (
`issue_id` int(11) NOT NULL,
  `member` varchar(20) NOT NULL,
  `book_isbn` varchar(13) NOT NULL,
  `due_date` date NOT NULL,
  `last_reminded` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;


DELIMITER //
CREATE TRIGGER `issue_book` BEFORE INSERT ON `book_issue_log`
 FOR EACH ROW BEGIN
	SET NEW.due_date = DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY);
    UPDATE member SET balance = balance - (SELECT price FROM book WHERE isbn = NEW.book_isbn) WHERE username = NEW.member;
    UPDATE book SET copies = copies - 1 WHERE isbn = NEW.book_isbn;
    DELETE FROM pending_book_requests WHERE member = NEW.member AND book_isbn = NEW.book_isbn;
END
//
DELIMITER ;
DELIMITER //
CREATE TRIGGER `return_book` BEFORE DELETE ON `book_issue_log`
 FOR EACH ROW BEGIN
    UPDATE member SET balance = balance + (SELECT price FROM book WHERE isbn = OLD.book_isbn) WHERE username = OLD.member;
    UPDATE book SET copies = copies + 1 WHERE isbn = OLD.book_isbn;
END
//
DELIMITER ;



CREATE TABLE IF NOT EXISTS `librarian` (
`id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` char(40) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;



INSERT INTO `librarian` (`id`, `username`, `password`) VALUES
(1, 'harry', '93c768d0152f72bc8d5e782c0b585acc35fb0442');



CREATE TABLE IF NOT EXISTS `member` (
`id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` char(40) NOT NULL,
  `name` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `balance` int(4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;


DELIMITER //
CREATE TRIGGER `add_member` AFTER INSERT ON `member`
 FOR EACH ROW DELETE FROM pending_registrations WHERE username = NEW.username
//
DELIMITER ;
DELIMITER //
CREATE TRIGGER `remove_member` AFTER DELETE ON `member`
 FOR EACH ROW DELETE FROM pending_book_requests WHERE member = OLD.username
//
DELIMITER ;



CREATE TABLE IF NOT EXISTS `pending_book_requests` (
`request_id` int(11) NOT NULL,
  `member` varchar(20) NOT NULL,
  `book_isbn` varchar(13) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `pending_registrations` (
  `username` varchar(20) NOT NULL,
  `password` char(40) NOT NULL,
  `name` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `balance` int(4) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



INSERT INTO `pending_registrations` (`username`, `password`, `name`, `email`, `balance`, `time`) VALUES
('christine', '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', 'Christine', 'christine400eer@gmail.com', 999, '2021-03-21 08:29:00'),
('steeve', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Steeve Rogers', 'thisissteeve69@gmail.com', 1500, '2021-03-21 12:14:53');


ALTER TABLE `book`
 ADD PRIMARY KEY (`isbn`);


ALTER TABLE `book_issue_log`
 ADD PRIMARY KEY (`issue_id`);


ALTER TABLE `librarian`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`);


ALTER TABLE `member`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`), ADD UNIQUE KEY `email` (`email`);


ALTER TABLE `pending_book_requests`
 ADD PRIMARY KEY (`request_id`);


ALTER TABLE `pending_registrations`
 ADD PRIMARY KEY (`username`), ADD UNIQUE KEY `email` (`email`);


ALTER TABLE `book_issue_log`
MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;

ALTER TABLE `librarian`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;

ALTER TABLE `member`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;

ALTER TABLE `pending_book_requests`
MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
