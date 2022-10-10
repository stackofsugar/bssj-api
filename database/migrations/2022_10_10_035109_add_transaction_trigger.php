<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER add_deposit_transc_trigger 
        AFTER INSERT ON `deposits` FOR EACH ROW
                BEGIN
                   INSERT INTO `transactions` (`id`,
                   `user_id`, `is_in`, `amount`, `created_at`,
                   `updated_at`) VALUES (NEW.id, NEW.user_id, 1, NEW.amount,
                   NEW.created_at, NEW.updated_at);
                END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `add_deposit_transc_trigger`');
    }
};
