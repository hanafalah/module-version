<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Hanafalah\ModuleVersion\Models\Version\ModelHasVersion;

return new class extends Migration
{
    use Hanafalah\LaravelSupport\Concerns\NowYouSeeMe;

    private $__table;

    public function __construct()
    {
        $this->__table = app(config('database.models.ModelHasVersion', ModelHasVersion::class));
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $table_name = $this->__table->getTable();
        if (!$this->isTableExists()) {
            Schema::create($table_name, function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->string('model_type', 50)->nullable(false);
                $table->string('model_id', 36)->nullable(false);
                $table->string('name', 15)->nullable(false)->comment('fill it like 1.0.120 or 1.0.^ or 1.^');
                $table->enum('current', ['1', '0'])->coment('1: active current, 0: for not current')->default(1);
                $table->json('props')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['model_type', 'model_id'], 'model_morph_version');
            });
        }

        if (!Schema::hasColumn($table_name, 'parent_id')) {
            Schema::table($table_name, function (Blueprint $table) use ($table_name) {
                $table->foreignIdFor($this->__table::class, 'parent_id')
                    ->after('id')->nullable()->index()->constrained($table_name)
                    ->cascadeOnUpdate()->restrictOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->__table->getTable());
    }
};
