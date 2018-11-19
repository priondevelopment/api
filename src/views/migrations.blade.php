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
        if (!Schema::hasTable('{{ $prionapi['tables']['api_credentials'] }}')) {
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
                    ->tinyInteger('internal')
                    ->default('0');
                $table
                    ->tinyInteger('active')
                    ->default('1');
                $table
                    ->integer('account_id')
                    ->default('0');
                $table
                    ->integer('user_id')
                    ->default('0');
                $table
                    ->dateTime('expires_at');
                $table
                    ->timestamp('created_at')
                    ->useCurrent();
                $table
                    ->timestamp('updated_at')
                    ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

                $table->index('public_key', 'index_api_credentials_public_key');
                $table->index('user_id', 'index_api_credentials_user_id');
                $table->index('account_id', 'index_api_credentials_account_id');
            });
        }


        /**
         * Store Tokens used to authenticate sessions
         *
         */
        if (!Schema::hasTable('api_tokens')) {
            Schema::create('{{ $prionapi['tables']['api_tokens'] }}', function (Blueprint $table) {
                $table->increments('id');
                $table
                    ->string('token')
                    ->nullable();
                $table
                    ->string('ip', 50)
                    ->nullable();
                $table
                    ->unsignedInteger('api_credential_id');
                $table
                    ->string('device_id', 100)
                    ->nullable();
                $table
                    ->string('type', 15)
                    ->nullable()
                    ->comments('initial, token, refresh');
                $table
                    ->tinyInteger('active')
                    ->default('0');
                $table
                    ->dateTime('expires_at');
                $table
                    ->timestamp('created_at')
                    ->useCurrent();
                $table
                    ->timestamp('updated_at')
                    ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

                $table->foreign('api_credential_id')
                      ->references('id')->on('{{ $prionapi['tables']['api_credentials'] }}')
                      ->onDelete('cascade');

                $table->index('token', 'index_api_tokens_token');
                $table->index('api_credential_id', 'index_api_tokens_api_credential_id');
            });
        }

        /**
         * The Available API Permissions
         *
         */
        Schema::create('{{ $prionapi['tables']['permissions'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->string('name', 200)
                ->nullable();
            $table
                ->string('slug', 150)
                ->nullable();
            $table
                ->text('description')
                ->nullable();
			$table
				->tinyInteger('admin')
				->default('0');
			$table
				->tinyInteger('active')
				->default('0');
            $table
                ->timestamp('created_at')
                ->useCurrent();
            $table
                ->timestamp('updated_at')
                ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->index('slug', 'index_permissions_slug');
        });


        /**
         * Create Permission Groups
         *
         */
        Schema::create('{{ $prionapi['tables']['permission_groups'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->string('name',200)
                ->nullable();
			$table
				->text('description');
			$table
                ->enum('type',['credentials','users'])
                ->nullable();
            $table
                ->integer('account_id')
                ->default('0');
            $table
                ->timestamp('created_at')
                ->useCurrent();
            $table
                ->timestamp('updated_at')
                ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

			$table->index('account_id', 'index_permission_groups_account_id');
        });


        /**
         * Add Permissions to each Permission Group
         *
         *
         */
		Schema::create('{{ $prionapi['tables']['permission_group_permissions'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->unsignedInteger('permission_group_id')
                ->default('0');
            $table
                ->unsignedInteger('permission_id')
                ->default('0');
			$table
                ->dateTime('expires_at');
			$table
                ->dateTime('created_at')
                ->useCurrent();

			$table->foreign('permission_group_id')
				->references('id')->on('{{ $prionapi['tables']['permission_groups'] }}')
				->onDelete('cascade');
			$table->foreign('permission_id')
				->references('id')->on('{{ $prionapi['tables']['permissions'] }}')
				->onDelete('cascade');
        });

        /**
         * Link Each Credential with a Permission Group
         *
         */
        Schema::create('{{ $prionapi['tables']['api_credential_permission_groups'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->unsignedInteger('api_credential_id')
                ->default('0');
            $table
                ->unsignedInteger('permission_group_id')
                ->default('0');
            $table
                ->dateTime('expires_at');
            $table
                ->dateTime('created_at')
                ->useCurrent();

            $table->foreign('api_credential_id')
                ->references('id')->on('{{ $prionapi['tables']['api_credentials'] }}')
                ->onDelete('cascade');
            $table->foreign('permission_group_id')
                ->references('id')->on('{{ $prionapi['tables']['permission_groups'] }}')
                ->onDelete('cascade');
        });

        /**
         * Link Each Credential with Permissions
         *
         */
        Schema::create('{{ $prionapi['tables']['api_credential_permissions'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->unsignedInteger('api_credential_id')
                ->default('0');
            $table
                ->unsignedInteger('permission_id')
                ->default('0');
            $table
                ->tinyInteger('read')
                ->default('0');
            $table
                ->tinyInteger('write')
                ->default('0');
            $table
                ->dateTime('expires_at');
            $table
                ->timestamp('created_at')
                ->useCurrent();
            $table
                ->timestamp('updated_at')
                ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('api_credential_id')
                ->references('id')->on('{{ $prionapi['tables']['api_credentials'] }}')
                ->onDelete('cascade');
            $table->foreign('permission_id')
                ->references('id')->on('{{ $prionapi['tables']['permissions'] }}')
                ->onDelete('cascade');
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
        Schema::drop('{{ $prionapi['tables']['api_tokens'] }}');
        Schema::drop('{{ $prionapi['tables']['api_credential_permissions'] }}');
        Schema::drop('{{ $prionapi['tables']['api_credential_permission_groups'] }}');
        Schema::drop('{{ $prionapi['tables']['permissions'] }}');
        Schema::drop('{{ $prionapi['tables']['permission_groups'] }}');
        Schema::drop('{{ $prionapi['tables']['permission_group_permissions'] }}');
    }
}