<?php
use PHPUnit\Framework\TestCase;

class IndexPageTest extends TestCase
{
  
    public function testNavigationButtonsExist()
    {
        ob_start();
        include __DIR__ . '/../index.php'; 
        $htmlOutput = ob_get_clean();

        $this->assertStringContainsString('Member Login', $htmlOutput, "The Member Login button should exist.");

        $this->assertStringContainsString('Librarian Login', $htmlOutput, "The Librarian Login button should exist.");
    }
}
