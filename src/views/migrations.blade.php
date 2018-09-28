<?php echo '<?php'; ?>

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PrionapiSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /**
         * Create Api Credentials Table
         *
         */
        Schema::create('{{ $prionapi['tables']['api_credentials'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->string('title', 100)
                ->nullable();
            $table
                ->text('description')
                ->nullable();
            $table
                ->string('public_key', 150)
                ->nullable();
            $table
                ->string('private_key', 250)
                ->nullable();
            $table
                ->integer('internal')
                ->default('0');
            $table
                ->integer('account_id')
                ->default('0');
            $table
                ->integer('user_id')
                ->default('0');
            $table
                ->dateTime('expires_at');
            $table
                ->integer('active')
                ->default('0');
            $table->timestamps();
        });


        /**
         * Link Each Credential with Permissions
         *
         */
        Schema::create('{{ $prionapi['tables']['api_credential_permissions'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->integer('api_credential_id')
                ->default('0');
            $table
                ->integer('permission_id')
                ->default('0');
            $table
                ->integer('read')
                ->default('0');
            $table
                ->integer('write')
                ->default('0');
            $table->timestamps();
        });


        /**
         * The Available API Permissions
         *
         */
        Schema::create('{{ $prionapi['tables']['api_permissions'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->string('title')
                ->nullable();
            $table
                ->text('description')
                ->nullable();
            $table
                ->integer('active')
                ->default('0');
        });


        /**
         * Link an API Permission Group with the Permission
         *
         */
        Schema::create('{{ $prionapi['tables']['api_permission_groups'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->integer('api_permission_id')
                ->nullable();
            $table
                ->integer('api_group_id')
                ->nullable();
        });


        /**
         * Groups of API Permissions
         *
         */
        Schema::create('{{ $prionapi['tables']['api_groups'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->string('title')
                ->nullable();
            $table
                ->text('description')
                ->nullable();
            $table
                ->integer('active')
                ->default('0');
        });


        /**
         * Store Tokens used to authenticate sessions
         *
         */
        Schema::create('{{ $prionapi['tables']['api_tokens'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->string('token')
                ->nullable();
            $table
                ->string('ip', 50)
                ->nullable();
            $table
                ->string('device_id', 100)
                ->nullable();
            $table
                ->integer('api_credential_id')
                ->default('0');
            $table
                ->string('type', 15)
                ->nullable()
                ->comments('initial, token, refresh');
            $table
                ->integer('active')
                ->default('0');
            $table
                ->dateTime('expires_at');
            $table->timestamps();
        });


        /**
         * Associate a User to a Token. If a user is associated, the user is logged into an account.
         *
         */
        Schema::create('{{ $prionapi['tables']['api_token_user'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->integer('api_token_id')
                ->default('0');
            $table
                ->integer('user_id')
                ->default('0');
            $table
                ->integer('active')
                ->default('0');
            $table
                ->dateTime('expires_at');
            $table->timestamps();
        });

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('{{ $prionapi['tables']['api_credentials'] }}');
        Schema::drop('{{ $prionapi['tables']['api_credential_permissions'] }}');
        Schema::drop('{{ $prionapi['tables']['api_groups'] }}');
        Schema::drop('{{ $prionapi['tables']['api_permissions'] }}');
        Schema::drop('{{ $prionapi['tables']['api_permission_groups'] }}');
        Schema::drop('{{ $prionapi['tables']['api_tokens'] }}');
        Schema::drop('{{ $prionapi['tables']['api_token_user'] }}');
    }
}