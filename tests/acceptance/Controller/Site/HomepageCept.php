<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('Ensure that homepage works');
$I->amOnPage('/');
$I->see('Litpiman');
