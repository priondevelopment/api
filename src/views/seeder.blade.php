<?php echo '<?php'; ?>

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

class PrionApiSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        // Create Default Credentials
        $this->command->info('Create Default API Credentials');
        $credential = {{ $models['api_credentials'] }}::create([
            'title' => "Default Credential",
            'description' => "The initial credential created by Prion API. DELETE THIS CREDENTIAL IN PRODUCTION. This credential has access to the full site.",
            'public_key' => "{{ $defaults['credentials']['public_key'] }}",
            'private_key' => "{{ $defaults['credentials']['private_key'] }}",
            'internal' => 1,
            'account_id' => 0, // Account who Created Credential (Prion Users)
            'user_id' => 0, // User who created credential (Prion Users)
            'expires_at' => Carbon::now('UTC')->addYears(10),
            'active' => 1,
            'created_at' => Carbon::now('UTC'),
            'updated_at' => Carbon::now('UTC'),
        ]);


        // Create a Default Token
        $token = {{ $models['api_tokens'] }}::create([
            'token' => 'default_token',
            'ip' => '',
            'device_id' => '',
            'api_credential_id' => $credential->id,
            'type' => "",
            'active' => 1,
            'expires_at' => Carbon::now('UTC')->addYears(1),
            'created_at' => Carbon::now('UTC'),
            'updated_at' => Carbon::now('UTC'),
        ]);

        $this->permissions($credential);
    }


	/**
	 *	Push Permissions into Database Seeder
	 *
	 */
    protected function permissions($credential)
    {
        $this->command->info('Permission group: create the admin permission group');
        $permissionGroup = {{ $models['permission_groups'] }}::create([
            'name' => 'Admin Credentials',
            'description' => 'This is the default admin credentials permission group. It has permission to do everything.',
            'type' => 'credentials',
            'account_id' => 0,
        ]);

        $this->command->info('Add Credentials to Admin Permission Group');
        {{ $models['api_credential_permission_groups'] }}::create([
            'api_credential_id' => $credential->id,
            'permission_group_id' => $permissionGroup->id,
            'expires_at' => null,
        ]);


        // Read Credentials
        $this->command->info('Read Permission: credential_read');
        $credentialReadPermission = {{ $models['permissions'] }}::create([
            'name' => 'Credential: Read',
            'slug' => 'credential_read',
            'description' => "Access to view credentials in the platform.",
            'active' => 1,
            'created_at' => Carbon::now('UTC'),
            'updated_at' => Carbon::now('UTC'),
        ]);
        {{ $models['permission_group_permissions']}}::create([
            'permission_group_id' => $permissionGroup->id,
            'permission_id' => $credentialReadPermission->id,
            'expires_at' => null,
        ]);


        // Write Credentials
        $this->command->info('Insert Permission: credential_write');
        $credentialWritePermission = {{ $models['permissions'] }}::create([
            'name' => 'Credential: Write',
            'slug' => 'credential_write',
            'description' => "Access to create or edit credentials in the platform.",
            'active' => 1,
            'created_at' => Carbon::now('UTC'),
            'updated_at' => Carbon::now('UTC'),
        ]);
        {{ $models['permission_group_permissions']}}::create([
            'permission_group_id' => $permissionGroup->id,
            'permission_id' => $credentialWritePermission->id,
            'expires_at' => null,
        ]);


        // Read Permissions
        $this->command->info('Read Permission: permission_read');
        $permissionRead = {{ $models['permissions'] }}::create([
            'name' => 'Permission: Read',
            'slug' => 'permission_read',
            'description' => "Read permissions on the platform.",
            'active' => 1,
            'created_at' => Carbon::now('UTC'),
            'updated_at' => Carbon::now('UTC'),
        ]);
        {{ $models['permission_group_permissions']}}::create([
            'permission_group_id' => $permissionGroup->id,
            'permission_id' => $permissionRead->id,
            'expires_at' => null,
        ]);


        // Write Permissions
        $this->command->info('Insert Permission: permission_write');
        $permissionWrite = {{ $models['permissions'] }}::create([
            'name' => 'Permission: Write',
            'slug' => 'permission_write',
            'description' => "Read and write permissions on the platform.",
            'active' => 1,
            'created_at' => Carbon::now('UTC'),
            'updated_at' => Carbon::now('UTC'),
        ]);
        {{ $models['permission_group_permissions']}}::create([
            'permission_group_id' => $permissionGroup->id,
            'permission_id' => $permissionWrite->id,
            'expires_at' => null,
        ]);


        // Read Permission Groups
        $this->command->info('Read Permission: permission_group_read');
        $permissionGroupRead = {{ $models['permissions'] }}::create([
            'name' => 'Permission Group: Read',
            'slug' => 'permission_group_read',
            'description' => "Read permission groups on the platform.",
            'active' => 1,
            'created_at' => Carbon::now('UTC'),
            'updated_at' => Carbon::now('UTC'),
        ]);
        {{ $models['permission_group_permissions']}}::create([
            'permission_group_id' => $permissionGroup->id,
            'permission_id' => $permissionGroupRead->id,
            'expires_at' => null,
        ]);


        // Create and Edit Permission Groups
        $this->command->info('Insert Permission: permission_group_write');
        $permissionGroupWrite = {{ $models['permissions'] }}::create([
            'name' => 'Permission Group: Write',
            'slug' => 'permission_group_write',
            'description' => "Read and write permission groups on the platform.",
            'active' => 1,
            'created_at' => Carbon::now('UTC'),
            'updated_at' => Carbon::now('UTC'),
        ]);
        {{ $models['permission_group_permissions']}}::create([
            'permission_group_id' => $permissionGroup->id,
            'permission_id' => $permissionGroupWrite->id,
            'expires_at' => null,
        ]);


        // Manage Permission Group Permissions
        $this->command->info('Manage Permission Groups (Add and remove permissions from groups): permission_group_manage');
        $permissionGroupManage = {{ $models['permissions'] }}::create([
            'name' => 'Permission Group: Manage',
            'slug' => 'permission_group_manage',
            'description' => "Manage permissions in permission groups on the platform.",
            'active' => 1,
            'created_at' => Carbon::now('UTC'),
            'updated_at' => Carbon::now('UTC'),
        ]);
        {{ $models['permission_group_permissions']}}::create([
            'permission_group_id' => $permissionGroup->id,
            'permission_id' => $permissionGroupManage->id,
            'expires_at' => null,
        ]);


        // Manage Credential Permission Groups
        $this->command->info('Manage Credential Permission Groups (Add and remove permission groups from credentials): permission_credential_manage');
        $credentialPermissionGroup = {{ $models['permissions'] }}::create([
            'name' => 'Credential Permission Group: Manage',
            'slug' => 'permission_credential_manage',
            'description' => "Manage manage credential permission groups on the platform.",
            'active' => 1,
            'created_at' => Carbon::now('UTC'),
            'updated_at' => Carbon::now('UTC'),
        ]);
        {{ $models['permission_group_permissions']}}::create([
            'permission_group_id' => $permissionGroup->id,
            'permission_id' => $credentialPermissionGroup->id,
            'expires_at' => null,
        ]);
    }
}