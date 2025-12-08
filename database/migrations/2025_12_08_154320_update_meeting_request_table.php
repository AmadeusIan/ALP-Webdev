<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('meeting_requests', function (Blueprint $table) {

        // Hapus foreign key
        if (Schema::hasColumn('meeting_requests', 'schedule_id')) {
            try {
                $table->dropForeign(['schedule_id']);
            } catch (\Exception $e) {}
            $table->dropColumn('schedule_id');
        }

        // Tambah kolom baru
        if (!Schema::hasColumn('meeting_requests', 'title')) {
            $table->string('title');
        }

        if (!Schema::hasColumn('meeting_requests', 'description')) {
            $table->text('description')->nullable();
        }

        if (!Schema::hasColumn('meeting_requests', 'start')) {
            $table->dateTime('start');
        }

        if (!Schema::hasColumn('meeting_requests', 'end')) {
            $table->dateTime('end');
        }

        // ubah status → enum
        $table->enum('status', ['pending', 'approved', 'rejected'])
            ->default('pending')
            ->change();

        if (!Schema::hasColumn('meeting_requests', 'updated_at')) {
            $table->timestamp('updated_at')->nullable()->after('created_at');
        }
    });
}

};
