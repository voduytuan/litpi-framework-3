<?php
$I = new FunctionalTester($scenario);
$I->wantTo('Check Homepage page');
$I->amOnPage('site');
$I->see('Litpiman');
$I->amOnPage('admin/login');
$I->see('Password');
