<?php


\DB::statement('ALTER TABLE `utility_comments` MODIFY `author_id` INTEGER UNSIGNED NULL;');

\DB::statement('ALTER TABLE `utility_comments` MODIFY `author_type` varchar(191)  NULL;');
