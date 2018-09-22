<?php echo '<?php' ?>

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

class PrionUsersSeeder extends Seeder
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
        $credential = PrionApi\Models\{{ $api_credentials }}::create([
            'title' => "Default Credential",
            'description' => "The initial credential created by Prion API. DELETE THIS CREDENTIAL IN PRODUCTION. This credential has access to the full site.",
            'public_key' => "default_credential_public",
            'private_key' => "default_credential_private",
            'internal' => 1,
            'account_id' => 0, // Account who Created Credential (Prion Users)
            'user_id' => 0, // User who created credential (Prion Users)
            'expires_at' => Carbon::now('UTC')->addYears(10),
            'active' => 1,
            'created_at' => Carbon::now('UTC'),
            'updated_at' => Carbon::now('UTC'),
        ]);


        // Create a Default Token
        $token = PrionApi\Models\{{ $api_tokens }}::create([
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

    }
}