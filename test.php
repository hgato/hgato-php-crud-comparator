<?php

require_once implode(DIRECTORY_SEPARATOR, [__DIR__, 'tests', 'Test.php']);

(new Test())->testPureObjects();
(new Test())->testPureArrays();