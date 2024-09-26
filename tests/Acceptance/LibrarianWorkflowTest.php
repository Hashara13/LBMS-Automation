<?php

namespace Tests\Acceptance;

use PHPUnit\Framework\TestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Firefox\FirefoxOptions;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class LibrarianWorkflowTest extends TestCase
{
    protected $webDriver;
    protected function setUp(): void
    {
        $host = 'http://localhost:4444/wd/hub';
        $capabilities = DesiredCapabilities::firefox();
        $options = new FirefoxOptions();
        $capabilities->setCapability(FirefoxOptions::CAPABILITY, $options);
        $this->webDriver = RemoteWebDriver::create($host, $capabilities);
    }

    protected function tearDown(): void
    {
        $this->webDriver->quit();
    }

    public function testLibrarianWorkflow()
    {
        $this->webDriver->get('http://localhost/your-project-path/librarian/index.php');
        $this->webDriver->findElement(WebDriverBy::name('l_user'))->sendKeys('harry');
        $this->webDriver->findElement(WebDriverBy::name('l_pass'))->sendKeys('librarian');
        $this->webDriver->findElement(WebDriverBy::name('l_login'))->click();
        $this->assertEquals('http://localhost/your-project-path/librarian/home.php', $this->webDriver->getCurrentURL());

        $this->webDriver->findElement(WebDriverBy::linkText('Insert New Book Record'))->click();
        $this->webDriver->findElement(WebDriverBy::name('b_isbn'))->sendKeys('1234567890');
        $this->webDriver->findElement(WebDriverBy::name('b_title'))->sendKeys('Test Book');
        $this->webDriver->findElement(WebDriverBy::name('b_author'))->sendKeys('Test Author');
        $this->webDriver->findElement(WebDriverBy::name('b_category'))->sendKeys('Fiction');
        $this->webDriver->findElement(WebDriverBy::name('b_price'))->sendKeys('19.99');
        $this->webDriver->findElement(WebDriverBy::name('b_copies'))->sendKeys('5');
        $this->webDriver->findElement(WebDriverBy::name('b_add'))->click();
        $this->assertStringContainsString('New book record has been added', $this->webDriver->findElement(WebDriverBy::cssSelector('.success-message'))->getText());

        $this->webDriver->get('http://localhost/your-project-path/librarian/update_copies.php');
        $this->webDriver->findElement(WebDriverBy::name('b_isbn'))->sendKeys('1234567890');
        $this->webDriver->findElement(WebDriverBy::name('b_copies'))->sendKeys('10');
        $this->webDriver->findElement(WebDriverBy::name('b_add'))->click();
        $this->assertStringContainsString('Number of book copies has been updated', $this->webDriver->findElement(WebDriverBy::cssSelector('.success-message'))->getText());

        $this->webDriver->get('http://localhost/your-project-path/librarian/display_books.php');
        $bookList = $this->webDriver->findElements(WebDriverBy::cssSelector('table tr'));
        $this->assertGreaterThan(1, count($bookList));
        $testBookRow = $this->webDriver->findElement(WebDriverBy::xpath("//tr[contains(., 'Test Book')]"));
        $this->assertStringContainsString('15', $testBookRow->getText());

        $this->webDriver->get('http://localhost/your-project-path/librarian/delete_book.php');
        $deleteLink = $this->webDriver->findElement(WebDriverBy::xpath("//a[contains(@href, 'dltbook.php?id=1234567890')]"));
        $deleteLink->click();
        $this->assertStringContainsString('Book record has been deleted', $this->webDriver->findElement(WebDriverBy::cssSelector('.success-message'))->getText());

        $this->webDriver->get('http://localhost/your-project-path/librarian/display_books.php');
        $this->assertEmpty($this->webDriver->findElements(WebDriverBy::xpath("//tr[contains(., 'Test Book')]")));
    }
}