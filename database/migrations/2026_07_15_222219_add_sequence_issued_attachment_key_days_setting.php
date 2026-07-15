<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddSequenceIssuedAttachmentKeyDaysSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!DB::table('settings')->where('type', 'SEQUENCE_ISSUED_ATTACHMENT_KEY_DAYS')->exists()) {
            DB::table('settings')->insert([
                'type' => 'SEQUENCE_ISSUED_ATTACHMENT_KEY_DAYS',
                'value' => 7,
                'updated_id' => DB::table('users')->orderBy('id')->value('id'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('settings')->where('type', 'SEQUENCE_ISSUED_ATTACHMENT_KEY_DAYS')->delete();
    }
}
