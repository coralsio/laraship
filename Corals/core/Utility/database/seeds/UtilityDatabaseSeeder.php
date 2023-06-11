<?php

namespace Corals\Utility\database\seeds;

use Corals\Menu\Models\Menu;
use Corals\Settings\Models\Setting;
use Corals\User\Communication\Models\NotificationTemplate;
use Corals\User\Models\Permission;
use Illuminate\Database\Seeder;
use \Spatie\MediaLibrary\MediaCollections\Models\Media;

class UtilityDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UtilityPermissionsDatabaseSeeder::class);
        $this->call(UtilityMenuDatabaseSeeder::class);
        $this->call(UtilitySettingsDatabaseSeeder::class);
        $this->call(UtilityNotificationTemplatesSeeder::class);

    }

    public function rollback()
    {
        Permission::where('name', 'like', 'Utility::%')->delete();
        Permission::where('name', 'Administrations::admin.utility')->delete();

        Menu::where('key', 'utility')
            ->orWhere('active_menu_url', 'like', 'utilitys%')
            ->orWhere('url', 'like', 'utilitys%')
            ->delete();

        Setting::where('category', 'Utilities')->delete();

        Media::whereIn('collection_name', ['utility-media-collection'])->delete();

        NotificationTemplate::where('name', 'like', 'notifications.utility%')->delete();

    }
}
